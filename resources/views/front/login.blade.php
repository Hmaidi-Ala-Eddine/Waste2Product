<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Waste2Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #4EA685;
            --secondary-color: #57B894;
            --black: #000000;
            --white: #ffffff;
            --gray: #efefef;
            --gray-2: #757575;
            --facebook-color: #4267B2;
            --google-color: #DB4437;
            --twitter-color: #1DA1F2;
            --insta-color: #E1306C;
        }
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');
        * { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100vh; overflow: hidden; }
        .container { position: relative; min-height: 100vh; overflow: hidden; }
        .row { display: flex; flex-wrap: wrap; height: 100vh; }
        .col { width: 50%; }
        .align-items-center { display: flex; align-items: center; justify-content: center; text-align: center; }
        .form-wrapper { width: 100%; max-width: 28rem; }
        .form { padding: 1rem; background-color: var(--white); border-radius: 1.5rem; width: 100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px; transform: scale(0); transition: .5s ease-in-out; transition-delay: 1s; }
        .input-group { position: relative; width: 100%; margin: 1rem 0; }
        .input-group i { position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); font-size: 1.4rem; color: var(--gray-2); }
        .input-group input { width: 100%; padding: 1rem 3rem; font-size: 1rem; background-color: var(--gray); border-radius: .5rem; border: 0.125rem solid var(--white); outline: none; }
        .input-group input:focus { border: 0.125rem solid var(--primary-color); }
        .input-group input.is-invalid { border: 0.125rem solid #e74c3c; background-color: #fff5f5; }
        .input-group input.is-valid { border: 0.125rem solid #27ae60; background-color: #f0fdf4; }
        .error-message { color: #e74c3c; font-size: 0.75rem; margin-top: 0.25rem; display: none; font-weight: 500; text-align: left; padding-left: 1rem; }
        .error-message.show { display: block; }
        .form button { cursor: pointer; width: 100%; padding: .6rem 0; border-radius: .5rem; border: none; background-color: var(--primary-color); color: var(--white); font-size: 1.2rem; outline: none; }
        .form p { margin: 1rem 0; font-size: .7rem; }
        .flex-col { flex-direction: column; }
        .pointer { cursor: pointer; }
        .container.sign-in .form.sign-in,
        .container.sign-in .social-list.sign-in,
        .container.sign-in .social-list.sign-in>div,
        .container.sign-up .form.sign-up,
        .container.sign-up .social-list.sign-up,
        .container.sign-up .social-list.sign-up>div { transform: scale(1); }
        .content-row { position: absolute; top: 0; left: 0; pointer-events: none; z-index: 6; width: 100%; }
        .text { margin: 4rem; color: var(--white); }
        .text h2 { font-size: 3.5rem; font-weight: 800; margin: 2rem 0; transition: 1s ease-in-out; }
        .text p { font-weight: 600; transition: 1s ease-in-out; transition-delay: .2s; }
        .img img { width: 30vw; transition: 1s ease-in-out; transition-delay: .4s; }
        .text.sign-in h2, .text.sign-in p, .img.sign-in img { transform: translateX(-250%); }
        .text.sign-up h2, .text.sign-up p, .img.sign-up img { transform: translateX(250%); }
        .container.sign-in .text.sign-in h2,
        .container.sign-in .text.sign-in p,
        .container.sign-in .img.sign-in img,
        .container.sign-up .text.sign-up h2,
        .container.sign-up .text.sign-up p,
        .container.sign-up .img.sign-up img { transform: translateX(0); }
        .container::before { content: ""; position: absolute; top: 0; right: 0; height: 100vh; width: 300vw; transform: translate(35%, 0); background-image: linear-gradient(-45deg, var(--primary-color) 0%, var(--secondary-color) 100%); transition: 1s ease-in-out; z-index: 6; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px; border-bottom-right-radius: max(50vw, 50vh); border-top-left-radius: max(50vw, 50vh); }
        .container.sign-in::before { transform: translate(0, 0); right: 50%; }
        .container.sign-up::before { transform: translate(100%, 0); right: 50%; }
        @media only screen and (max-width: 425px) {
            .container::before,
            .container.sign-in::before,
            .container.sign-up::before { height: 100vh; border-bottom-right-radius: 0; border-top-left-radius: 0; z-index: 0; transform: none; right: 0; }
            .container.sign-in .col.sign-in,
            .container.sign-up .col.sign-up { transform: translateY(0); }
            .content-row { align-items: flex-start !important; }
            .content-row .col { transform: translateY(0); background-color: unset; }
            .col { width: 100%; position: absolute; padding: 2rem; background-color: var(--white); border-top-left-radius: 2rem; border-top-right-radius: 2rem; transform: translateY(100%); transition: 1s ease-in-out; }
            .row { align-items: flex-end; justify-content: flex-end; }
            .form, .social-list { box-shadow: none; margin: 0; padding: 0; }
            .text { margin: 0; }
            .text p { display: none; }
            .text h2 { margin: .5rem; font-size: 2rem; }
        }
        .signup-pic-btn {
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }
        .signup-pic-btn:hover {
            background-color: #344767 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 71, 103, 0.3);
        }
        .signup-pic-btn * {
            user-select: none !important;
            cursor: pointer !important;
        }
        /* Face ID Modal Styles */
        .faceid-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }
        .faceid-modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .faceid-modal video {
            width: 100%;
            max-width: 480px;
            border-radius: 10px;
            margin: 20px 0;
            border: 3px solid var(--primary-color);
        }
        .faceid-modal canvas {
            display: none;
        }
        .faceid-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            margin: 10px;
            transition: 0.3s;
        }
        .faceid-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        .faceid-btn.secondary {
            background: #6c757d;
        }
        .faceid-btn.secondary:hover {
            background: #5a6268;
        }
        .face-login-btn {
            margin-top: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            transition: 0.3s;
        }
        .face-login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 166, 133, 0.4);
        }
    </style>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div id="container" class="container {{ session('show') === 'signup' ? 'sign-up' : (session('show') === 'signin' ? 'sign-in' : '') }}">
        <!-- FORM SECTION -->
        <div class="row">
            <!-- SIGN UP -->
            <div class="col align-items-center flex-col sign-up">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-up">
                        @if(session('success'))
                            <div style="color:green;margin-bottom:8px">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div style="color:red;margin-bottom:8px">
                                <ul style="margin:0;padding-left:18px;text-align:left;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="signupForm" method="POST" action="{{ route('front.signup.post') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="from" value="front">
                            <input type="hidden" name="g-recaptcha-response" id="signup-g-recaptcha-response">
                            <div class="input-group">
                                <i class='bx bxs-user'></i>
                                <input name="name" type="text" placeholder="Full name" required value="{{ old('name') }}" id="signup-name">
                                <div class="error-message" id="signup-name-error"></div>
                            </div>
                            <div class="input-group">
                                <i class='bx bx-mail-send'></i>
                                <input name="email" type="email" placeholder="Email" required value="{{ old('email') }}" id="signup-email">
                                <div class="error-message" id="signup-email-error"></div>
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input name="password" type="password" placeholder="Password" required id="signup-password">
                                <div class="error-message" id="signup-password-error"></div>
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input name="password_confirmation" type="password" placeholder="Confirm password" required id="signup-password-confirm">
                                <div class="error-message" id="signup-password-confirm-error"></div>
                            </div>
                            <div style="width: 100%; display: flex; flex-direction: column; align-items: center; padding: 20px 0; margin: 1rem 0;">
                                <label style="font-size: 0.875rem; color: #344767; margin-bottom: 15px; font-weight: 500; text-align: center;">Profile Picture</label>
                                <div id="signup-image-preview" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid #e0e0e0; overflow: hidden; margin: 0 auto 15px; display: block; background: #f8f9fa; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    <img id="signup-preview-img" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <div id="signup-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ccc;">
                                        <i class='bx bx-user' style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                                <label for="signup-profile-picture" class="signup-pic-btn" style="cursor: pointer; background: white; color: #344767; padding: 8px 20px; border: 1.5px solid #344767; border-radius: 6px; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; font-weight: 500; user-select: none;">
                                    <i class='bx bx-image-add' style="font-size: 1rem; pointer-events: none;"></i>
                                    <span style="pointer-events: none;">Change Picture</span>
                                </label>
                                <input name="profile_picture" type="file" accept="image/*" id="signup-profile-picture" style="display: none;" onchange="previewSignupImage(this)">
                                <small style="color: #888; font-size: 0.75rem; margin-top: 10px; display: block; text-align: center;">Optional - Max 2MB (JPG, PNG, GIF)</small>
                            </div>
                            <button type="button" onclick="showSignupRecaptcha()">Sign up</button>
                        </form>

                        <p>
                            <span>
                                Already have an account?
                            </span>
                            <b onclick="toggle()" class="pointer">
                                Sign in here
                            </b>
                        </p>
                    </div>
                </div>
            </div>
            <!-- END SIGN UP -->
            <!-- SIGN IN -->
            <div class="col align-items-center flex-col sign-in">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-in">
                        @if(session('success'))
                            <div style="color:green;margin-bottom:8px">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div style="color:red;margin-bottom:8px">
                                <ul style="margin:0;padding-left:18px;text-align:left;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="signinForm" method="POST" action="{{ route('front.sign-in.post') }}">
                            @csrf
                            <input type="hidden" name="from" value="front">
                            <input type="hidden" name="g-recaptcha-response" id="signin-g-recaptcha-response">
                            <div class="input-group">
                                <i class='bx bxs-user'></i>
                                <input name="email" type="email" id="login-email" placeholder="Email" required value="{{ old('email') }}">
                                <div class="error-message" id="login-email-error"></div>
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input name="password" type="password" id="login-password" placeholder="Password" required>
                                <div class="error-message" id="login-password-error"></div>
                            </div>
                            <button type="button" onclick="showSigninRecaptcha()">Sign in</button>
                            <button type="button" class="face-login-btn" onclick="openFaceLoginModal()">
                                <i class='bx bx-face-mask'></i>
                                Login with Face ID
                            </button>
                            <p>
                                <b>
                                    Forgot password?
                                </b>
                            </p>
                            <p>
                                <span>
                                    Don't have an account?
                                </span>
                                <b onclick="toggle()" class="pointer">
                                    Sign up here
                                </b>
                            </p>
                        </form>
                    </div>
                </div>
                <div class="form-wrapper">
                </div>
            </div>
            <!-- END SIGN IN -->
        </div>
        <!-- END FORM SECTION -->
        <!-- CONTENT SECTION -->
        <div class="row content-row">
            <!-- SIGN IN CONTENT -->
            <div class="col align-items-center flex-col">
                <div class="text sign-in">
                    <h2>
                        Welcome
                    </h2>
                </div>
                <div class="img sign-in">
                </div>
            </div>
            <!-- END SIGN IN CONTENT -->
            <!-- SIGN UP CONTENT -->
            <div class="col align-items-center flex-col">
                <div class="img sign-up">
                </div>
                <div class="text sign-up">
                    <h2>
                        Join with us
                    </h2>
                </div>
            </div>
            <!-- END SIGN UP CONTENT -->
        </div>
        <!-- END CONTENT SECTION -->
    </div>

    <!-- Face ID Login Modal -->
    <div id="faceLoginModal" class="faceid-modal">
        <div class="faceid-modal-content">
            <h2 style="color: var(--primary-color); margin-bottom: 10px;">
                <i class='bx bx-face-mask' style="font-size: 2rem;"></i>
                Face ID Login
            </h2>
            <p style="color: #666; margin-bottom: 20px;">Position your face in the camera</p>
            <video id="faceLoginVideo" autoplay playsinline></video>
            <canvas id="faceLoginCanvas"></canvas>
            <div id="faceLoginStatus" style="margin: 15px 0; color: #666; min-height: 24px;"></div>
            <div>
                <button class="faceid-btn" onclick="captureFaceLogin()">Capture & Login</button>
                <button class="faceid-btn secondary" onclick="closeFaceLoginModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- reCAPTCHA Modal -->
    <div id="recaptchaModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:white; padding:30px; border-radius:10px; text-align:center; min-width:400px;">
            <h3 style="margin-bottom:20px; color:#333;">Security Verification</h3>
            <p style="margin-bottom:20px; color:#666;">Please complete the security check to continue:</p>
            <div id="recaptcha-container" style="display:flex; justify-content:center; margin:20px 0;"></div>
            <div style="margin-top:20px;">
                <button onclick="closeRecaptchaModal()" style="background:#6c757d; color:white; border:none; padding:10px 20px; border-radius:5px; margin-right:10px; cursor:pointer;">Cancel</button>
                <button id="verifyBtn" onclick="submitAfterRecaptcha()" style="background:#4EA685; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">Verify & Continue</button>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaApiLoad&render=explicit" async defer></script>
    <script>
        let container = document.getElementById('container');
        function toggle() {
            container.classList.toggle('sign-in');
            container.classList.toggle('sign-up');
        }
        setTimeout(() => {
            container.classList.add('sign-in');
        }, 200);

        // forms submit to server; no client-side stub needed
        // reCAPTCHA explicit rendering
        let signupWidgetId = null;
        let signinWidgetId = null;

        let currentWidgetId = null;
        let currentForm = null;
        let recaptchaRendered = false;

        function onRecaptchaApiLoad(){
            // reCAPTCHA API is loaded, but we'll render it when modal opens
        }

        // Validation helper functions
        function clearValidation(input) {
            input.classList.remove('is-valid', 'is-invalid');
            const errorEl = document.getElementById(input.id + '-error');
            if (errorEl) {
                errorEl.classList.remove('show');
                errorEl.textContent = '';
            }
        }
        
        function setInvalid(input, message) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            const errorEl = document.getElementById(input.id + '-error');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.classList.add('show');
            }
            return false;
        }
        
        function setValid(input) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            const errorEl = document.getElementById(input.id + '-error');
            if (errorEl) {
                errorEl.classList.remove('show');
                errorEl.textContent = '';
            }
            return true;
        }
        
        function validateName(input) {
            const value = input.value.trim();
            clearValidation(input);
            
            if (!value) return setInvalid(input, 'Please enter your full name');
            if (value.length < 2) return setInvalid(input, 'Name must be at least 2 characters long');
            if (value.length > 255) return setInvalid(input, 'Name cannot exceed 255 characters');
            if (!/^[a-zA-Z\s\-\'\.]+(?: [a-zA-Z\s\-\'\.])*$/.test(value)) {
                return setInvalid(input, 'Name can only contain letters, spaces, hyphens, apostrophes, and periods');
            }
            return setValid(input);
        }
        
        function validateEmail(input) {
            const value = input.value.trim();
            clearValidation(input);
            
            if (!value) return setInvalid(input, 'Please enter your email address');
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return setInvalid(input, 'Please enter a valid email address');
            if (value.length > 255) return setInvalid(input, 'Email cannot exceed 255 characters');
            return setValid(input);
        }
        
        function validatePassword(input) {
            const value = input.value;
            clearValidation(input);
            
            if (!value) return setInvalid(input, 'Please enter a password');
            if (value.length < 8) return setInvalid(input, 'Password must be at least 8 characters long');
            if (value.length > 255) return setInvalid(input, 'Password cannot exceed 255 characters');
            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/.test(value)) {
                return setInvalid(input, 'Password must contain at least one uppercase letter, one lowercase letter, and one number');
            }
            return setValid(input);
        }
        
        function validatePasswordConfirm(input, passwordInput) {
            const value = input.value;
            const password = passwordInput.value;
            clearValidation(input);
            
            if (!value) return setInvalid(input, 'Please confirm your password');
            if (value !== password) return setInvalid(input, 'Passwords do not match');
            return setValid(input);
        }
        
        // Add real-time validation listeners
        document.addEventListener('DOMContentLoaded', function() {
            const signupName = document.getElementById('signup-name');
            const signupEmail = document.getElementById('signup-email');
            const signupPassword = document.getElementById('signup-password');
            const signupPasswordConfirm = document.getElementById('signup-password-confirm');
            const loginEmail = document.getElementById('login-email');
            const loginPassword = document.getElementById('login-password');
            
            if (signupName) {
                signupName.addEventListener('blur', () => validateName(signupName));
                signupName.addEventListener('input', () => {
                    if (signupName.classList.contains('is-invalid')) validateName(signupName);
                });
            }
            
            if (signupEmail) {
                signupEmail.addEventListener('blur', () => validateEmail(signupEmail));
                signupEmail.addEventListener('input', () => {
                    if (signupEmail.classList.contains('is-invalid')) validateEmail(signupEmail);
                });
            }
            
            if (signupPassword) {
                signupPassword.addEventListener('blur', () => validatePassword(signupPassword));
                signupPassword.addEventListener('input', () => {
                    if (signupPassword.classList.contains('is-invalid')) validatePassword(signupPassword);
                    if (signupPasswordConfirm && signupPasswordConfirm.value) {
                        validatePasswordConfirm(signupPasswordConfirm, signupPassword);
                    }
                });
            }
            
            if (signupPasswordConfirm) {
                signupPasswordConfirm.addEventListener('blur', () => validatePasswordConfirm(signupPasswordConfirm, signupPassword));
                signupPasswordConfirm.addEventListener('input', () => {
                    if (signupPasswordConfirm.classList.contains('is-invalid')) {
                        validatePasswordConfirm(signupPasswordConfirm, signupPassword);
                    }
                });
            }
            
            if (loginEmail) {
                loginEmail.addEventListener('blur', () => validateEmail(loginEmail));
                loginEmail.addEventListener('input', () => {
                    if (loginEmail.classList.contains('is-invalid')) validateEmail(loginEmail);
                });
            }
            
            if (loginPassword) {
                loginPassword.addEventListener('blur', () => {
                    const value = loginPassword.value;
                    clearValidation(loginPassword);
                    if (!value) return setInvalid(loginPassword, 'Please enter your password');
                    if (value.length < 8) return setInvalid(loginPassword, 'Password must be at least 8 characters long');
                    return setValid(loginPassword);
                });
                loginPassword.addEventListener('input', () => {
                    if (loginPassword.classList.contains('is-invalid')) {
                        const value = loginPassword.value;
                        clearValidation(loginPassword);
                        if (!value) return setInvalid(loginPassword, 'Please enter your password');
                        if (value.length < 8) return setInvalid(loginPassword, 'Password must be at least 8 characters long');
                        return setValid(loginPassword);
                    }
                });
            }
        });

        function showSignupRecaptcha(){
            const form = document.getElementById('signupForm');
            const nameInput = document.getElementById('signup-name');
            const emailInput = document.getElementById('signup-email');
            const passwordInput = document.getElementById('signup-password');
            const passwordConfirmInput = document.getElementById('signup-password-confirm');
            
            let isValid = true;
            
            if (!validateName(nameInput)) isValid = false;
            if (!validateEmail(emailInput)) isValid = false;
            if (!validatePassword(passwordInput)) isValid = false;
            if (!validatePasswordConfirm(passwordConfirmInput, passwordInput)) isValid = false;
            
            if (!isValid) {
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) firstInvalid.focus();
                return;
            }
            
            currentForm = form;
            showRecaptchaModal();
        }

        function showSigninRecaptcha(){
            const form = document.getElementById('signinForm');
            const emailInput = document.getElementById('login-email');
            const passwordInput = document.getElementById('login-password');
            
            let isValid = true;
            
            if (!validateEmail(emailInput)) isValid = false;
            
            const passwordValue = passwordInput.value;
            clearValidation(passwordInput);
            if (!passwordValue) {
                setInvalid(passwordInput, 'Please enter your password');
                isValid = false;
            } else if (passwordValue.length < 8) {
                setInvalid(passwordInput, 'Password must be at least 8 characters long');
                isValid = false;
            } else {
                setValid(passwordInput);
            }
            
            if (!isValid) {
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) firstInvalid.focus();
                return;
            }
            
            currentForm = form;
            showRecaptchaModal();
        }

        function showRecaptchaModal(){
            document.getElementById('recaptchaModal').style.display = 'block';
            
            // Render reCAPTCHA when modal opens (only once)
            if (!recaptchaRendered && document.getElementById('recaptcha-container')) {
                currentWidgetId = grecaptcha.render('recaptcha-container', {
                    sitekey: '{{ config('services.recaptcha.site_key') ?? env('RECAPTCHA_SITE_KEY') }}',
                    size: 'normal',
                    theme: 'light'
                });
                recaptchaRendered = true;
            } else if (currentWidgetId !== null) {
                // Reset reCAPTCHA for reuse
                grecaptcha.reset(currentWidgetId);
            }
        }

        function closeRecaptchaModal(){
            document.getElementById('recaptchaModal').style.display = 'none';
            currentForm = null;
        }

        function submitAfterRecaptcha(){
            if (currentWidgetId === null) {
                alert('reCAPTCHA not loaded. Please try again.');
                return;
            }
            
            const response = grecaptcha.getResponse(currentWidgetId);
            if (!response) {
                alert('Please complete the reCAPTCHA verification.');
                return;
            }
            
            // Add reCAPTCHA response to the correct hidden form field
            if (currentForm) {
                let hiddenField;
                if (currentForm.id === 'signupForm') {
                    hiddenField = document.getElementById('signup-g-recaptcha-response');
                } else if (currentForm.id === 'signinForm') {
                    hiddenField = document.getElementById('signin-g-recaptcha-response');
                }
                
                if (hiddenField) {
                    hiddenField.value = response;
                }
                
                // Close modal first, then submit
                document.getElementById('recaptchaModal').style.display = 'none';
                
                // Submit the form (keep reference before clearing)
                const formToSubmit = currentForm;
                currentForm = null;
                formToSubmit.submit();
            }
        }

        // Face ID Registration - Send to Python backend after successful signup
        async function registerFaceID(email, imageFile) {
            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('image', imageFile);

                const response = await fetch('http://127.0.0.1:5000/register', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                console.log('Face ID Registration:', result);
                return response.ok;
            } catch (error) {
                console.error('Face ID registration error:', error);
                return false;
            }
        }

        // Override signup form submission to include Face ID registration
        const originalSubmitAfterRecaptcha = submitAfterRecaptcha;
        submitAfterRecaptcha = function() {
            const isSignupForm = currentForm && currentForm.id === 'signupForm';
            
            if (isSignupForm) {
                const email = document.getElementById('signup-email').value;
                const profilePicInput = document.getElementById('signup-profile-picture');
                const profilePicFile = profilePicInput.files[0];

                if (profilePicFile && email) {
                    // Register face in background (don't block form submission)
                    registerFaceID(email, profilePicFile).then(success => {
                        if (success) {
                            console.log('✅ Face ID registered successfully');
                        } else {
                            console.warn('⚠️ Face ID registration failed, but account created');
                        }
                    });
                }
            }
            
            // Call original submit function
            originalSubmitAfterRecaptcha();
        };

        // Face ID Login Modal
        let faceLoginStream = null;

        function openFaceLoginModal() {
            const modal = document.getElementById('faceLoginModal');
            const video = document.getElementById('faceLoginVideo');
            const status = document.getElementById('faceLoginStatus');
            
            modal.style.display = 'flex';
            status.textContent = 'Starting camera...';

            navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
                .then(stream => {
                    faceLoginStream = stream;
                    video.srcObject = stream;
                    status.textContent = 'Camera ready. Position your face and click Capture.';
                })
                .catch(error => {
                    console.error('Camera error:', error);
                    status.textContent = '❌ Camera access denied. Please enable camera permissions.';
                    status.style.color = '#e74c3c';
                });
        }

        function closeFaceLoginModal() {
            const modal = document.getElementById('faceLoginModal');
            const video = document.getElementById('faceLoginVideo');
            const status = document.getElementById('faceLoginStatus');
            
            // Stop camera
            if (faceLoginStream) {
                faceLoginStream.getTracks().forEach(track => track.stop());
                faceLoginStream = null;
            }
            video.srcObject = null;
            
            modal.style.display = 'none';
            status.textContent = '';
            status.style.color = '#666';
        }

        async function captureFaceLogin() {
            const video = document.getElementById('faceLoginVideo');
            const canvas = document.getElementById('faceLoginCanvas');
            const status = document.getElementById('faceLoginStatus');
            const ctx = canvas.getContext('2d');

            // Set canvas size to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame to canvas
            ctx.drawImage(video, 0, 0);

            // Convert canvas to blob
            canvas.toBlob(async (blob) => {
                status.textContent = '🔍 Verifying your face...';
                status.style.color = '#4EA685';

                try {
                    const formData = new FormData();
                    formData.append('image', blob, 'face.jpg');

                    const response = await fetch('http://127.0.0.1:5000/login', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.message) {
                        // Extract email from message like "Welcome mehdikalfat1gmail.com.jpg!"
                        const email = extractEmailFromMessage(result.message);
                        
                        if (email) {
                            status.textContent = '✅ Face verified! Logging in...';
                            status.style.color = '#27ae60';

                            // Directly login with Face ID (no password required)
                            loginWithFaceID(email);
                        } else {
                            status.textContent = '❌ Could not extract email from response';
                            status.style.color = '#e74c3c';
                        }
                    } else {
                        status.textContent = '❌ ' + (result.message || result.error || 'Face not recognized');
                        status.style.color = '#e74c3c';
                    }
                } catch (error) {
                    console.error('Face login error:', error);
                    status.textContent = '❌ Connection error. Make sure Python backend is running on port 5000.';
                    status.style.color = '#e74c3c';
                }
            }, 'image/jpeg', 0.95);
        }

        function extractEmailFromMessage(message) {
            // Message format: "Welcome mehdikalfat1gmail.com.jpg!"
            // Extract: mehdikalfat1@gmail.com
            
            try {
                // Remove "Welcome " and "!"
                let extracted = message.replace(/Welcome\s+/i, '').replace(/!/g, '').trim();
                
                // Remove .jpg extension
                extracted = extracted.replace(/\.jpg$/i, '');
                
                // Common email patterns to look for
                const emailPatterns = [
                    // For emails with dots in username and known domains (e.g., kalfat.mehdiesprit.tn -> kalfat.mehdi@esprit.tn)
                    { pattern: /^([a-z0-9_.-]+?)(es[pb]rit|gmail|yahoo|hotmail|outlook|live|icloud|protonmail)(\.[a-z]{2,})?(\.(tn|com|net|org|fr))?$/i, 
                      replace: (match, user, domain, subdomain, tld) => {
                          // If there's a subdomain, include it in the domain part
                          const domainPart = subdomain ? domain + subdomain : domain;
                          return `${user}@${domainPart}${tld || ''}`;
                      }
                    },
                    // For general domain patterns (e.g., example.com -> example@com)
                    { pattern: /^([a-z0-9_.-]+?)(\.[a-z]{2,}){1,2}$/i, 
                      replace: (match, user, domain) => {
                          // Split the domain parts
                          const parts = domain.split('.').filter(Boolean);
                          // If we have multiple parts (e.g., .esprit.tn), join them with a dot
                          if (parts.length > 1) {
                              return `${user}@${parts.join('')}`;
                          }
                          // Otherwise, just add @ before the domain
                          return `${user}@${parts[0]}`;
                      }
                    },
                    // Fallback for other cases
                    { pattern: /([a-z0-9_.-]+?)(gmail|yahoo|hotmail|outlook|live|icloud|protonmail)(\.[a-z]{2,})?/i, 
                      replace: '$1@$2$3' 
                    }
                ];

                // Try to fix the email by applying patterns
                for (const { pattern, replace } of emailPatterns) {
                    if (pattern.test(extracted)) {
                        if (typeof replace === 'function') {
                            extracted = extracted.replace(pattern, (...args) => replace(...args));
                        } else {
                            extracted = extracted.replace(pattern, replace);
                        }
                        break;
                    }
                }

                // If still no @ and contains common email providers, try to add @
                if (!extracted.includes('@')) {
                    const emailProviders = ['gmail', 'yahoo', 'hotmail', 'outlook', 'live', 'icloud', 'protonmail'];
                    for (const provider of emailProviders) {
                        if (extracted.includes(provider)) {
                            // Find where provider starts and add @ before it
                            const regex = new RegExp(`(\\w*)${provider}`, 'i');
                            extracted = extracted.replace(regex, `$1@${provider}`);
                            break;
                        }
                    }
                }
                
                console.log('Extracted email:', extracted);
                return extracted;
            } catch (error) {
                console.error('Email extraction error:', error);
                return null;
            }
        }

        // Format email by ensuring it has @ before common domains
        function formatEmail(email) {
            if (!email) return email;
            
            // If already has @, return as is
            if (email.includes('@')) return email;
            
            // Common email domains to check
            const domains = [
                'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 
                'live.com', 'icloud.com', 'protonmail.com', 'gmail', 'yahoo', 
                'hotmail', 'outlook', 'live', 'icloud', 'protonmail'
            ];
            
            // Try to find and fix the domain
            for (const domain of domains) {
                const domainIndex = email.toLowerCase().indexOf(domain.toLowerCase());
                if (domainIndex > 0) {
                    // If we find a domain, insert @ before it
                    return email.slice(0, domainIndex) + '@' + email.slice(domainIndex);
                }
            }
            
            // If no domain found but has dot com/net/org, add @ before it
            const tldMatch = email.match(/(\.(com|net|org|io|co\.uk))$/i);
            if (tldMatch) {
                return email.replace(tldMatch[1], '@' + tldMatch[1]);
            }
            
            return email; // Return as is if we can't determine
        }

        // Direct login with Face ID (no password required)
        async function loginWithFaceID(email) {
            try {
                // Format the email before using it
                const formattedEmail = formatEmail(email);
                
                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(formattedEmail)) {
                    throw new Error('Invalid email format');
                }
                
                // Get CSRF token from meta tag if available
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                
                console.log('Original email:', email);
                console.log('Formatted email:', formattedEmail);
                console.log('Attempting Face ID login for:', formattedEmail);
                console.log('CSRF Token:', csrfToken);
                
                const response = await fetch('{{ route("front.faceid-login") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: formattedEmail }),
                    credentials: 'same-origin'
                });

                console.log('Response status:', response.status);
                
                const result = await response.json();
                console.log('Response data:', result);

                if (response.ok && result.success) {
                    // Login successful - redirect to home
                    closeFaceLoginModal();
                    window.location.href = result.redirect || '/';
                } else {
                    const status = document.getElementById('faceLoginStatus');
                    status.textContent = '❌ ' + (result.message || result.error || 'Login failed');
                    status.style.color = '#e74c3c';
                    
                    // Log detailed error
                    console.error('Login failed:', result);
                }
            } catch (error) {
                console.error('Face ID login error:', error);
                const status = document.getElementById('faceLoginStatus');
                status.textContent = '❌ Login failed. Please try again.';
                status.style.color = '#e74c3c';
            }
        }

        // Preview profile picture in signup form
        function previewSignupImage(input) {
            const previewImg = document.getElementById('signup-preview-img');
            const placeholder = document.getElementById('signup-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    placeholder.style.display = 'none';
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewImg.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        }
    </script>
</body>
</html>
