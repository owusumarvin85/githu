<?php
require_once __DIR__ . '/auth.php';
requireLogin();
$user = currentUser();

$errors = [];
$values = [
	'name' => '',
	'email' => '',
	'phone' => '',
	'gender' => '',
	'dob' => '',
	'address' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$token = $_POST['csrf_token'] ?? '';
	if (!verifyCsrfToken($token)) {
		$errors[] = 'Invalid request token.';
	} else {
		$values['name'] = trim($_POST['name'] ?? '');
		$values['email'] = trim($_POST['email'] ?? '');
		$values['phone'] = trim($_POST['phone'] ?? '');
		$values['gender'] = trim($_POST['gender'] ?? '');
		$values['dob'] = trim($_POST['dob'] ?? '');
		$values['address'] = trim($_POST['address'] ?? '');

		if ($values['name'] === '') $errors[] = 'Name is required.';
		if ($values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email is invalid.';
		if ($values['gender'] !== '' && !in_array($values['gender'], ['Male','Female','Other'], true)) $errors[] = 'Gender is invalid.';
		if ($values['dob'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $values['dob'])) $errors[] = 'DOB must be in YYYY-MM-DD format.';

		// If there are no validation errors, the INSERT will run below after rendering.
		if (!$errors) { /* handled after rendering */ }
	}
}
?>
<?php include __DIR__ . '/header.php'; ?>

<div class="card">
	<div class="card-header fw-semibold">Add Patient</div>
	<div class="card-body">
		<?php if ($errors): ?>
			<div class="alert alert-danger">
				<ul class="mb-0">
					<?php foreach ($errors as $e): ?>
						<li><?php echo htmlspecialchars($e); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<form method="post" novalidate>
			<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken()); ?>">
			<div class="row g-3">
				<div class="col-md-6">
					<label class="form-label">Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($values['name']); ?>" required>
				</div>
				<div class="col-md-6">
					<label class="form-label">Email</label>
					<input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($values['email']); ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label">Phone</label>
					<input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($values['phone']); ?>">
				</div>
				<div class="col-md-6">
					<label class="form-label">Gender</label>
					<select class="form-select" name="gender">
						<option value="">-- Select --</option>
						<?php
						$genders = ['Male','Female','Other'];
						foreach ($genders as $g):
						?>
							<option value="<?php echo $g; ?>" <?php echo $values['gender']===$g?'selected':''; ?>><?php echo $g; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="form-label">Date of Birth</label>
					<input type="date" class="form-control" name="dob" value="<?php echo htmlspecialchars($values['dob']); ?>">
				</div>
				<div class="col-12">
					<label class="form-label">Address</label>
					<textarea class="form-control" name="address" rows="3"><?php echo htmlspecialchars($values['address']); ?></textarea>
				</div>
			</div>
			<div class="d-flex gap-2 mt-4">
				<button class="btn btn-primary-soft" type="submit">Save</button>
				<a class="btn btn-outline-secondary" href="dashboard.php">Cancel</a>
			</div>
		</form>
	</div>
</div>

<?php
// Finish server-side after rendering to keep logic simple in a single file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
	$sql = "INSERT INTO patients (user_id, name, email, phone, gender, dob, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = $mysqli->prepare($sql);
	$stmt->bind_param(
		'issssss',
		$user['id'],
		$values['name'],
		$values['email'],
		$values['phone'],
		$values['gender'],
		$values['dob'],
		$values['address']
	);
	if ($stmt->execute()) {
		$_SESSION['flash_success'] = 'Patient created successfully.';
		header('Location: dashboard.php');
		exit;
	} else {
		$_SESSION['flash_error'] = 'Failed to create patient.';
	}
}
?>

<?php include __DIR__ . '/footer.php'; ?>



