<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}
$title="Cuenta - Las Delicias Horneadas";
include 'layout/header.php';

$user_id = (int) $_SESSION['user_id'];

// Active tab from query param or default
$active_tab = $_GET['tab'] ?? 'profile';
if (!in_array($active_tab, ['profile', 'addresses', 'orders'])) {
    $active_tab = 'profile';
}

// Load user data
$stmt = $mysqli->prepare("SELECT name, email, card_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Masked card number
$card_display = '';
if (!empty($user['card_number']) && $user['card_number'] > 0) {
    $card_str = (string) $user['card_number'];
    $card_display = '•••• •••• •••• ' . substr($card_str, -4);
}

// Load addresses
$stmt = $mysqli->prepare("SELECT id, street, city, country, postal_code FROM user_addresses WHERE user_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$addresses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Load orders
$stmt = $mysqli->prepare("SELECT o.id, o.total, o.placed_at, a.street, a.city FROM orders o LEFT JOIN user_addresses a ON a.id = o.address_id WHERE o.user_id = ? ORDER BY o.placed_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Flash messages
$profile_msg = $_SESSION['profile_msg'] ?? '';
$profile_msg_type = $_SESSION['profile_msg_type'] ?? 'info';
unset($_SESSION['profile_msg'], $_SESSION['profile_msg_type']);

$address_msg = $_SESSION['address_msg'] ?? '';
$address_msg_type = $_SESSION['address_msg_type'] ?? 'info';
unset($_SESSION['address_msg'], $_SESSION['address_msg_type']);
?>

<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <!-- Left Column: User Card + Nav Pills -->
            <div class="col-lg-4 mb-4">
                <div class="card product-card p-4 text-center">
                    <div class="bg-accent rounded-circle mx-auto d-flex align-items-center justify-content-center text-white mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill fs-1"></i>
                    </div>
                    <h3 class="font-playfair mb-1"><?= htmlspecialchars($user['name']) ?></h3>
                    <p class="text-muted small mb-0"><?= htmlspecialchars($user['email']) ?></p>
                    <hr class="my-4 opacity-25">
                    <div class="list-group list-group-flush text-start" id="accountTabs" role="tablist">
                        <a href="#profile" class="list-group-item list-group-item-action border-0 py-3 rounded-pill mb-2 <?= $active_tab === 'profile' ? 'active' : '' ?>"
                           data-bs-toggle="pill" role="tab">
                            <i class="bi bi-person me-2"></i> Información Personal
                        </a>
                        <a href="#addresses" class="list-group-item list-group-item-action border-0 py-3 rounded-pill mb-2 <?= $active_tab === 'addresses' ? 'active' : '' ?>"
                           data-bs-toggle="pill" role="tab">
                            <i class="bi bi-geo-alt me-2"></i> Mis Direcciones
                        </a>
                        <a href="#orders" class="list-group-item list-group-item-action border-0 py-3 rounded-pill mb-2 <?= $active_tab === 'orders' ? 'active' : '' ?>"
                           data-bs-toggle="pill" role="tab">
                            <i class="bi bi-bag-check me-2"></i> Historial de Pedidos
                        </a>
                        <a href="php/logout.php" class="list-group-item list-group-item-action border-0 py-3 rounded-pill text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Tab Content -->
            <div class="col-lg-8">
                <div class="tab-content">

                    <!-- ═══ TAB 1: Profile Info ═══ -->
                    <div class="tab-pane fade <?= $active_tab === 'profile' ? 'show active' : '' ?>" id="profile" role="tabpanel">
                        <div class="card product-card p-4 p-md-5">
                            <h2 class="font-playfair mb-4"><i class="bi bi-person me-2 text-muted"></i>Información del Perfil</h2>

                            <?php if ($profile_msg): ?>
                            <div class="alert alert-<?= $profile_msg_type ?> alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($profile_msg) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <form action="php/update_profile.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label small text-secondary">Nombre Completo</label>
                                        <input type="text" class="form-control border-0 border-bottom rounded-0 px-0"
                                               id="name" name="name"
                                               value="<?= htmlspecialchars($user['name']) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label small text-secondary">Correo Electrónico</label>
                                        <input type="email" class="form-control border-0 border-bottom rounded-0 px-0"
                                               id="email" name="email"
                                               value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="card_number" class="form-label small text-secondary">Número de Tarjeta</label>
                                        <input type="text" class="form-control border-0 border-bottom rounded-0 px-0"
                                               id="card_number" name="card_number"
                                               value="<?= htmlspecialchars($card_display) ?>"
                                               placeholder="Ej. 4152313456781234"
                                               maxlength="19">
                                        <small class="text-muted">Deja vacío o sin cambios para conservar el actual.</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label small text-secondary">Nueva Contraseña</label>
                                        <input type="password" class="form-control border-0 border-bottom rounded-0 px-0"
                                               id="new_password" name="new_password"
                                               placeholder="Dejar vacío para no cambiar">
                                    </div>
                                </div>

                                <hr class="my-4 opacity-25">

                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                    <p class="text-muted small m-0">Tus datos están protegidos bajo nuestras políticas de privacidad.</p>
                                    <button type="submit" class="btn btn-accent rounded-pill px-5">Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ═══ TAB 2: Addresses ═══ -->
                    <div class="tab-pane fade <?= $active_tab === 'addresses' ? 'show active' : '' ?>" id="addresses" role="tabpanel">
                        <div class="card product-card p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="font-playfair mb-0"><i class="bi bi-geo-alt me-2 text-muted"></i>Mis Direcciones</h2>
                                <button class="btn btn-accent rounded-pill btn-sm px-3" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#newAddressForm">
                                    <i class="bi bi-plus-lg me-1"></i>Nueva
                                </button>
                            </div>

                            <?php if ($address_msg): ?>
                            <div class="alert alert-<?= $address_msg_type ?> alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($address_msg) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <!-- New address form (collapsed) -->
                            <div class="collapse mb-4" id="newAddressForm">
                                <div class="card card-body border-0 bg-light rounded-3">
                                    <h6 class="fw-semibold mb-3">Agregar Dirección</h6>
                                    <form action="php/update_address.php" method="POST">
                                        <input type="hidden" name="action" value="create">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <input type="text" class="form-control" name="street" placeholder="Calle y número" required>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="city" placeholder="Ciudad" required>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" name="country" placeholder="País" required>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" class="form-control" name="postal_code" placeholder="C.P." required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-accent btn-sm rounded-pill mt-3 px-4">Guardar</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Existing addresses -->
                            <?php if (empty($addresses)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-geo-alt fs-1 d-block mb-2 opacity-50"></i>
                                <p>No tienes direcciones registradas.</p>
                            </div>
                            <?php else: ?>
                                <?php foreach ($addresses as $addr): ?>
                                <div class="border rounded-3 p-3 mb-3">
                                    <!-- Display mode -->
                                    <div id="addr-display-<?= $addr['id'] ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="mb-1 fw-semibold"><?= htmlspecialchars($addr['street']) ?></p>
                                                <p class="mb-0 text-muted small">
                                                    <?= htmlspecialchars($addr['city']) ?>, <?= htmlspecialchars($addr['country']) ?> — C.P. <?= htmlspecialchars($addr['postal_code']) ?>
                                                </p>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill"
                                                        onclick="toggleEdit(<?= $addr['id'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a href="php/update_address.php?action=delete&id=<?= $addr['id'] ?>"
                                                   class="btn btn-sm btn-outline-danger rounded-pill"
                                                   onclick="return confirm('¿Eliminar esta dirección?')">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit mode (hidden by default) -->
                                    <div id="addr-edit-<?= $addr['id'] ?>" style="display:none;">
                                        <form action="php/update_address.php" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?= $addr['id'] ?>">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <input type="text" class="form-control form-control-sm" name="street"
                                                           value="<?= htmlspecialchars($addr['street']) ?>" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control form-control-sm" name="city"
                                                           value="<?= htmlspecialchars($addr['city']) ?>" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control form-control-sm" name="country"
                                                           value="<?= htmlspecialchars($addr['country']) ?>" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control form-control-sm" name="postal_code"
                                                           value="<?= htmlspecialchars($addr['postal_code']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mt-2">
                                                <button type="submit" class="btn btn-accent btn-sm rounded-pill px-3">Guardar</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                                        onclick="toggleEdit(<?= $addr['id'] ?>)">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ═══ TAB 3: Order History ═══ -->
                    <div class="tab-pane fade <?= $active_tab === 'orders' ? 'show active' : '' ?>" id="orders" role="tabpanel">
                        <div class="card product-card p-4 p-md-5">
                            <h2 class="font-playfair mb-4"><i class="bi bi-bag-check me-2 text-muted"></i>Historial de Pedidos</h2>

                            <?php if (empty($orders)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-bag fs-1 d-block mb-2 opacity-50"></i>
                                <p>Aún no tienes pedidos.</p>
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th class="d-none d-md-table-cell">Dirección</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $ord): ?>
                                        <tr>
                                            <td><?= $ord['id'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($ord['placed_at'])) ?></td>
                                            <td class="d-none d-md-table-cell text-muted"><?= htmlspecialchars(($ord['street'] ?? '') . ', ' . ($ord['city'] ?? '')) ?></td>
                                            <td class="text-end fw-semibold">$<?= number_format($ord['total'], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<script>
function toggleEdit(id) {
    const display = document.getElementById('addr-display-' + id);
    const edit = document.getElementById('addr-edit-' + id);
    if (edit.style.display === 'none') {
        edit.style.display = 'block';
        display.style.display = 'none';
    } else {
        edit.style.display = 'none';
        display.style.display = 'block';
    }
}
</script>

<?php require_once 'layout/footer.php'; ?>
</body>
</html>