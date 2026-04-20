<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <title>Bootstrap Test</title>
</head>
<body>
    <?php 
        require_once 'php/header.php';
        require_once 'php/db.php';
        //$result=$mysqli->query('SELECT');
     ?>
    <section class='hero-section'>
        <div class="container mb-5">
            <h1 class="hero-title font-playfair mb-4">Postres Artesanales</h1>
            <p class="lead mb-5 fs-4">Horneados diariamente con ingredientes auténticos</p>
            <a href="#menu" class="btn btn-accent btn-lg shadow">Explora Nuestro Menu</a>
        </div>
    </section>

    <div class="container py-5">
        <div class="text-center">
            <h2 class="font-playfair fs-1">Los Productos Más Nuevos</h2>
            <div class="mx-auto" style="width: 50px; height: 3px; background-color: var(--accent-color);"></div>
        </div>
    </div>

    <?php require_once 'php/footer.php'; ?>
</body>
</html>