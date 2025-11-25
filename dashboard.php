<?php
include "connection.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
$nama = htmlspecialchars($_SESSION['nama']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        /* Hanya dropdown Teams yang lebar + scrollable */
        .navbar-main {
            background-image: url(asset/background-navbar.avif);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: transparent !important;
        }
        
        .teams-dropdown {
            width: 360px !important;
            /* khusus Teams saja */
            max-height: 80vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Dropdown lain (Games, dll) tetap normal */
        .dropdown-menu:not(.teams-dropdown) {
            width: auto;
            max-height: none;
            overflow: visible;
        }

        /* Logo + nama tim rapi */
        .team-item {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 0.45rem 1rem;
            transition: all 0.2s;
        }

        .team-item img {
            width: 26px;
            height: 26px;
            flex-shrink: 0;
        }

        .team-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd !important;
            border-radius: 6px;
        }

        .dropdown-header {
            padding-left: 1.5rem;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1a1a1a;
        }

        /* Hover buka dropdown di desktop (lebih smooth) */
        @media (min-width: 992px) {
            .dropdown:hover>.dropdown-menu {
                display: block;
            }
        }
    </style>
</head>

<body>
    <?php include "navbar.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>