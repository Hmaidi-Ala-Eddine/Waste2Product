<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventParticipation;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS reminders to participants for events happening tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send event reminders...');

        // Get events happening tomorrow (24 hours from now)
        $tomorrow = Carbon::now()->addDay()->startOfDay();
        $dayAfterTomorrow = Carbon::now()->addDay()->endOfDay();

        $upcomingEvents = Event::whereBetween('date_time', [$tomorrow, $dayAfterTomorrow])
            ->with(['participations.user'])
            ->get();

        if ($upcomingEvents->isEmpty()) {
            $this->info('No events scheduled for tomorrow.');
            return 0;
        }

        $smsService = new SmsService();
        $totalSent = 0;
        $totalFailed = 0;

        foreach ($upcomingEvents as $event) {
            $this->info("Processing event: {$event->subject}");
            
            foreach ($event->participations as $participation) {
                $user = $participation->user;
                
                if (!$user) {
                    continue;
                }

                if (!$user->phone) {
                    $this->warn("  - Skipping user {$user->name} (ID: {$user->id}) - No phone number");
                    continue;
                }

                try {
                    $sent = $smsService->sendEventReminder($user, $event);
                    
                    if ($sent) {
                        $this->info("  ✓ Sent reminder to {$user->name} ({$user->phone})");
                        $totalSent++;
                    } else {
                        $this->warn("  ✗ Failed to send reminder to {$user->name}");
                        $totalFailed++;
                    }
                } catch (\Exception $e) {
                    $this->error("  ✗ Error sending to {$user->name}: {$e->getMessage()}");
                    $totalFailed++;
                }
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Events processed: " . $upcomingEvents->count());
        $this->info("SMS sent: {$totalSent}");
        $this->info("Failed: {$totalFailed}");

        return 0;
    }
}
