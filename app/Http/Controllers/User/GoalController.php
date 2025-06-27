<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\GoalCompletedConfirmation;
use App\Mail\GoalCreatedConfirmation;
use App\Mail\GoalDeletedConfirmation;
use App\Mail\GoalToppedUpConfirmation;
use App\Models\Goal;
use App\Models\GoalCategory;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Log;
use Throwable;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goals = Goal::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Get primary goal only if there are at least two goals and at least one active
        $primary = null;
        if ($goals->count() > 1 && $goals->where('current_amount', '>', 0)->count() > 0) {
            $primary = $goals
                ->whereNull('completed_at')
                ->where('current_amount', '>', 0)
                ->sortByDesc('current_amount')
                ->first();
        }

        return view('user.goals.index', [
            'title' => 'Account - My Goals & Savings',
            'goals' => $goals,
            'primary' => $primary,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.goals.create', [
            'title' => 'Account - Create - Goals & Savings',
            'categories' => GoalCategory::where('is_active', 1)->latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:goal_categories,id',
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:1000',
            'target_amount' => 'required|numeric|min:0|max:999999999.99',
            'current_amount' => 'nullable|numeric|min:0|max:999999999.99',
            'target_date' => 'required|date|after_or_equal:' . Carbon::today()->addMonth()->toDateString(),
            'priority' => 'required|in:low,medium,high',
            'monthly_target' => 'nullable|numeric|min:0|max:999999999.99',
            'is_public' => 'nullable',
            'milestones' => 'nullable|json',
            'goal_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'target_date.after_or_equal' => 'The target date must be at least one month from today.',
        ]);

        try {

            $user = Auth::user();

            $transactionId = 'GOAL_CREATED_' . Str::uuid();
            $description = "New Goal Created";

            DB::beginTransaction();

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all(),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Additional business logic validations
            $additionalValidation = $this->performAdditionalValidations($request, $user);
            if ($additionalValidation !== true) {
                return response()->json([
                    'status' => 'error',
                    'message' => $additionalValidation
                ], 422);
            }

            // Parse milestones if provided
            $milestones = null;
            if ($request->has('milestones') && !empty($request->milestones)) {
                $milestones = json_decode($request->milestones, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid milestones format.'
                    ], 422);
                }
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('goal_image')) {
                $imagePath = $this->handleImageUpload($request->file('goal_image'));
                if (!$imagePath) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to upload image. Please try again.'
                    ], 500);
                }
            }

            // Create the goal
            $goal = Goal::create([
                'user_id' => Auth::id(),
                'goal_category_id' => $request->category_id,
                'title' => trim($request->title),
                'description' => trim($request->description),
                'target_amount' => $request->target_amount,
                'current_amount' => $request->current_amount ?? 0,
                'target_date' => $request->target_date,
                'start_date' => now(),
                'priority' => $request->priority,
                'monthly_target' => $request->monthly_target,
                'is_public' => $request->boolean('is_public'),
                'milestones' => $milestones,
                'image_url' => $imagePath,
                'status' => 'active',
            ]);

            Transaction::create([
                'user_id' => auth()->id(),
                'reference_id' => $transactionId,
                'amount' => $request->target_amount,
                'bank_name' => config('app.name'),
                'account_number' => auth()->user()->profile->account_number,
                'trans_type' => 'credit',
                'receiver_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'description' => $description,
                'acct_type' => auth()->user()->profile->acct_type ?? 'savings',
                'trans_status' => 'pending'
            ]);

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to the user
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new GoalCreatedConfirmation($goal));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Goal created successfully! Good luck achieving it.',
                'redirect' => route('user.goal')
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error('Goal creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }

    /**
     * Top up a savings goal.
     *
     * @throws Throwable
     */
    public function fund(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'top_up_amount' => 'required|numeric|min:0|max:999999999.99',
        ]);

        $user = Auth::user();

        // Ensure the user has enough savings to top up with
        if ($validated['top_up_amount'] > $user->balance) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not enough funds in your wallet to complete this top-up.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update goal status
            $goal->current_amount += $validated['top_up_amount'];
            $goal->save();

            // Create a transaction record
            $transactionId = 'GOAL_TOP_UP_' . Str::uuid();
            $description = 'Top-Up for ' . $goal->title;

            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $validated['top_up_amount'],
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number ?? 'N/A',
                'trans_type' => 'credit',
                'receiver_name' => $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved',
            ]);

            // Deduct from user's main balance
            $user->decrement('balance', $validated['top_up_amount']);

            DB::commit();

            // Send email notification
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new GoalToppedUpConfirmation($goal, $validated['top_up_amount']));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Your savings goal has been successfully topped up!',
                'goal' => $goal,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Goal top-up failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to process your top-up. Please try again later.',
                'error' => app()->environment('production') ? null : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Withdraw funds from the specified goal and mark it as completed.
     *
     * @param  Request  $request
     * @param  Goal  $goal
     * @return JsonResponse
     * @throws Throwable
     */
    public function withdraw(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'withdrawal_amount' => 'required|numeric|min:0|max:999999999.99',
        ]);

        $user = Auth::user();

        // Ensure the user has enough savings to withdraw
        if ($validated['withdrawal_amount'] > $goal->current_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient savings. You cannot withdraw more than your available goal balance.',
            ], 422);
        }

        // Ensure the withdrawal amount matches the exact current savings
        if ($validated['withdrawal_amount'] != $goal->current_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Withdrawal must match your exact savings of $' . number_format($goal->current_amount, 2) . '.',
            ], 422);
        }

        try {

            DB::beginTransaction();

            // Update goal status
            $goal->status = 'completed';
            $goal->completed_at = now();
            $goal->save();

            // Create a transaction record
            $transactionId = 'GOAL_COMPLETED_' . Str::uuid();
            $description = 'Goal Completed';

            Transaction::create([
                'user_id' => $user->id,
                'reference_id' => $transactionId,
                'amount' => $validated['withdrawal_amount'],
                'bank_name' => config('app.name'),
                'account_number' => $user->profile->account_number,
                'trans_type' => 'credit',
                'receiver_name' => $user->first_name . ' ' . $user->last_name,
                'description' => $description,
                'acct_type' => $user->profile->acct_type ?? 'savings',
                'trans_status' => 'approved',
            ]);

            // Credit user's main balance
            $user->increment('balance', $validated['withdrawal_amount']);

            DB::commit();

            // Send email notification
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new GoalCompletedConfirmation($goal));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Your savings has been successfully withdrawn and added to your balance. The goal is now marked as completed.',
                'goal' => $goal,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Goal withdrawal failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your withdrawal. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        try {

            $user = Auth::user();

            // Block deletion if current amount is not zero
            if ($goal->current_amount != 0 && $goal->completed_at == null) {
                return redirect()->back()->with('warning', 'Please withdraw your current savings of $'
                    . number_format($goal->current_amount, 2) . ' before deleting this goal.');
            }

            $goal->delete();

            // Send notifications
            if (config('settings.email_notification')) {
                // Send confirmation to user
                Mail::mailer(config('settings.email_provider'))
                    ->to($user->email)
                    ->send(new GoalDeletedConfirmation($goal));
            }

            return redirect()->back()->with('success', 'Goal deleted successfully.');
        }catch (Exception $e) {
            Log::error('Failed to delete Goal: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Perform additional business logic validations
     */
    private function performAdditionalValidations(Request $request, User $user)
    {
        // Check if current amount is greater than target amount
        if ($request->current_amount && $request->target_amount) {
            if ($request->current_amount > $request->target_amount) {
                return 'Current amount cannot be greater than target amount.';
            }
        }

        // Check for duplicate goal titles for the same user
        $duplicateTitle = Goal::where('user_id', $user->id)
            ->where('title', trim($request->title))
            ->whereNull('completed_at')
            ->exists();

        if ($duplicateTitle) {
            return 'You already have a goal with this title. Please choose a different title.';
        }

        // If current_amount is set, ensure user has enough balance and debit
        if ($request->current_amount) {
            if ($user->balance < $request->current_amount) {
                return 'Insufficient balance to fund this goal.';
            }

            $debit = $user->decrement('balance', $request->current_amount);
            if (!$debit) {
                return 'Failed to deduct funds from your balance.';
            }
        }

        return true;
    }

    /**
     * Handle image upload and return the path
     */
    private function handleImageUpload($file)
    {
        try {

            $storagePath = 'goals/';

            // Store new image
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $fullPath = $storagePath . $filename;

            // Resize and save
            $resizedImage = Image::read($file)->resize(1024, 1024);
            Storage::disk('public')->put($fullPath, $resizedImage->encode());

            // Store in the goals directory within storage/app/public
            return Storage::disk('public')->url($fullPath);
        } catch (Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }
}
