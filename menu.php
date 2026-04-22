<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Menú - Las Delicias Horneadas</title>
    <style>
        .nav-pills .nav-link {
            color: var(--text-color);
            background-color: #e9ecef;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link:hover {
            background-color: #dee2e6;
        }
        .nav-pills .nav-link.active-filter {
            background-color: var(--accent-color) !important;
            color: white !important;
            box-shadow: 0 4px 6px rgba(226, 167, 111, 0.3);
        }
    </style>
</head>
<body>
    <?php 
        require_once 'php/header.php';
        require_once 'php/db.php';

        //Obtener las categorías de la base de datos
        $cat_result = $mysqli->query("SELECT id, name FROM categories ORDER BY id ASC");
        $categories = $cat_result->fetch_all(MYSQLI_ASSOC);

        //Revisar categoría
        $selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

        //Obtener productos de categoría
        if ($selected_category > 0) {
            $stmt = $mysqli->prepare("SELECT id, name, price, image FROM products WHERE category_id = ? ORDER BY id DESC");
            $stmt->bind_param("i", $selected_category);
            $stmt->execute();
            $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            //mostrar todas
            $result = $mysqli->query("SELECT id, name, price, image FROM products ORDER BY id DESC");
            $products = $result->fetch_all(MYSQLI_ASSOC);
        }
    ?>
    <section class="hero-menu">
        <h1 class="hero-title font-playfair mb-4">Nuestro menú</h1>
        <p class="lead mb-5 fs-4">Explora nuestra selección de productos recién horneados</p>

    </section>


    <div class="container py-5 main-content">
        <div class="row mb-5">
            <div class="col-12 d-flex justify-content-center flex-wrap gap-2">
                <ul class="nav nav-pills justify-content-center">
                    <li class="nav-item me-2 mb-2">
                        <a class="nav-link px-4 <?= $selected_category === 0 ? 'active-filter' : '' ?>" href="menu.php">
                            Todos
                        </a>
                    </li>
                    <?php foreach($categories as $cat): ?>
                        <li class="nav-item me-2 mb-2">
                            <a class="nav-link px-4 <?= $selected_category === $cat['id'] ? 'active-filter' : '' ?>" 
                               href="menu.php?category=<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="row g-4">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-basket text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 text-muted font-playfair">No hay productos en esta categoría aún.</h3>
                    <a href="menu.php" class="btn btn-accent mt-3">Ver todos los productos</a>
                </div>
            <?php else: ?>
                <?php foreach($products as $product): ?>
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card product-card h-100">
                            <?php
                                $imageData = base64_encode($product['image']);
                                $src = 'data:image/jpeg;base64,' . $imageData;
                            ?>
                            <img src="<?php echo $src ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title font-playfair fs-4 mb-1"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="text-accent fw-bold fs-5 mb-3">$<?= number_format($product['price'], 2) ?></p>
                                <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-dark w-100 mt-auto rounded-pill">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once 'php/footer.php'; ?>
</body>
</html>