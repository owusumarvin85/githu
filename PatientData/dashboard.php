<?php
require_once __DIR__ . '/auth.php';
requireLogin();
$user = currentUser();

$success = $_SESSION['flash_success'] ?? null;
$error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$search = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$whereClauses = ['user_id = ?'];
$params = [$user['id']];
$types = 'i';

if ($search !== '') {
	$whereClauses[] = "(name LIKE CONCAT('%', ?, '%') OR email LIKE CONCAT('%', ?, '%') OR phone LIKE CONCAT('%', ?, '%'))";
	$params[] = $search;
	$params[] = $search;
	$params[] = $search;
	$types .= 'sss';
}

$whereSql = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

// Count total
$countSql = "SELECT COUNT(*) AS total FROM patients $whereSql";
$stmt = $mysqli->prepare($countSql);
if ($search !== '') {
	$stmt->bind_param('isss', $user['id'], $search, $search, $search);
} else {
	$stmt->bind_param('i', $user['id']);
}
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();

$total = $total ?? 0;
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) {
	$page = $totalPages;
	$offset = ($page - 1) * $perPage;
}

// Fetch page data
$dataSql = "SELECT id, name, email, phone, gender, dob, address, created_at
			FROM patients
			$whereSql
			ORDER BY id DESC
			LIMIT ? OFFSET ?";
$stmt = $mysqli->prepare($dataSql);
if ($search !== '') {
	$stmt->bind_param('isssii', $user['id'], $search, $search, $search, $perPage, $offset);
} else {
	$stmt->bind_param('iii', $user['id'], $perPage, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

include __DIR__ . '/header.php';
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
	<div>
		<h2 class="h4 fw-semibold mb-1">Welcome back, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
		<p class="text-muted mb-0">You have <?php echo (int)$total; ?> patient<?php echo $total === 1 ? '' : 's'; ?> on record.</p>
	</div>
	<a class="btn btn-primary-soft px-3" href="create.php"><i class="bi bi-person-plus me-2"></i>Add Patient</a>
</div>

<?php if ($success): ?>
	<div class="alert alert-success shadow-sm"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if ($error): ?>
	<div class="alert alert-danger shadow-sm"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
	<div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
		<span class="fw-semibold"><i class="bi bi-table me-2"></i>Your Patients</span>
		<a href="create.php" class="btn btn-outline-light btn-sm">
			<i class="bi bi-plus-circle me-1"></i>New
		</a>
	</div>
	<div class="card-body">
		<form class="row g-2 mb-4" method="get" action="dashboard.php">
			<div class="col-sm-8 col-md-9">
				<div class="input-group">
					<span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
					<input type="text" class="form-control border-start-0" name="q" placeholder="Search by name, email, or phone" value="<?php echo htmlspecialchars($search); ?>">
				</div>
			</div>
			<div class="col-sm-4 col-md-3 d-grid">
				<button class="btn btn-primary-soft" type="submit">Search</button>
			</div>
		</form>

		<div class="table-responsive rounded-3">
			<table class="table table-hover align-middle mb-0">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Name</th>
						<th scope="col">Contact</th>
						<th scope="col">Gender</th>
						<th scope="col">DOB</th>
						<th scope="col">Created</th>
						<th scope="col" class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php if ($result->num_rows === 0): ?>
					<tr>
						<td colspan="7" class="text-center py-5">
							<div class="d-flex flex-column align-items-center gap-2 text-muted">
								<div class="feature-icon"><i class="bi bi-clipboard-heart"></i></div>
								<div>No patients found. Start by adding a new record.</div>
							</div>
						</td>
					</tr>
				<?php else: ?>
					<?php while ($row = $result->fetch_assoc()): ?>
						<tr>
							<td><?php echo (int)$row['id']; ?></td>
							<td class="fw-semibold"><?php echo htmlspecialchars($row['name']); ?></td>
							<td>
								<div class="d-flex flex-column">
									<span class="small"><i class="bi bi-envelope me-1 text-muted"></i><?php echo htmlspecialchars($row['email'] ?? '—'); ?></span>
									<span class="small"><i class="bi bi-telephone me-1 text-muted"></i><?php echo htmlspecialchars($row['phone'] ?? '—'); ?></span>
								</div>
							</td>
							<td><?php echo htmlspecialchars($row['gender'] ?? '—'); ?></td>
							<td><?php echo htmlspecialchars($row['dob'] ?? '—'); ?></td>
							<td><?php echo htmlspecialchars($row['created_at']); ?></td>
							<td class="text-end">
								<div class="btn-group btn-group-sm" role="group" aria-label="Actions">
									<a class="btn btn-outline-secondary" href="edit.php?id=<?php echo (int)$row['id']; ?>"><i class="bi bi-pencil"></i></a>
									<a class="btn btn-outline-danger" href="delete.php?id=<?php echo (int)$row['id']; ?>"><i class="bi bi-trash"></i></a>
								</div>
							</td>
						</tr>
					<?php endwhile; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>

		<?php if ($totalPages > 1): ?>
			<nav aria-label="Patients pagination" class="mt-4">
				<ul class="pagination justify-content-center gap-1">
					<?php
					$qParam = $search !== '' ? '&q=' . urlencode($search) : '';
					?>
					<li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
						<a class="page-link" href="?page=<?php echo max(1, $page - 1); ?><?php echo $qParam; ?>">Prev</a>
					</li>
					<?php
					$start = max(1, $page - 2);
					$end = min($totalPages, $page + 2);
					for ($i = $start; $i <= $end; $i++):
					?>
						<li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
							<a class="page-link" href="?page=<?php echo $i; ?><?php echo $qParam; ?>"><?php echo $i; ?></a>
						</li>
					<?php endfor; ?>
					<li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
						<a class="page-link" href="?page=<?php echo min($totalPages, $page + 1); ?><?php echo $qParam; ?>">Next</a>
					</li>
				</ul>
			</nav>
		<?php endif; ?>
	</div>
</div>

<?php include __DIR__ . '/footer.php'; ?>


