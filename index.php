<?php
$title="Las delicias Horneadas";
require_once 'php/db.php';
require 'layout/header.php';

$result=$mysqli->query("SELECT id, name, price, image FROM `products` ORDER BY id DESC LIMIT 5;");
$products = $result->fetch_all(MYSQLI_ASSOC);
 ?>
    <section class='hero-section'>
        <div class="container mb-5">
            <h1 class="hero-title font-playfair mb-4">Postres Artesanales</h1>
            <p class="lead mb-5 fs-4">Horneados diariamente con ingredientes auténticos</p>
            <a href="menu.php" class="btn btn-accent btn-lg shadow">Explora Nuestro Menu</a>
        </div>
    </section>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="font-playfair fs-1">Los Productos Más Nuevos</h2>
            <div class="mx-auto mt-2" style="width: 50px; height: 3px; background-color: var(--accent-color);"></div>
        </div>
    <div class="row g-4">
        <?php if (empty($products)): ?>
            <div class="col-12 text-center py-5">
            <i class="bi bi-basket text-muted" style="font-size: 4rem;"></i>
            <h3 class="mt-3 text-muted">More delicious pastries coming soon!</h3>
            </div>
    <?php else: ?>
        <?php foreach($products as $product): ?>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="card product-card h-100">
                    <?php
                        $imageData=base64_encode($product['image']);
                        $src='data:image/jpeg;base64,' . $imageData;
                    ?>
                    <img src="<?php echo $src ?>" class="card-img-top">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title font-playfair fs-4 mb-1"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="text-accent fw-bold fs-5 mb-3">$<?= number_format($product['price'], 2) ?></p>
                        <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-dark w-100 mt-2 rounded-pill">Ver Detalles</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
    </div>
    <?php require_once 'layout/footer.php'; ?>
</body>
</html>