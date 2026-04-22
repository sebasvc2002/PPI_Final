<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'php/header.php'; ?>

    <main class="main-content d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row g-0 product-card mx-auto overflow-hidden" style="max-width: 900px;">
                
                <div class="col-md-6 p-5 bg-white">
                    <h1 class="font-playfair fs-1">Iniciar Sesión</h1>
                    
                    <form action="login.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label small text-secondary">Correo Electrónico</label>
                            <input type="email" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="ejemplo@mail.com" required>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label small text-secondary">Contraseña</label>
                            </div>
                            <input type="password" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="*********" required>
                        </div>

                        <button type="submit" class="btn-accent w-100 mt-3 py-3">Iniciar Sesión</button>
                    </form>

                    <p class="text-center mt-4 small text-muted">
                        ¿No tienes una cuenta? <a href="#" class="fw-bold text-decoration-none">Registrarse</a>
                    </p>
                </div>

                <div class="col-md-6 d-none d-md-block position-relative bg-primary">
                    <img src="img/login-cupcake.jpg" class="w-100 h-100 object-fit-cover opacity-75" alt="Bakery">
                </div>

            </div>
        </div>
    </main>

<?php include 'php/footer.php'; ?>
</body>
</html>