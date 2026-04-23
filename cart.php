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
    <title>Carrito - Las Delicias Horneadas</title>
    <style>
        .cart-nav-btn {
            font-weight: 500;
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 2px;
            text-decoration: none;
        }

        /* Cart Items */
        .cart-item-card {
            background-color: var(--card-surface);
            border-radius: 12px;
            padding: 1.5rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.03); /* Soft shadow complementing your product cards */
        }
        .cart-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        .qty-selector {
            background-color: var(--bg-color);
            border-radius: 50px;
            padding: 0.25rem 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
        }
        .qty-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.2rem;
            padding: 0;
            cursor: pointer;
        }
        .item-price {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary-color);
        }
        .tag-seasonal {
            /* Using your accent color for the tag */
            background-color: var(--accent-color);
            color: white;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Order Summary */
        .summary-card {
            background-color: var(--card-surface);
            border-radius: 16px;
            padding: 2rem;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            color: var(--text-muted-custom);
            font-size: 0.95rem;
        }
        .summary-row.total {
            color: var(--primary-color);
            font-size: 1.75rem;
            margin-top: 1rem;
        }
        
        /* Utilities */
        .fs-7 { font-size: 0.85rem; }
    </style>
</head>
<body>
    <?php 
        require_once 'php/header.php';
        require_once 'php/db.php'; ?>
        <main class="container my-5 main-content">
            <div class="mb-5">
                <h1 class="display-4 mb-2 font-playfair">Carrito</h1>
            </div>

            <div class="row gx-xl-5">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    
                    <div class="cart-item-card mb-4 d-flex flex-column flex-sm-row gap-4 position-relative">
                        <button class="btn btn-sm position-absolute top-0 end-0 m-3 text-muted-custom"><i class="bi bi-trash3-fill"></i></button>
                        <img src="https://images.unsplash.com/photo-1613929231151-d7571591259e?q=80&w=1287&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="cart-img">
                        <div class="d-flex flex-column justify-content-between w-100">
                            <div>
                                <h4 class="font-playfair mb-1 mt-2 mt-sm-0">Pain au Chocolat</h4>
                                <p class="text-muted-custom fs-7 mb-3">Slow-fermented dough, 70% Valrhona dark chocolate</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="qty-selector shadow-sm">
                                    <button class="qty-btn">&minus;</button>
                                    <span class="fw-medium">2</span>
                                    <button class="qty-btn">&plus;</button>
                                </div>
                                <div class="text-end">
                                    <span class="d-block text-muted-custom fs-7 text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Total</span>
                                    <span class="item-price">$11.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cart-item-card mb-4 d-flex flex-column flex-sm-row gap-4 position-relative">
                        <button class="btn btn-sm position-absolute top-0 end-0 m-3 text-muted-custom"><i class="bi bi-trash3-fill"></i></button>
                        <img src="https://images.unsplash.com/photo-1519915028121-7d3463d20b13?q=80&w=300&auto=format&fit=crop" alt="Fruit Tart" class="cart-img">
                        <div class="d-flex flex-column justify-content-between w-100">
                            <div>
                                <h4 class="font-playfair mb-1">Seasonal Fruit Tart</h4>
                                <p class="text-muted-custom fs-7 mb-3">Wild berries, Tahitian vanilla bean custard, sable crust</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-end">
                                <div class="qty-selector shadow-sm">
                                    <button class="qty-btn">&minus;</button>
                                    <span class="fw-medium">1</span>
                                    <button class="qty-btn">&plus;</button>
                                </div>
                                <div class="text-end">
                                    <span class="d-block text-muted-custom fs-7 text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Total</span>
                                    <span class="item-price">$14.50</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="#" class="text-decoration-none slide-link pb-1" style="color: var(--primary-color);">
                            <i class="bi bi-arrow-left me-2"></i> Regresar al menu
                        </a>
                    </div>

                </div>

                <div class="col-lg-5 col-xl-4 ms-auto">
                    
                    <div class="summary-card mb-4">
                        <h3 class="font-playfair mb-4">Resumen de orden</h3>
                        
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span class="fw-medium" style="color: var(--text-color);">$25.50</span>
                        </div>
                        <div class="summary-row">
                            <span>Envío</span>
                            <span class="fw-medium" style="color: var(--text-color);">$0.00</span>
                        </div>
                        
                        <div class="summary-row total align-items-end mb-4">
                            <span class="text-uppercase text-muted-custom mb-1 font-playfair" style="font-size: 0.8rem; letter-spacing: 1px;">Grand Total</span>
                            <span class="font-playfair">$25.50</span>
                        </div>

                        <button class="btn btn-accent w-100 py-3 mb-3 d-flex justify-content-center align-items-center gap-2">
                            Continuar compra <i class="bi bi-arrow-right"></i>
                        </button>

                    </div>
                </div>
            </div>
    </main>


    <?php require_once 'php/footer.php'; ?>
</body>
</html>