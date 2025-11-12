<?php
require_once __DIR__ . '/auth.php';
requireLogin();
$user = currentUser();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
	$_SESSION['flash_error'] = 'Invalid patient id.';
	header('Location: dashboard.php');
	exit;
}

// Load record to show confirmation
$stmt = $mysqli->prepare("SELECT id, name, email FROM patients WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $id, $user['id']);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$patient) {
	$_SESSION['flash_error'] = 'Patient not found.';
	header('Location: dashboard.php');
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$token = $_POST['csrf_token'] ?? '';
	if (!verifyCsrfToken($token)) {
		$_SESSION['flash_error'] = 'Invalid request token.';
		header('Location: dashboard.php');
		exit;
	}
	$stmt = $mysqli->prepare("DELETE FROM patients WHERE id = ? AND user_id = ?");
	$stmt->bind_param('ii', $id, $user['id']);
	if ($stmt->execute()) {
		$_SESSION['flash_success'] = 'Patient deleted.';
	} else {
		$_SESSION['flash_error'] = 'Failed to delete patient.';
	}
	header('Location: dashboard.php');
	exit;
}
?>
<?php include __DIR__ . '/header.php'; ?>

<div class="card">
	<div class="card-header fw-semibold">Delete Patient</div>
	<div class="card-body">
		<p>Are you sure you want to delete the patient <strong><?php echo htmlspecialchars($patient['name']); ?></strong> (ID: <?php echo (int)$patient['id']; ?>)?</p>
		<form method="post">
			<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(getCsrfToken()); ?>">
			<div class="d-flex gap-2">
				<button type="submit" class="btn btn-danger">Yes, Delete</button>
				<a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
			</div>
		</form>
	</div>
</div>

<?php include __DIR__ . '/footer.php'; ?>


