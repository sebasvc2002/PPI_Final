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

       

    <?php require_once 'php/footer.php'; ?>
</body>
</html>