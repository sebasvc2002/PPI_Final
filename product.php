<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
    <title>Document</title>
</head>
<body>
<?php
require_once 'php/db.php';
require_once 'php/header.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id===0){
    echo "<div class='container py-5 text-center'><h2>Producto no encontrado.</h2><a href='menu.php' class='btn btn-accent mt-3'>Regresar a Menú</a></div>";
    require_once 'php/footer.php';
    exit;
}
$stmt=$mysqli->prepare("SELECT p.id,p.name,p.price,p.description,c.name AS category ,p.image  FROM products p JOIN categories c ON c.id=p.id WHERE p.id=?;");
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();
$product=$result->fetch_assoc();

$imageData=base64_encode($product['image']);
$src='data:image/jpeg;base64,' . $imageData;

if(!$product){
    echo "<div class='container py-5 text-center'><h2>Producto no encontrado.</h2><a href='menu.php' class='btn btn-accent mt-3'>Regresar a Menú</a></div>";
    require_once 'php/footer.php';
    exit;
}
?>

<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="<?php echo $src?>" class="img-fluid w-100" style="object-fit: cover; min-height: 400px; max-height: 600px;" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
        </div>
        <div class="col-md-6 ps-md-5 ">
            <h1 class="font-playfair"><?= htmlspecialchars($product['name']) ?></h1>
            <h2 class="text-accent fw-bold mb-4">$<?= number_format($product['price'], 2) ?></h2>
            <p class="lead text-muted mb-5" style="line-height: 1.8;">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </p>

            <form action="" class="d-flex align-items-center gap-3">
                
                    <input type="number" name="" id="" class="form-control border-start-0 ps-0" min="1" max="99">
                    <button type="submit" class="btn btn-accent btn-lg ps-4 pe-4 rounded-pill shadow-sm">
                            <i class="bi bi-cart-plus me-2"></i>Agregar a Carrito
                    </button>
            </form>
        </div>

    </div>

</div>

<?php
require_once 'php/footer.php'
?>
</body>
</html>
