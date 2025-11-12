<?php
require_once __DIR__ . '/auth.php';

if (currentUser()) {
	header('Location: dashboard.php');
	exit;
}

$errors = [];
$values = [
	'name' => '',
	'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$token = $_POST['csrf_token'] ?? '';
	if (!verifyCsrfToken($token)) {
		$errors[] = 'Invalid request token.';
	} else {
		$values['name'] = trim($_POST['name'] ?? '');
		$values['email'] = trim($_POST['email'] ?? '');
		$password = $_POST['password'] ?? '';
		$confirm = $_POST['confirm_password'] ?? '';

		if ($values['name'] === '' || mb_strlen($values['name']) < 2) {
			$errors[] = 'Please provide your name.';
		}
		if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Please enter a valid email address.';
		}
		if (mb_strlen($password) < 8) {
			$errors[] = 'Password must be at least 8 characters.';
		}
		if ($password !== $confirm) {
			$errors[] = 'Passwords do not match.';
		}

		if (!$errors) {
			$stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
			$stmt->bind_param('s', $values['email']);
			$stmt->execute();
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$errors[] = 'An account already exists with that email.';
			}
			$stmt->close();
		}

		if (!$errors) {
			$passwordHash = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $mysqli->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
			$stmt->bind_param('sss', $values['name'], $values['email'], $passwordHash);
			if ($stmt->execute()) {
				$userId = $stmt->insert_id;
				$stmt->close();
				$_SESSION['flash_success'] = 'Account created successfully. Welcome aboard!';
				finishLogin((int)$userId, true);
				header('Location: dashboard.php');
				exit;
			}
			$stmt->close();
			$errors[] = 'Failed to create account. Please try again.';
		}
	}
}

include __DIR__ . '/header.php';
?>

<div class="row justify-content-center">
	<div class="col-lg-6">
		<div class="card">
			<div class="card-header">
				<i class="bi bi-pencil-square me-2"></i>Create your free account
			</div>
			<div class="card-body p-4">
				<p class="text-muted mb-4">Unlock secure dashboards, patient collaboration, and smart cookie-aware authentication in minutes.</p>
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
					<div class="mb-3">
						<label class="form-label fw-semibold" for="name">Full name</label>
						<div class="input-group">
							<span class="input-group-text bg-white border-end-0"><i class="bi bi-person"></i></span>
							<input type="text" class="form-control border-start-0" id="name" name="name" required value="<?php echo htmlspecialchars($values['name']); ?>" placeholder="Dr. Jane Doe">
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label fw-semibold" for="email">Work email</label>
						<div class="input-group">
							<span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope"></i></span>
							<input type="email" class="form-control border-start-0" id="email" name="email" required value="<?php echo htmlspecialchars($values['email']); ?>" placeholder="you@clinic.com">
						</div>
					</div>
					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label fw-semibold" for="password">Password</label>
							<div class="input-group">
								<span class="input-group-text bg-white border-end-0"><i class="bi bi-lock"></i></span>
								<input type="password" class="form-control border-start-0" id="password" name="password" required placeholder="Min. 8 characters">
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label fw-semibold" for="confirm_password">Confirm password</label>
							<div class="input-group">
								<span class="input-group-text bg-white border-end-0"><i class="bi bi-lock-fill"></i></span>
								<input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" required placeholder="Repeat password">
							</div>
						</div>
					</div>
					<div class="form-text text-muted mt-2">By creating an account you consent to our essential cookie usage and agree to receive service updates.</div>
					<div class="d-grid gap-3 mt-4">
						<button type="submit" class="btn btn-primary-soft py-2">Create account</button>
						<a href="login.php" class="btn btn-outline-secondary py-2">Already have an account? Sign in</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/footer.php'; ?>


