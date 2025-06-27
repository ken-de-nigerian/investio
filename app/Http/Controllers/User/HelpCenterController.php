<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewContactMessageNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Log;

class HelpCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.helpcenter.index', [
            'title' => 'Account - Help Center'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
        ]);

        try {

            // Send notifications
            if (config('settings.email_notification')) {
                // Send notification to admins
                Notification::send(
                    User::where('role', 'admin')->get(),
                    new NewContactMessageNotification($validated)
                );
            }

            return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Sorry, there was an error sending your message. Please try again.');
        }
    }
}
