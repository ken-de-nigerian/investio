<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserEmailConfirmation;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminEmailNotificationsController extends Controller
{
    /**
     * Display email notifications index.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.email.notifications.index', [
            'title' => 'Account - Notifications',
        ]);
    }

    /**
     * Send email to all users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function broadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {

            // Get all none admin users with email addresses
            $users = User::whereNotNull('email')
                ->where('role', '!=', 'admin')
                ->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users with valid email addresses found.',
                ], 400);
            }

            // Send notifications
            if (config('settings.email_notification')) {
                // Queue emails for all users
                foreach ($users as $user) {
                    // Send confirmation to the user
                    Mail::mailer(config('settings.email_provider'))
                        ->to($user->email)
                        ->send(new UserEmailConfirmation($user, $request->subject, $request->message));
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Email sending failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to send email'], 500);
        }
    }
}
