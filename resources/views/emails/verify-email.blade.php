<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Verify your email</title>
</head>
<body>
  <p>Hello {{ $user->name }},</p>
  <p>Thanks for registering. Please click the link below to verify your email address and activate your account:</p>
  <p><a href="{{ $verifyUrl }}">Verify my email</a></p>
  <p>If you didn't create an account, you can ignore this email.</p>
  <p>Regards,<br/>Waste2Product</p>
</body>
</html>
