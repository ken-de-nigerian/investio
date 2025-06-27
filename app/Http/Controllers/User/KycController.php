<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\KycSubmittedUser;
use App\Models\User;
use App\Notifications\NewKycNotification;
use App\Services\Geolocation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class KycController extends Controller
{
    /**
     * Display the appropriate KYC view based on verification status.
     * Returns a tailored title and message for each verification state.
     */
    public function index()
    {
        $user = Auth::user();
        $verification = User::with('kyc')->find($user->id);
        $status = $verification->kyc->status ?? 'unverified';

        $statusData = [
            'pending' => [
                'title' => 'Verification in Progress',
                'static_message' => 'To protect against fraudulent activity, all participants will be required to complete identity verification (KYC/AML).',
                'dynamic_message' => 'Your documents are under review. This typically takes 1-3 business days.',
                'icon' => 'bi-hourglass',
                'color' => 'btn-warning',
                'action' => [
                    'text' => 'Go to Dashboard',
                    'route' => route('user.dashboard')
                ]
            ],
            'verified' => [
                'title' => 'Verification Complete',
                'static_message' => 'Your account is now fully verified and you can access all platform features.',
                'dynamic_message' => 'Your identity has been successfully verified!',
                'icon' => 'bi-check-circle-fill',
                'color' => 'btn-success',
                'action' => [
                    'text' => 'Go to Dashboard',
                    'route' => route('user.dashboard')
                ]
            ],
            'rejected' => [
                'title' => 'Verification Rejected',
                'static_message' => 'We couldn\'t verify your identity with the provided documents.',
                'dynamic_message' => $verification->kyc->rejection_reason ?? 'Documents didn\'t meet requirements. Please try again.',
                'icon' => 'bi-x-circle-fill',
                'color' => 'btn-danger',
                'action' => [
                    'text' => 'Resubmit Documents',
                    'route' => route('user.kyc.create')
                ]
            ],
            'unverified' => [
                'title' => 'Account Verification Required',
                'static_message' => 'To protect against fraudulent activity, all participants will be required to complete identity verification (KYC/AML).',
                'dynamic_message' => 'To access all features, please complete your identity verification.',
                'icon' => 'bi-shield-exclamation',
                'color' => 'btn-primary',
                'action' => [
                    'text' => 'Start Verification',
                    'route' => route('user.kyc.create')
                ]
            ]
        ];

        $currentStatus = $statusData[$status] ?? $statusData['unverified'];

        return view('user.kyc.index', [
            'title' => $currentStatus['title'],
            'static_message' => $currentStatus['static_message'],
            'dynamic_message' => $currentStatus['dynamic_message'],
            'icon' => $currentStatus['icon'],
            'color' => $currentStatus['color'],
            'action' => $currentStatus['action'],
            'status' => $status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countriesResponse = Geolocation::handleLocationRequest('countries');
        $countries = $countriesResponse->getData(true)['data'] ?? [];

        return view('user.kyc.kyc-create', [
            'title' => 'Account - Verification Required',
            'countries' => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if campaign details exist in session
        if (!$request->session()->has('kyc.data')) {
            return response()->json([
                'success' => false,
                'message' => 'Please upload the required files first and try again'
            ]);
        }

        // Validate all fields
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'id_proof_type' => 'required|in:passport,national_id,driving_license',
            'address_proof_type' => 'required|in:passport,electricity_bill,gas_bill',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);

        try {

            // Get kyc file uploads from the session
            $files = $request->session()->get('kyc.data');

            // Ensure directories exist
            Storage::disk('public')->makeDirectory('kyc/id_proof');
            Storage::disk('public')->makeDirectory('kyc/address_proof');

            $fileUrls = [];
            foreach ($files as $type => $fileData) {
                // Convert URL to storage-relative path
                $urlPath = parse_url($fileData['file'], PHP_URL_PATH);
                $tempPath = str_replace('/storage/', '', $urlPath);

                // Generate new filename
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                $newFilename = Str::uuid() . '.' . $extension;

                // Target directory
                $targetDir = str_contains($type, 'id_') ? 'kyc/id_proof' : 'kyc/address_proof';
                $newPath = $targetDir . '/' . $newFilename;

                // Move the file
                Storage::disk('public')->move($tempPath, $newPath);

                // Store URL
                $fileUrls[$type] = Storage::disk('public')->url($newPath);
            }

            // Process and store kyc data
            $user = auth()->user();
            $kycData = $user->kyc()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone_number' => $validated['phone_number'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'country' => $validated['country'],
                    'state' => $validated['state'],
                    'city' => $validated['city'],
                    'address' => $validated['address'],
                    'id_proof_type' => $validated['id_proof_type'],
                    'address_proof_type' => $validated['address_proof_type'],
                    'id_front_proof_url' => $fileUrls['id_front_proof'],
                    'id_back_proof_url' => $fileUrls['id_back_proof'] ?? null,
                    'address_front_proof_url' => $fileUrls['address_front_proof'],
                    'address_back_proof_url' => $fileUrls['address_back_proof'] ?? null,
                    'status' => 'pending',
                ]
            );

            // Clear the session data
            $request->session()->forget('kyc.data');

            // Send email notifications
            if (config('settings.email_notification')){
                Mail::mailer(config('settings.email_provider'))->to($user->email)->send(new KycSubmittedUser($kycData));
                Notification::send(User::where('role', 'admin')->get(), new NewKycNotification($kycData));
            }

            return response()->json([
                'success' => true,
                'message' => 'KYC submitted successfully. You will receive a confirmation email shortly.',
                'redirect' => route('user.kyc')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // Clean up any partially moved files if an error occurs
            if (isset($fileUrls)) {
                foreach ($fileUrls as $url) {
                    $path = str_replace(url('storage'), '', $url);
                    $path = ltrim($path, '/');
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Error submitting KYC: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function processImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'upload_type' => 'required|in:id_front_proof,id_back_proof,address_front_proof,address_back_proof',
        ]);

        // Get existing KYC data from session if it exists
        $kycData = $request->session()->has('kyc.data')
            ? $request->session()->get('kyc.data')
            : [];

        // Handle file upload
        if ($request->hasFile('file')) {
            $storedPath = $request->file('file')->store('temp/kyc-data', 'public');
            $fileUrl = Storage::disk('public')->url($storedPath);

            // Update or add the new file for the specific upload type
            $kycData[$request->upload_type] = [
                'file' => $fileUrl,
                'upload_type' => $request->upload_type,
            ];
        }

        // Store updated media data in session
        $request->session()->put('kyc.data', $kycData);

        return response()->json([
            'success' => true,
            'file' => $fileUrl ?? null,
            'upload_type' => $request->upload_type,
            'kyc_data' => $kycData
        ]);
    }
}
