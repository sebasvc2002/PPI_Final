<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}
$title="Cuenta - Las Delicias Horneadas";
include 'layout/header.php'; ?>

    <main class="main-content py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card product-card p-4 text-center">
                        <div class="bg-accent rounded-circle mx-auto d-flex align-items-center justify-content-center text-white mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill fs-1"></i>
                        </div>
                        <h3 class="font-playfair mb-1">Sebastián Velasco</h3>
                        <hr class="my-4 opacity-25 ">
                        <div class="list-group list-group-flush text-start">
                            <a href="#" class="list-group-item list-group-item-action border-0 active-filter py-3 rounded-pill mb-2">
                                <i class="bi bi-person me-2"></i> Información Personal
                            </a>
                            <a href="#" class="list-group-item list-group-item-action border-0 py-3 rounded-pill mb-2">
                                <i class="bi bi-geo-alt me-2"></i> Mis Direcciones
                            </a>
                            <a href="#" class="list-group-item list-group-item-action border-0 py-3 rounded-pill mb-2">
                                <i class="bi bi-bag-check me-2"></i> Historial de Pedidos
                            </a>
                            <a href="#" class="list-group-item list-group-item-action border-0 py-3 rounded-pill text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card product-card p-5">
                        <h2 class="font-playfair mb-4">Información del Perfil</h2>
                        
                        <form action="php/update_profile.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small text-secondary">Nombre Completo</label>
                                    <input type="text" class="form-control border-0 border-bottom rounded-0 px-0" value="Sebastián Velasco" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small text-secondary">Correo Electrónico</label>
                                    <input type="email" class="form-control border-0 border-bottom rounded-0 px-0" value="sebastian.velasco@anahuac.mx" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small text-secondary">Teléfono</label>
                                    <input type="tel" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="+52 123 456 7890">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label small text-secondary">Número de Tarjeta</label>
                                    <input type="text" class="form-control border-0 border-bottom rounded-0 px-0" value="**** **** **** 1234" disabled>
                                </div>
                            </div>

                            <hr class="my-4 opacity-25">

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <p class="text-muted small m-0">Tus datos están protegidos bajo nuestras políticas de privacidad.</p>
                                <button type="submit" class="btn btn-accent rounded-pill px-5">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require_once 'layout/footer.php'; ?>
</body>
</html>