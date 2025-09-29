<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Waste2Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

                        <form id="signupForm" method="POST" action="{{ route('front.signup.post') }}">
                            @csrf
                            <input type="hidden" name="from" value="front">
                            <input type="hidden" name="g-recaptcha-response" id="signup-g-recaptcha-response">
                            <div class="input-group">
                                <i class='bx bxs-user'></i>
                                <input name="name" type="text" placeholder="Full name" required value="{{ old('name') }}">
                            </div>
                            <div class="input-group">
                                <i class='bx bx-mail-send'></i>
                                <input name="email" type="email" placeholder="Email" required value="{{ old('email') }}">
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input name="password" type="password" placeholder="Password" required>
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input name="password_confirmation" type="password" placeholder="Confirm password" required>
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
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input name="password" type="password" id="login-password" placeholder="Password" required>
                            </div>
                            <button type="button" onclick="showSigninRecaptcha()">Sign in</button>
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

        function showSignupRecaptcha(){
            const form = document.getElementById('signupForm');
            const name = form.querySelector('input[name="name"]').value;
            const email = form.querySelector('input[name="email"]').value;
            const password = form.querySelector('input[name="password"]').value;
            const passwordConfirm = form.querySelector('input[name="password_confirmation"]').value;
            
            if (!name || !email || !password || !passwordConfirm) {
                alert('Please fill in all required fields.');
                return;
            }
            
            if (password !== passwordConfirm) {
                alert('Passwords do not match.');
                return;
            }
            
            currentForm = form;
            showRecaptchaModal();
        }

        function showSigninRecaptcha(){
            const form = document.getElementById('signinForm');
            const email = form.querySelector('input[name="email"]').value;
            const password = form.querySelector('input[name="password"]').value;
            
            if (!email || !password) {
                alert('Please fill in all required fields.');
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
    </script>
</body>
</html>
