<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\CardCreatedConfirmation;
use App\Models\Card;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CardController extends Controller
{
    /**
     * Store a newly created credit card in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_holder' => 'required|string|max:255',
            'card_number' => [
                'required',
                'string',
                'size:19',
                'regex:/^[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}$/',
            ],
            'expiry_month' => 'required|numeric|between:1,12',
            'expiry_year' => 'required|numeric|digits:4|after_or_equal:' . date('Y'),
            'cvv' => 'required|numeric|digits:3',
        ]);

        // Additional validation for expiration date
        $currentYear = date('Y');
        $currentMonth = date('m');

        if ($validated['expiry_year'] == $currentYear && $validated['expiry_month'] < $currentMonth) {
            return back()->withErrors(['expiry_month' => 'The card has already expired.']);
        }

        try {

            $expiryDate = str_pad($validated['expiry_month'], 2, '0', STR_PAD_LEFT) . '/' . substr($validated['expiry_year'], 2);

            // Create the card record
            $card = Card::create([
                'user_id' => auth()->id(),
                'card_balance' => auth()->user()->balance,
                'serial_key' => $this->generateSerialKey(),
                'card_number' => $this->maskCardNumber($validated['card_number']),
                'card_name' => $validated['card_holder'],
                'card_expiration' => $expiryDate,
                'card_security' => encrypt($validated['cvv']),
                'card_type' => $this->determineCardType($validated['card_number']),
                'card_status' => 'active',
            ]);

            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider'))->to(auth()->user()->email)->send(new CardCreatedConfirmation($card));
            }

            return redirect()->route('user.profile', ['tab' => 'cards'])
                ->with('success', 'Credit card added successfully!');

        } catch (Exception $exception) {
            Log::error('Failed to add card' . $exception->getMessage());
            return back()->withErrors(['error' => 'Failed to add card. Please try again.']);
        }
    }

    /**
     * Determine card type based on number
     */
    protected function determineCardType($cardNumber)
    {
        $cardNumber = str_replace(' ', '', $cardNumber);
        $firstDigit = substr($cardNumber, 0, 1);
        $firstTwoDigits = substr($cardNumber, 0, 2);
        $firstFourDigits = substr($cardNumber, 0, 4);

        return match(true) {
            // Visa (starts with 4)
            $firstDigit === '4' => 'Visa',

            // Mastercard (starts with 51-55 or 2221-2720)
            $firstDigit === '5' && $firstTwoDigits >= 51 && $firstTwoDigits <= 55 => 'Mastercard',
            $firstFourDigits >= 2221 && $firstFourDigits <= 2720 => 'Mastercard',

            // American Express (starts with 34 or 37)
            $firstTwoDigits === '34' || $firstTwoDigits === '37' => 'American Express',

            // Discover (starts with 6011, 644-649, 65)
            str_starts_with($cardNumber, '6011') => 'Discover',
            ($firstThreeDigits = substr($cardNumber, 0, 3)) >= 644 && $firstThreeDigits <= 649 => 'Discover',
            str_starts_with($cardNumber, '65') => 'Discover',

            // Other less common types
            $firstTwoDigits === '62' => 'China UnionPay',
            $firstFourDigits >= 2200 && $firstFourDigits <= 2204 => 'Mir',

            // Default fallback
            default => 'Unknown'
        };
    }

    /**
     * @return string
     */
    protected function generateSerialKey()
    {
        return Str::upper(Str::random(3)) . '-' .
            mt_rand(1000, 9999) . '-' .
            Str::upper(Str::random(3)) . '-' .
            mt_rand(1000, 9999);
    }

    /**
     * @param $cardNumber
     * @return string
     */
    protected function maskCardNumber($cardNumber)
    {
        $cleaned = preg_replace('/\s+/', '', $cardNumber);
        return substr($cleaned, 0, 4) . '******' . substr($cleaned, -4);
    }
}
