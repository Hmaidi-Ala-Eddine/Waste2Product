<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $twilio;
    protected $fromNumber;

    public function __construct()
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $this->fromNumber = config('services.twilio.from_number');

        if ($accountSid && $authToken && $this->fromNumber) {
            $this->twilio = new Client($accountSid, $authToken);
        }
    }

    /**
     * Send SMS message
     *
     * @param string $to Recipient phone number (with country code)
     * @param string $message Message content
     * @return bool Success status
     */
    public function sendSms($to, $message)
    {
        try {
            // If Twilio is not configured, log the message instead
            if (!$this->twilio) {
                Log::warning('SMS not sent - Twilio not configured', [
                    'to' => $to,
                    'message' => $message
                ]);
                return false;
            }

            // Format phone number for Tunisia (add +216 if not present)
            $formattedNumber = $this->formatPhoneNumber($to);

            // Send SMS via Twilio
            $this->twilio->messages->create(
                $formattedNumber,
                [
                    'from' => $this->fromNumber,
                    'body' => $message
                ]
            );

            Log::info('SMS sent successfully', [
                'to' => $formattedNumber,
                'message' => $message
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'to' => $to,
                'message' => $message,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format phone number for Tunisia
     * Adds +216 country code if not present
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove any spaces, dashes, or parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // If phone starts with +, return as is
        if (substr($phone, 0, 1) === '+') {
            return $phone;
        }

        // If phone starts with 216, add +
        if (substr($phone, 0, 3) === '216') {
            return '+' . $phone;
        }

        // Otherwise, add +216 (Tunisia country code)
        return '+216' . $phone;
    }

    /**
     * Send event participation confirmation SMS
     *
     * @param \App\Models\User $user
     * @param \App\Models\Event $event
     * @return bool
     */
    public function sendEventParticipationConfirmation($user, $event)
    {
        if (!$user->phone) {
            Log::warning('Cannot send SMS - User has no phone number', ['user_id' => $user->id]);
            return false;
        }

        $message = "Hello {$user->name},\n\n"
            . "You have successfully registered for the event: {$event->subject}\n"
            . "Date: {$event->formatted_date_time}\n"
            . "We look forward to seeing you!\n\n"
            . "- Waste2Product Team";

        return $this->sendSms($user->phone, $message);
    }

    /**
     * Send event reminder SMS (1 day before)
     *
     * @param \App\Models\User $user
     * @param \App\Models\Event $event
     * @return bool
     */
    public function sendEventReminder($user, $event)
    {
        if (!$user->phone) {
            Log::warning('Cannot send SMS - User has no phone number', ['user_id' => $user->id]);
            return false;
        }

        $message = "Hello {$user->name},\n\n"
            . "Reminder: Your event \"{$event->subject}\" is tomorrow!\n"
            . "Date: {$event->formatted_date_time}\n"
            . "Don't forget to attend!\n\n"
            . "- Waste2Product Team";

        return $this->sendSms($user->phone, $message);
    }
}
