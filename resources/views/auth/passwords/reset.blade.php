<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demonteverde Agrivet Supply - Reset Password</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2d5a3d;
            --primary-light: #4a7c59;
            --primary-dark: #1e3a21;
            --secondary-color: #8b4513;
            --accent-color: #d4a574;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --box-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(45, 90, 61, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139, 69, 19, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(45, 90, 61, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        .reset-container {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
        }

        .reset-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: 1px solid rgba(45, 90, 61, 0.1);
            transition: all 0.3s ease;
            animation: slideUp 0.6s ease-out;
        }

        .reset-card:hover {
            box-shadow: var(--box-shadow-hover);
            transform: translateY(-5px);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reset-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .reset-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .reset-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .reset-header p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .reset-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.15);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            z-index: 1;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .reset-body {
            padding: 40px 30px;
        }

        .form-floating {
            position: relative;
            margin-bottom: 25px;
        }

        .form-floating input {
            border: 2px solid #e3e6f0;
            border-radius: var(--border-radius);
            padding: 20px 20px 20px 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-floating input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(45, 90, 61, 0.25);
            background: white;
        }

        .form-floating i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.1rem;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .form-floating input:focus + i {
            color: var(--primary-color);
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: var(--border-radius);
            padding: 15px 30px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-reset::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-reset:hover::before {
            left: 100%;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 90, 61, 0.3);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .btn-back {
            background: none;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 15px;
        }

        .btn-back:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 90, 61, 0.3);
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            margin-bottom: 25px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .reset-footer {
            text-align: center;
            padding: 20px 30px 30px;
            background: rgba(248, 249, 250, 0.5);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .reset-footer p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-reset.loading .loading-spinner {
            display: inline-block;
        }

        .btn-reset.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        /* Password strength indicator */
        .password-strength {
            margin-top: 10px;
            font-size: 0.85rem;
        }

        .password-strength-weak {
            color: var(--danger-color);
        }

        .password-strength-medium {
            color: var(--warning-color);
        }

        .password-strength-strong {
            color: var(--success-color);
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .reset-container {
                padding: 15px;
            }

            .reset-header {
                padding: 30px 20px 20px;
            }

            .reset-header h2 {
                font-size: 1.5rem;
            }

            .reset-body {
                padding: 30px 20px;
            }
        }

        /* Form validation styles */
        .is-invalid {
            border-color: var(--danger-color) !important;
            animation: shake 0.3s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 5px;
            animation: slideIn 0.3s ease-out;
        }

        /* Focus styles for accessibility */
        .btn-reset:focus,
        .btn-back:focus,
        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(45, 90, 61, 0.25);
        }

        /* Additional styles for better UX */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 3;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="card reset-card">
            <div class="reset-header">
                <div class="reset-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h2>Set New Password</h2>
                <p>Enter your new password below</p>
            </div>

            <div class="reset-body">
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-floating">
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email"
                               value="{{ $email ?? old('email') }}"
                               required
                               autocomplete="email"
                               readonly
                               placeholder="Email Address">
                        <i class="bi bi-envelope-fill"></i>
                        <label for="email">Email Address</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-floating">
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="New Password">
                        <i class="bi bi-lock-fill"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="bi bi-eye" id="passwordToggleIcon"></i>
                        </button>
                        <label for="password">New Password</label>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>

                    <div class="form-floating">
                        <input id="password-confirm" type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Confirm New Password">
                        <i class="bi bi-lock-fill"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                            <i class="bi bi-eye" id="passwordConfirmToggleIcon"></i>
                        </button>
                        <label for="password-confirm">Confirm New Password</label>
                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-reset" id="resetBtn">
                        <span class="loading-spinner" id="loadingSpinner"></span>
                        Update Password
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn-back">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Login
                        </a>
                    </div>
                </form>
            </div>

            <div class="reset-footer">
                <p>&copy; 2024 Demonteverde Agrivet Supply. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const resetForm = document.getElementById('resetPasswordForm');
            const resetBtn = document.getElementById('resetBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password-confirm');

            // Add loading state to form submission
            resetForm.addEventListener('submit', function(e) {
                resetBtn.classList.add('loading');
                resetBtn.disabled = true;
            });

            // Add floating label animation
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(function(control) {
                control.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                control.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });

                // Check if input has value on page load
                if (control.value) {
                    control.parentElement.classList.add('focused');
                }
            });

            // Add keyboard navigation support
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.type !== 'textarea') {
                    const focusedElement = document.activeElement;
                    if (focusedElement && focusedElement.form) {
                        e.preventDefault();
                        focusedElement.form.requestSubmit();
                    }
                }
            });

            // Password strength checker
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strengthIndicator = document.getElementById('passwordStrength');

                if (password.length === 0) {
                    strengthIndicator.textContent = '';
                    return;
                }

                let strength = 0;
                let feedback = '';

                // Length check
                if (password.length >= 8) strength += 1;

                // Character variety checks
                if (/[a-z]/.test(password)) strength += 1;
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[0-9]/.test(password)) strength += 1;
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;

                if (strength <= 2) {
                    feedback = 'Weak password';
                    strengthIndicator.className = 'password-strength password-strength-weak';
                } else if (strength <= 3) {
                    feedback = 'Medium strength';
                    strengthIndicator.className = 'password-strength password-strength-medium';
                } else {
                    feedback = 'Strong password';
                    strengthIndicator.className = 'password-strength password-strength-strong';
                }

                strengthIndicator.textContent = feedback;
            });

            // Password confirmation validation
            passwordConfirmInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });

            passwordInput.addEventListener('input', function() {
                if (passwordConfirmInput.value && passwordConfirmInput.value !== this.value) {
                    passwordConfirmInput.classList.add('is-invalid');
                } else {
                    passwordConfirmInput.classList.remove('is-invalid');
                }
            });
        });

        // Password toggle functionality
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId + 'ToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
