<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: black;
            color: white;
        }

        /* Batasi tinggi dropdown dan aktifkan scroll hanya di dalam dropdown */
        .dropdown-menu {
            max-height: 80vh;
            /* 80% tinggi layar, sesuaikan kalau mau lebih pendek */
            overflow-y: auto;
            /* muncul scroll vertical kalau melebihi */
            overflow-x: hidden;
            /* sembunyikan scroll horizontal */
        }

        /* Opsional: biar lebih rapi di mobile */
        @media (max-width: 768px) {
            .dropdown-menu {
                max-height: 70vh;
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