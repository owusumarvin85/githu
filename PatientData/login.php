<?php
require_once __DIR__ . '/auth.php';

if (currentUser()) {
	header('Location: dashboard.php');
	exit;
}

$redirect = $_GET['redirect'] ?? '';
if (!is_string($redirect) || preg_match('/^https?:\/\//i', $redirect)) {
	$redirect = '';
}

$errors = [];
$values = [
	'email' => '',
	'remember' => false,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$token = $_POST['csrf_token'] ?? '';
	if (!verifyCsrfToken($token)) {
		$errors[] = 'Invalid request token.';
	} else {
		$postRedirect = $_POST['redirect'] ?? '';
		if (!is_string($postRedirect) || preg_match('/^https?:\/\//i', $postRedirect)) {
			$postRedirect = '';
		}
		$redirect = $postRedirect !== '' ? $postRedirect : $redirect;

		$values['email'] = trim($_POST['email'] ?? '');
		$password = $_POST['password'] ?? '';
		$values['remember'] = isset($_POST['remember']);

		if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Please enter a valid email address.';
		}
		if ($password === '') {
			$errors[] = 'Please enter your password.';
		}

		if (!$errors) {
			$stmt = $mysqli->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ?");
			$stmt->bind_param('s', $values['email']);
			$stmt->execute();
			$user = $stmt->get_result()->fetch_assoc();
			$stmt->close();

			if (!$user || !password_verify($password, $user['password_hash'])) {
				$errors[] = 'Incorrect email or password.';
			} else {
				finishLogin((int)$user['id'], $values['remember']);
				$_SESSION['flash_success'] = 'Welcome back, ' . $user['name'] . '!';
				$destination = $redirect !== '' ? $redirect : 'dashboard.php';
				header('Location: ' . $destination);
				exit;
			}
		}
	}
}

include __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
	<div class="col-lg-5">
		<div class="card">
			<div class="card-header">
				<i class="bi bi-box-arrow-in-right me-2"></i>Sign In
			</div>
			<div class="card-body p-4">
				<p class="text-muted mb-4">Access your personalised dashboard, manage patient records, and pick up right where you left off.</p>
				<?php if ($errors): ?>
					<div class="alert alert-danger">
						<ul class="mb-0">
							<?php foreach ($errors as $error): ?>
								<li><?php echo htmlspecialchars($error); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<form method="post" novalidate>
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken()); ?>">
					<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
					<div class="mb-3">
						<label class="form-label fw-semibold" for="email">Email</label>
						<div class="input-group">
							<span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope"></i></span>
							<input type="email" class="form-control border-start-0" id="email" name="email" required value="<?php echo htmlspecialchars($values['email']); ?>" placeholder="you@clinic.com">
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label fw-semibold" for="password">Password</label>
						<div class="input-group">
							<span class="input-group-text bg-white border-end-0"><i class="bi bi-lock"></i></span>
							<input type="password" class="form-control border-start-0" id="password" name="password" required placeholder="Your secure password">
						</div>
					</div>
					<div class="d-flex justify-content-between align-items-center mb-4">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" id="remember" name="remember" <?php echo $values['remember'] ? 'checked' : ''; ?>>
							<label class="form-check-label small" for="remember">Remember me on this device</label>
						</div>
						<a href="register.php" class="small text-decoration-none">Need an account?</a>
					</div>
					<div class="d-grid gap-3">
						<button type="submit" class="btn btn-primary-soft py-2">Sign in securely</button>
						<a href="index.php" class="btn btn-outline-secondary py-2"><i class="bi bi-arrow-left me-2"></i>Back to home</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/footer.php'; ?>


