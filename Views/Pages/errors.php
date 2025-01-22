<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE . ' - ' . $data['title'] ?></title>
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/img/favicon-16x16.png">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Libraries/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        .error-container {
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .error-container h1 {
            font-size: 120px;
            font-weight: bold;
            color:rgb(255, 96, 68);
            margin-bottom: 20px;
        }

        .error-container p {
            font-size: 20px;
            color: #666;
            margin-bottom: 30px;
        }

        .btn-back {
            background-color: hsl(228, 86%, 60%);
            color: #fff;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: .6rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            color: #fff;
            background-color:hsl(228, 59%, 48%);
        }
    </style>
</head>

<body>

    <div class="error-container">
        <h1>404</h1>
        <p>¡Vaya! La página que buscas no está disponible.</p>
        <a href="<?php echo BASE_URL; ?>" class="btn-back">Volver a la página principal</a>
    </div>

    <script src="<?php echo BASE_URL; ?>assets/js/Libraries/bootstrap.min.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/Libraries/jquery-3.7.1.min.js"></script>
    <script>
        const BASE_URL = "<?php echo BASE_URL; ?>";
    </script>
</body>

</html>
