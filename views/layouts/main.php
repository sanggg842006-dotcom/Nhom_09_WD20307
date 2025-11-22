<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Quản lý Tour' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-wrapper {
            flex: 1;
        }

        .content-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            padding: 24px;
            margin-bottom: 24px;
        }

        .navbar-brand span {
            font-size: 0.8rem;
            font-weight: 400;
            opacity: 0.8;
        }

        .nav-link {
            border-radius: 999px;
            padding-inline: 1rem !important;
        }

        .nav-link.active,
        .nav-link:hover {
            background-color: rgba(255,255,255,0.15) !important;
        }

        footer {
            font-size: 0.85rem;
            color: #777;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <strong class="me-2">TourAdmin</strong>
            <span>Dashboard</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?= (($_GET['c'] ?? 'Tour') === 'Tour') ? 'active' : '' ?>"
                       href="index.php?c=Tour&a=index">
                        Quản lý Tour
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="nav-link <?= (($_GET['c'] ?? '') === 'Guide') ? 'active' : '' ?>"
                       href="index.php?c=Guide&a=index">
                        Quản lý Nhân sự
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-wrapper">
    <div class="container mb-4">
        <div class="content-card">
            <?= $content ?? '' ?>
        </div>
    </div>
</div>

<footer class="border-top py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <span>© <?= date('Y') ?> TourAdmin Panel</span>
        <span class="text-muted">Mô hình MVC – Quản lý tour & nhân sự</span>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

