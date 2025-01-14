<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE . ' - ' . $data['title'] ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Libraries/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Libraries/notyf.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>

<body>

    <div class="body-form">

        <form class="modern-form" id="frmLogin">

            <div class="form-logo">
                <span>EVIHUB</span>
                <div class="form-logo-glow"></div>
            </div>

            <div class="form-title">Sistema Gestión de Archivos</div>

            <div class="form-body">

                <div class="input-group">
                    <span class="input-group-text" id="basic-addon1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                    </span>
                    <input type="email" id="usuario" name="usuario" class="form-control" placeholder="Usuario" aria-label="Usuario" aria-describedby="basic-addon1">
                    <div id="usuario-feedback" class="invalid-feedback">
                        <!-- Mensaje de error aparecerá aquí -->
                    </div>
                </div>

                <div class="input-group">
                    <span class="input-group-text" id="basic-addon2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1" />
                        </svg>
                    </span>
                    <input type="password" id="clave" name="clave" class="form-control" placeholder="Contraseña" aria-label="Usuario" aria-describedby="basic-addon2">
                    <div id="clave-feedback" class="invalid-feedback">
                        <!-- Mensaje de error aparecerá aquí -->
                    </div>
                </div>

            </div>

            <button class="submit-button" type="submit" id="loginButton">
                <span class="button-text">Iniciar Sesión</span>
                <div class="button-glow"></div>
            </button>

            <div class="form-footer">
                <a class="login-link">
                    Si no cuenta con credenciales, pongase en contacto con su adminsitrador
                </a>
            </div>
        </form>
    </div>

    <script src="<?php echo BASE_URL; ?>assets/js/Libraries/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/Libraries/jquery-3.7.1.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/Libraries/notyf.min.js"></script>
    <script>
        const BASE_URL = "<?php echo BASE_URL; ?>";
    </script>
    <script src="<?php echo BASE_URL; ?>assets/js/Pages/login.js"></script>
</body>

</html>