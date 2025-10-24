<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Your Email - Waste2Product</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', Arial, sans-serif;
      line-height: 1.6;
      color: #333333;
      background-color: #f5f5f5;
    }
    
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .header {
      text-align: center;
      padding: 20px 0;
    }
    
    .logo {
      max-width: 150px;
      height: auto;
    }
    
    .card {
      background: #ffffff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    h1 {
      color: #2d3748;
      font-size: 24px;
      margin-top: 0;
      margin-bottom: 20px;
      text-align: center;
    }
    
    p {
      margin-bottom: 20px;
      font-size: 16px;
      color: #4a5568;
    }
    
    .button {
      display: inline-block;
      background-color: #4CAF50;
      color: #ffffff !important;
      text-decoration: none;
      padding: 12px 30px;
      border-radius: 5px;
      font-weight: 600;
      margin: 20px 0;
      text-align: center;
    }
    
    .footer {
      text-align: center;
      padding: 20px 0;
      font-size: 14px;
      color: #718096;
    }
    
    .verification-code {
      background: #f7fafc;
      border: 1px dashed #cbd5e0;
      padding: 15px;
      text-align: center;
      margin: 20px 0;
      border-radius: 5px;
      word-break: break-all;
    }
    
    @media only screen and (max-width: 600px) {
      .container {
        padding: 10px;
      }
      
      .card {
        padding: 20px;
      }
      
      h1 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{ asset('assets/back/img/recycle.png') }}" alt="Waste2Product" class="logo">
    </div>
    
    <div class="card">
      <h1>Verify Your Email Address</h1>
      
      <p>Hello <strong>{{ $user->name }}</strong>,</p>
      
      <p>Thank you for registering with Waste2Product! To complete your registration and start using your account, please verify your email address by clicking the button below:</p>
      
      <div style="text-align: center;">
        <a href="{{ $verifyUrl }}" class="button" style="background-color: #4CAF50;">Verify Email Address</a>
      </div>
      
      <p>If the button above doesn't work, please copy and paste the following link into your web browser:</p>
      
      <div class="verification-code">
        <a href="{{ $verifyUrl }}" style="color: #4a5568; text-decoration: none;">{{ $verifyUrl }}</a>
      </div>
      
      <p>This verification link will expire in 24 hours for security reasons.</p>
      
      <p>If you did not create an account, no further action is required.</p>
      
      <p>Best regards,<br>The Waste2Product Team</p>
    </div>
    
    <div class="footer">
      <p>© {{ date('Y') }} Waste2Product. All rights reserved.</p>
      <p>If you're having trouble with the button above, copy and paste the URL below into your web browser.</p>
      <p style="font-size: 12px; color: #a0aec0;">{{ $verifyUrl }}</p>
    </div>
  </div>
</body>
</html>
