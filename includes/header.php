<?php

$pageTitle = $pageTitle ?? 'NSBM SportsHub';
$activePage = $activePage ?? '';
$bodyClass = $bodyClass ?? '';
$user = current_user();
$flashes = get_flashes();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="NSBM SportsHub — university sports clubs, training, fixtures and facility bookings in one place.">
        <title><?= e($pageTitle) ?> | NSBM SportsHub</title>
        <link rel="icon" type="image/png" href="<?= e(asset('img/image1.png')) ?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script>
            var savedTheme = 'light';
            try {
                savedTheme = localStorage.getItem('theme') || "light";
            } catch (error) {
                savedTheme = 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        </script>
        <link href="<?= e(asset('css/style.css')) ?>" rel="stylesheet">
        <link href="<?= e(asset('css/theme.css')) ?>" rel="stylesheet">
    </head>
    <body class="<?= e($bodyClass) ?>">
        <a class="skip-link" href="#main-content">Skip to main content</a>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark site-navbar" aria-label="Main navigation">
                <div class="container">
                    <a class="navbar-brand brand" href="<?= e(url('index.php')) ?>" aria-label="NSBM GreenArena home">
                        <img src="<?= e(asset('img/image.png')) ?>" alt="NSBM GreenArena Logo" class="brand-mark">
                        <span class="green-arena-title"><span class="t-nsbm">  NSBM</span><span class="t-green">GREEN</span><span class="t-arena">ARENA</span></span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto align-items-lg-center">
                            <li class="nav-item"><a class="nav-link <?= active_class('home', $activePage) ?>" href="<?= e(url('index.php')) ?>">Home</a></li>
                            <li class="nav-item"><a class="nav-link <?= active_class('clubs', $activePage) ?>" href="<?= e(url('clubs.php')) ?>">Clubs</a></li>
                            <li class="nav-item"><a class="nav-link <?= active_class('fixtures', $activePage) ?>" href="<?= e(url('fixtures.php')) ?>">Fixtures</a></li>
                            <li class="nav-item"><a class="nav-link <?= active_class('results', $activePage) ?>" href="<?= e(url('results.php')) ?>">Results</a></li>
                            <?php if ($user): ?>
                                <li class="nav-item"><a class="nav-link <?= active_class('dashboard', $activePage) ?>" href="<?= e(url($user['role'] === 'admin' ? 'admin/index.php' : 'student/dashboard.php')) ?>"><?= $user['role'] === 'admin' ? 'Admin panel' : 'My dashboard' ?></a></li>
                                <li class="nav-item ms-lg-2">
                                    <form action="<?= e(url('logout.php')) ?>" method="post" class="m-0">
                                        <button class="btn btn-sm btn-outline-light w-100" type="submit">Log out</button>
                                    </form>
                                </li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link <?= active_class('login', $activePage) ?>" href="<?= e(url('login.php')) ?>">Log in</a></li>
                                <li class="nav-item ms-lg-2 mt-2 mt-lg-0"><a class="btn btn-sm btn-sport" href="<?= e(url('register.php')) ?>">Join SportsHub</a></li>
                            <?php endif; ?>
                            <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                                <button class="theme-switch" id="theme-toggle" type="button" role="switch" aria-checked="false" aria-label="Dark mode">
                                    <img class="switch-icon icon-sun" src="<?= e(asset('img/sun.png')) ?>" alt="">
                                    <img class="switch-icon icon-moon" src="<?= e(asset('img/moon.png')) ?>" alt="">
                                    <span class="switch-knob"></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main id="main-content">
            <?php if ($flashes): ?>
                <div class="container flash-container" aria-live="polite">
                    <?php foreach ($flashes as $message): ?>
                        <div class="alert alert-<?= e($message['type']) ?> alert-dismissible fade show" role="alert">
                            <?= e($message['message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
