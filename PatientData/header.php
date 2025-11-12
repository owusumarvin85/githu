<?php
require_once __DIR__ . '/auth.php';
$currentUser = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Patient Manager</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		:root {
			--primary: #2F3C7E;
			--primary-dark: #1E2A5A;
			--accent: #F39422;
			--mint: #ECF8F8;
			--slate: #4C5B7A;
			--text-muted: #6c7a91;
			--radius-xl: 24px;
		}
		body {
			background: radial-gradient(circle at top left, rgba(236, 248, 248, 0.88), #ffffff 40%);
			min-height: 100vh;
			font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			color: #1b2433;
			position: relative;
		}
		.navbar {
			background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
			border-bottom-left-radius: var(--radius-xl);
			border-bottom-right-radius: var(--radius-xl);
			box-shadow: 0 20px 45px rgba(47, 60, 126, 0.25);
		}
		.navbar .navbar-brand, .navbar .nav-link, .navbar .navbar-text {
			color: #fff !important;
		}
		.nav-link {
			position: relative;
			font-weight: 500;
			letter-spacing: 0.2px;
			padding: 0.75rem 1rem !important;
		}
		.nav-link::after {
			content: '';
			position: absolute;
			left: 1rem;
			right: 1rem;
			bottom: 0.35rem;
			height: 2px;
			background: linear-gradient(90deg, rgba(243,148,34,0.6) 0%, rgba(255,255,255,0.8) 100%);
			transform: scaleX(0);
			transform-origin: center;
			transition: transform 0.3s ease;
		}
		.nav-link:hover::after,
		.nav-link:focus-visible::after,
		.nav-link.active::after {
			transform: scaleX(1);
		}
		.card {
			border: none;
			border-radius: 20px;
			box-shadow: 0 20px 45px rgba(31, 41, 79, 0.08);
			overflow: hidden;
		}
		.card-header {
			background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
			color: #fff;
			font-weight: 600;
			font-size: 1.1rem;
		}
		.btn-primary-soft {
			background: linear-gradient(120deg, var(--accent) 0%, #ffaf4d 100%);
			color: #fff;
			border: none;
			border-radius: 999px;
			box-shadow: 0 12px 30px rgba(243, 148, 34, 0.45);
			font-weight: 600;
			transition: transform 0.25s ease, box-shadow 0.25s ease;
		}
		.btn-primary-soft:hover,
		.btn-primary-soft:focus-visible {
			transform: translateY(-2px) scale(1.01);
			box-shadow: 0 18px 40px rgba(243, 148, 34, 0.55);
			color: #fff;
		}
		.hero-cta {
			background: rgba(255, 255, 255, 0.75);
			border-radius: 999px;
			font-weight: 600;
			transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
		}
		.hero-cta:hover,
		.hero-cta:focus-visible {
			transform: translateY(-2px);
			background: rgba(255, 255, 255, 0.95);
			box-shadow: 0 14px 35px rgba(47, 60, 126, 0.25);
		}
		.btn-outline-light {
			border-radius: 999px;
			font-weight: 500;
			transition: transform 0.25s ease;
		}
		.btn-outline-light:hover,
		.btn-outline-light:focus-visible {
			transform: translateY(-1px);
			background-color: rgba(255, 255, 255, 0.2);
			color: #fff;
		}
		.form-control:focus {
			box-shadow: 0 0 0 0.2rem rgba(47, 60, 126, 0.15);
			border-color: var(--primary);
		}
		.table {
			border-radius: 16px;
			overflow: hidden;
		}
		.table thead th {
			background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
			color: #fff;
			border-color: transparent;
			text-transform: uppercase;
			letter-spacing: 0.4px;
		}
		.table tbody tr:hover {
			background-color: rgba(47, 60, 126, 0.08);
		}
		.footer {
			background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
			color: #fff;
		}
		.text-slate {
			color: var(--slate) !important;
		}
		.hero-gradient {
			background: linear-gradient(135deg, rgba(47, 60, 126, 0.1), rgba(243, 148, 34, 0.14));
			border-radius: var(--radius-xl);
			overflow: hidden;
			position: relative;
		}
		.hero-gradient::after {
			content: '';
			position: absolute;
			inset: 0;
			background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.65), transparent 55%);
			pointer-events: none;
		}
		.badge-soft {
			background-color: rgba(243, 148, 34, 0.12);
			color: var(--accent);
			font-weight: 600;
		}
		.feature-icon {
			width: 56px;
			height: 56px;
			display: grid;
			place-items: center;
			border-radius: 18px;
			background: linear-gradient(140deg, rgba(47, 60, 126, 0.12), rgba(243, 148, 34, 0.2));
			color: var(--primary-dark);
			font-size: 1.5rem;
			transition: transform 0.3s ease;
		}
		.feature-card:hover .feature-icon {
			transform: translateY(-6px) scale(1.05);
		}
		.btn-fab {
			width: 2.5rem;
			height: 2.5rem;
			border-radius: 50%;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 1.1rem;
			transition: transform 0.25s ease, box-shadow 0.25s ease;
		}
		.btn-fab:hover {
			transform: translateY(-1px) rotate(-4deg);
			box-shadow: 0 12px 30px rgba(47, 60, 126, 0.25);
		}
		.cookie-banner {
			position: fixed;
			bottom: 1.5rem;
			right: 1.5rem;
			max-width: 360px;
			background: #ffffff;
			padding: 1.5rem;
			border-radius: 18px;
			box-shadow: 0 20px 50px rgba(31, 41, 79, 0.18);
			border: 1px solid rgba(76, 91, 122, 0.08);
			z-index: 1080;
			display: none;
		}
		.cookie-banner.show {
			display: block;
			animation: floatUp 0.4s ease-out both;
		}
		@keyframes floatUp {
			from {
				opacity: 0;
				transform: translateY(15px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
		@media (max-width: 991.98px) {
			.navbar {
				border-bottom-left-radius: 0;
				border-bottom-right-radius: 0;
			}
			.nav-link::after {
				left: 0.5rem;
				right: 0.5rem;
			}
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark mb-4">
		<div class="container">
			<a class="navbar-brand fw-semibold" href="index.php">Patient Manager</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="nav">
				<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
					<li class="nav-item">
						<a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>" href="index.php">
							<i class="bi bi-house-door me-1"></i>Home
						</a>
					</li>
					<?php if ($currentUser): ?>
						<li class="nav-item">
							<a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
								<i class="bi bi-grid-1x2 me-1"></i>Dashboard
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="create.php">
								<i class="bi bi-person-plus me-1"></i>New Patient
							</a>
						</li>
					<?php endif; ?>
					<?php if (!$currentUser): ?>
						<li class="nav-item">
							<a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'active' : ''; ?>" href="login.php">
								<i class="bi bi-box-arrow-in-right me-1"></i>Sign In
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'register.php' ? 'active' : ''; ?>" href="register.php">
								<i class="bi bi-pencil-square me-1"></i>Sign Up
							</a>
						</li>
					<?php else: ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<span class="btn btn-fab btn-outline-light border-0">
									<i class="bi bi-person-circle"></i>
								</span>
								<span class="d-lg-none d-xl-inline"><?php echo htmlspecialchars($currentUser['name']); ?></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-end shadow">
								<li><h6 class="dropdown-header text-muted text-uppercase small">Account</h6></li>
								<li><span class="dropdown-item-text text-muted"><?php echo htmlspecialchars($currentUser['email']); ?></span></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
								<li><a class="dropdown-item" href="create.php"><i class="bi bi-person-plus me-2"></i>Add Patient</a></li>
								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</a></li>
							</ul>
						</li>
					<?php endif; ?>
				</ul>
				<?php if ($currentUser): ?>
					<div class="d-none d-lg-block ms-3">
						<a class="btn btn-outline-light btn-sm px-3" href="create.php">
							<i class="bi bi-person-plus me-1"></i>New Patient
						</a>
					</div>
				<?php else: ?>
					<div class="d-none d-lg-flex ms-3 gap-2">
						<a class="btn btn-outline-light btn-sm px-3" href="login.php">Sign In</a>
						<a class="btn btn-primary-soft btn-sm px-3" href="register.php">Create Account</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</nav>
	<main class="container mb-5">


