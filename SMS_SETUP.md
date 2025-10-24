# SMS Notification Setup Guide

This guide will help you set up SMS notifications for event participation and reminders using Twilio.

## Features Implemented

1. ✅ **Instant SMS** when a user participates in an event
2. ✅ **SMS Reminder** sent 1 day before the event (daily at 9:00 AM)
3. ✅ Automatic phone number formatting for Tunisia (+216)
4. ✅ Test number support: 22688314

## Setup Instructions

### 1. Create a Twilio Account

1. Go to [https://www.twilio.com/](https://www.twilio.com/)
2. Sign up for a free trial account
3. You'll receive **$15 in free credit** (enough for testing)

### 2. Get Your Twilio Credentials

After signing up:
1. Go to [Twilio Console](https://console.twilio.com/)
2. Find your **Account SID** and **Auth Token** on the dashboard
3. Get a phone number:
   - Go to **Phone Numbers** → **Manage** → **Buy a number**
   - Choose a number that can send SMS to Tunisia
   - Note: For testing, you can use the Twilio trial number

### 3. Configure Your .env File

Open your `.env` file and add your Twilio credentials:

```env
TWILIO_ACCOUNT_SID=your_account_sid_here
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=your_twilio_phone_number
```

Example:
```env
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=+15555555555
```

### 4. Add Test Phone Number (For Trial Account)

If you're using a Twilio trial account, you need to verify phone numbers before sending SMS:

1. Go to [Verified Caller IDs](https://console.twilio.com/us1/develop/phone-numbers/manage/verified)
2. Click **Add a new Caller ID**
3. Enter the test number: **+21622688314**
4. Verify it via SMS code

## Testing the Features

### Test 1: Participation SMS

1. Make sure a user has a phone number in the database
2. Log in to the application
3. Participate in an event
4. You should receive an SMS immediately with event details

### Test 2: Event Reminder SMS

#### Manual Test (Recommended for quick testing):
```bash
php artisan events:send-reminders
```

This will:
- Find all events happening tomorrow
- Send SMS reminders to all participants with phone numbers
- Display a summary of sent/failed messages

#### Automatic Test:
Create an event for tomorrow and wait for the scheduled task to run (daily at 9:00 AM).

## Schedule Configuration

The reminder SMS is scheduled to run every day at 9:00 AM. To change the time, edit `bootstrap/app.php`:

```php
->withSchedule(function (Schedule $schedule) {
    // Change '09:00' to your preferred time (24-hour format)
    $schedule->command('events:send-reminders')->dailyAt('09:00');
})
```

## Running the Scheduler

The Laravel scheduler needs to be running. You have two options:

### Option 1: Development (Manual)
Run the scheduler manually:
```bash
php artisan schedule:work
```

### Option 2: Production (Cron Job)
Add this cron entry to your server:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## SMS Message Templates

### Participation Confirmation
```
Hello [Name],

You have successfully registered for the event: [Event Subject]
Date: [Event Date/Time]
We look forward to seeing you!

- Waste2Product Team
```

### Event Reminder (1 Day Before)
```
Hello [Name],

Reminder: Your event "[Event Subject]" is tomorrow!
Date: [Event Date/Time]
Don't forget to attend!

- Waste2Product Team
```

## Phone Number Format

The SMS service automatically formats phone numbers for Tunisia:
- **22688314** → **+21622688314**
- **21622688314** → **+21622688314**
- **+21622688314** → **+21622688314** (already formatted)

## Troubleshooting

### SMS Not Sending?

1. **Check Twilio credentials**: Make sure `.env` has correct values
2. **Verify phone number**: For trial accounts, verify the recipient number in Twilio Console
3. **Check logs**: View Laravel logs at `storage/logs/laravel.log`
4. **Check balance**: Ensure your Twilio account has credit

### Testing Without Twilio

If Twilio is not configured, the system will:
- Log SMS messages to `storage/logs/laravel.log`
- Continue working without errors
- You can review what would have been sent in the logs

## Cost Estimation

Twilio SMS pricing for Tunisia:
- **~$0.045 per SMS** (check current rates at [Twilio Pricing](https://www.twilio.com/sms/pricing/tn))
- Trial account includes **$15 free credit** (~333 SMS messages)
- For production, add credit as needed

## Files Modified/Created

### Created:
- `app/Services/SmsService.php` - SMS sending logic
- `app/Console/Commands/SendEventReminders.php` - Reminder command
- `SMS_SETUP.md` - This documentation

### Modified:
- `app/Http/Controllers/EventController.php` - Added SMS on participation
- `config/services.php` - Added Twilio configuration
- `.env` - Added Twilio credentials placeholders
- `bootstrap/app.php` - Added task scheduler
- `composer.json` - Added Twilio SDK dependency

## Support

For Twilio support:
- [Twilio Documentation](https://www.twilio.com/docs)
- [Twilio Support](https://support.twilio.com)

For application issues:
- Check Laravel logs: `storage/logs/laravel.log`
- Run command manually to see errors: `php artisan events:send-reminders`
