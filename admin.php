<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Las Delicias Horneadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #5C3A21;
            --accent-color: #E2A76F;
            --bg-color: #FAFAFA;
            --text-color: #333333;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f4f6f9; /* Slightly darker than main bg for contrast in admin */
            color: var(--text-color);
            overflow-x: hidden;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: var(--primary-color);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 8px;
            margin-inline: 10px;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(226, 167, 111, 0.2); /* Accent color with opacity */
            color: var(--accent-color);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* Top Navbar */
        .admin-topbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .bg-accent-light {
            background-color: rgba(226, 167, 111, 0.15);
            color: var(--primary-color);
        }

        /* Table adjustments */
        .table-custom th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }
        .text-accent{
            color:white;
        }
    </style>
</head>
<body>

    <div class="d-flex">
        <aside class="sidebar d-flex flex-column py-3">
            <div class="text-center mb-4 px-3">
                <h5 class="text-white font-playfair m-0 pt-2">Las Delicias Horneadas</h5>
                <small class="text-white ">Admin Panel</small>
                
            </div>
            
            <hr class="text-white mx-3 opacity-25">

            <ul class="nav flex-column mb-auto mt-2">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-tags"></i> Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-truck"></i> Proveedores
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-receipt"></i> Pedidos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
            </ul>

            <hr class="text-white mx-3 opacity-25">
            
            <div class="px-3">
                <a href="index.php" class="btn btn-outline-light w-100 rounded-pill">
                    <i class="bi bi-box-arrow-left"></i> Volver a la Tienda
                </a>
            </div>
        </aside>

        <main class="flex-grow-1">
            <nav class="navbar admin-topbar px-4 py-3 mb-4 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-playfair text-muted">Resumen General</h5>
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light rounded-circle"><i class="bi bi-bell"></i></button>
                    <div class="dropdown">
                        <button class="btn border-0 dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">A</div>
                            <span>Admin</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#">Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4">
                
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Ventas Totales</h6>
                                    <h3 class="mb-0">$24,500</h3>
                                </div>
                                <div class="stat-icon bg-accent-light">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Pedidos Nuevos</h6>
                                    <h3 class="mb-0">12</h3>
                                </div>
                                <div class="stat-icon bg-accent-light">
                                    <i class="bi bi-bag-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Productos Activos</h6>
                                    <h3 class="mb-0">45</h3>
                                </div>
                                <div class="stat-icon bg-accent-light">
                                    <i class="bi bi-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card h-100 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Usuarios Registrados</h6>
                                    <h3 class="mb-0">128</h3>
                                </div>
                                <div class="stat-icon bg-accent-light">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card stat-card mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-playfair">Pedidos Recientes</h5>
                        <button class="btn btn-sm btn-outline-primary rounded-pill">Ver todos</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">ID Pedido</th>
                                        <th>Cliente (User ID)</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th class="pe-4 text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-4">#ORD-001</td>
                                        <td>Usuario #5</td>
                                        <td>2026-04-20 14:30</td>
                                        <td>$450.00</td>
                                        <td class="pe-4 text-end">
                                            <button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">#ORD-002</td>
                                        <td>Usuario #12</td>
                                        <td>2026-04-20 12:15</td>
                                        <td>$280.00</td>
                                        <td class="pe-4 text-end">
                                            <button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>