# Quick Start - SMS Notifications for Events

## What's Been Added?

✅ **SMS on Event Participation** - Users receive instant SMS when they join an event
✅ **SMS Reminders** - Automatic reminders sent 1 day before event (at 9:00 AM)
✅ **Tunisia Support** - Automatic +216 country code formatting
✅ **Test Number Ready** - 22688314

## Quick Setup (5 minutes)

### Step 1: Get Twilio Credentials (FREE Trial)

1. Go to https://www.twilio.com/try-twilio
2. Sign up (FREE - includes $15 credit)
3. Get your credentials from dashboard:
   - **Account SID** (starts with AC...)
   - **Auth Token**
   - **Phone Number** (get a trial number)

### Step 2: Add Credentials to .env

```env
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=+15555555555
```

### Step 3: Verify Test Phone Number (Trial Only)

For Twilio trial accounts, verify +21622688314 at:
https://console.twilio.com/us1/develop/phone-numbers/manage/verified

### Step 4: Test It!

#### Test Participation SMS:
1. Add phone number "22688314" to a user in database
2. Login and participate in an event
3. Check for SMS!

#### Test Reminder SMS:
```bash
# Create an event for tomorrow in your app first, then run:
php artisan events:send-reminders
```

You should see output like:
```
Starting to send event reminders...
Processing event: Beach Cleanup
  ✓ Sent reminder to John Doe (+21622688314)

=== Summary ===
Events processed: 1
SMS sent: 1
Failed: 0
```

## How It Works

### 1. Participation SMS (Automatic)

**When:** User clicks "Participate" button on any event
**What happens:**
```php
// In EventController@participate
$smsService->sendEventParticipationConfirmation($user, $event);
```

**SMS Content:**
```
Hello John Doe,

You have successfully registered for the event: Beach Cleanup
Date: 25/10/2025 10:00
We look forward to seeing you!

- Waste2Product Team
```

### 2. Reminder SMS (Scheduled Daily)

**When:** Every day at 9:00 AM automatically
**What happens:**
- Finds all events happening tomorrow
- Sends SMS to all registered participants
- Logs results

**To change the time:**
Edit `bootstrap/app.php`:
```php
$schedule->command('events:send-reminders')->dailyAt('14:00'); // 2:00 PM
```

## Running the Scheduler

### For Development:
```bash
# Terminal 1 - Keep this running
php artisan schedule:work
```

### For Production:
Add to crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Phone Number Format Examples

The system automatically formats phone numbers:

| Input          | Output           |
|----------------|------------------|
| 22688314       | +21622688314     |
| 21622688314    | +21622688314     |
| +21622688314   | +21622688314     |

## Testing Without Twilio

If you don't configure Twilio yet, the app will:
- Continue working normally
- Log SMS messages to `storage/logs/laravel.log`
- Show what would have been sent

View logs:
```bash
tail -f storage/logs/laravel.log
```

## Common Issues

### "SMS not sent - Twilio not configured"
→ Add Twilio credentials to `.env`

### "Failed to send SMS" (Trial Account)
→ Verify the phone number at Twilio Console

### Schedule not running
→ Run `php artisan schedule:work` in development

## Files Created

1. **`app/Services/SmsService.php`** - SMS logic
2. **`app/Console/Commands/SendEventReminders.php`** - Reminder command
3. **`SMS_SETUP.md`** - Detailed documentation
4. **`QUICK_START_SMS.md`** - This file

## Files Modified

1. **`app/Http/Controllers/EventController.php`** - Added SMS on participation (line 224-225)
2. **`config/services.php`** - Added Twilio config
3. **`bootstrap/app.php`** - Added scheduler
4. **`.env`** - Added Twilio placeholders
5. **`composer.json`** - Added Twilio SDK

## Need Help?

- Full documentation: See `SMS_SETUP.md`
- Twilio docs: https://www.twilio.com/docs/sms
- Check logs: `storage/logs/laravel.log`
- Test manually: `php artisan events:send-reminders`

## Cost

- Twilio Trial: **FREE** ($15 credit = ~330 SMS)
- Tunisia SMS: **~$0.045 per message**
- Perfect for testing and small/medium usage

---

**Ready to test?** Just add your Twilio credentials to `.env` and you're good to go! 🚀
