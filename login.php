<?php
session_start();
include 'layout/header.php';
$errors=[
    'login' => $_SESSION['login_error']?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm=$_SESSION['active_form'] ?? 'login';
session_unset();
function showError($error){
    return !empty($error) ? "<div class='alert alert-danger alert-dismissible fade show'>$error<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>" : '';
}
function isActiveForm($formName,$activeForm){
    return $formName === $activeForm ? 'active' : '';
}
?>    
    <main class="main-content d-flex align-items-center justify-content-center py-5">
        <div class="container form <?=isActiveForm('login',$activeForm);?>" id="login-form">
            <div class="row g-0 product-card mx-auto overflow-hidden" style="max-width: 900px;">
                
                <div class="col-md-6 p-5 bg-white">
                    <h1 class="font-playfair fs-1">Iniciar Sesión</h1>
                    <?= showError($errors['login']);?>
                    <form action="php/signin.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label small text-secondary">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="ejemplo@mail.com" required>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label small text-secondary">Contraseña</label>
                            </div>
                            <input type="password" name="password" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="*********" required>
                        </div>

                        <button type="submit" name="login" class="btn-accent w-100 mt-3 py-3">Iniciar Sesión</button>
                    </form>

                    <p class="text-center mt-4 small text-muted">
                        ¿No tienes una cuenta? <a href="#" class="fw-bold text-decoration-none" onclick="showForm('register-form')">Registrarse</a>
                    </p>
                </div>

                <div class="col-md-6 d-none d-md-block position-relative bg-primary">
                    <img src="img/login-cupcake.jpg" class="w-100 h-100 object-fit-cover opacity-75" alt="Bakery">
                </div>

            </div>
        </div>

        <div class="container form <?=isActiveForm('register',$activeForm);?>" id="register-form">
            <div class="row g-0 product-card mx-auto overflow-hidden" style="max-width: 900px;">
                
                <div class="col-md-6 d-none d-md-block position-relative bg-primary">
                    <img src="img/register-baker.jpg" class="w-100 h-100 object-fit-cover opacity-75" alt="Bakery">
                </div>

                <div class="col-md-6 p-5 bg-white">
                    <h1 class="font-playfair fs-1">Registro</h1>
                    <?= showError($errors['register']);?>
                    <form action="php/signin.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label small text-secondary">Nombre Completo</label>
                            <input type="text" id="name" name="name" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="Juan Perez" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small text-secondary">Correo Electrónico</label>
                            <input type="email" id="email" name="email" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="ejemplo@mail.com" required>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label small text-secondary">Contraseña</label>
                            </div>
                            <input type="password" id="password" name="password" class="form-control border-0 border-bottom rounded-0 px-0" placeholder="*********" required>
                        </div>

                        <button type="submit" name="register" class="btn-accent w-100 mt-3 py-3">Registrarse</button>
                    </form>

                    <p class="text-center mt-4 small text-muted">
                        ¿Ya tienes una cuenta? <a href="#" class="fw-bold text-decoration-none" onclick="showForm('login-form')">Iniciar sesión</a>
                    </p>
                </div>

                

            </div>
        </div>
        <script src="js/login.js"></script>
    </main>

<?php include 'layout/footer.php'; ?>
</body>
</html>