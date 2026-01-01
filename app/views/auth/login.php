<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Login - <?php echo APP_NAME; ?></title>
    <!-- CSS de Tabler -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet"/>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .login-form-section {
            width: 40%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 2rem;
        }

        .login-image-section {
            width: 60%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .login-image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.9;
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section h1 {
            color: #206bc4;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .logo-section p {
            color: #667;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-form-section {
                width: 100%;
                height: auto;
            }

            .login-image-section {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Sección del formulario (40%) -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <div class="logo-section">
                    <h1>
                        <i class="ti ti-lock-square-rounded"></i>
                        <?php echo APP_NAME; ?>
                    </h1>
                    <p>Inicia sesión para acceder al sistema</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-alert-circle"></i>
                            </div>
                            <div class="ms-2">
                                <?php
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                ?>
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-check"></i>
                            </div>
                            <div class="ms-2">
                                <?php
                                    echo $_SESSION['success'];
                                    unset($_SESSION['success']);
                                ?>
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                <?php endif; ?>

                <form action="<?php echo APP_URL; ?>/login" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Usuario o Email</label>
                        <div class="input-group input-group-flat">
                            <span class="input-group-text">
                                <i class="ti ti-user"></i>
                            </span>
                            <input type="text"
                                   name="identifier"
                                   class="form-control"
                                   placeholder="Ingrese su usuario o email"
                                   autocomplete="username"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Contraseña
                            <span class="form-label-description">
                                <a href="#">¿Olvidaste tu contraseña?</a>
                            </span>
                        </label>
                        <div class="input-group input-group-flat">
                            <span class="input-group-text">
                                <i class="ti ti-lock"></i>
                            </span>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Tu contraseña"
                                   autocomplete="current-password"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input"/>
                            <span class="form-check-label">Recordarme en este dispositivo</span>
                        </label>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-login"></i>
                            Iniciar Sesión
                        </button>
                    </div>
                </form>

                <div class="text-center text-muted mt-3">
                    ¿No tienes una cuenta? <a href="#" tabindex="-1">Regístrate</a>
                </div>

                <div class="text-center text-muted mt-4">
                    <small>
                        Usuario demo: <strong>admin</strong> | Contraseña: <strong>admin123</strong>
                    </small>
                </div>
            </div>
        </div>

        <!-- Sección de imagen (60%) -->
        <div class="login-image-section">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80"
                 alt="Login Background">
        </div>
    </div>

    <!-- JS de Tabler -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js"></script>
</body>
</html>
