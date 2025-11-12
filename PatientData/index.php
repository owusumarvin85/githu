<?php
require_once __DIR__ . '/auth.php';
$currentUser = currentUser();
include __DIR__ . '/header.php';
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>

<?php if ($flashSuccess): ?>
	<div class="alert alert-success shadow-sm"><?php echo htmlspecialchars($flashSuccess); ?></div>
<?php endif; ?>
<?php if ($flashError): ?>
	<div class="alert alert-danger shadow-sm"><?php echo htmlspecialchars($flashError); ?></div>
<?php endif; ?>

<section class="hero-gradient p-5 p-md-5 position-relative overflow-hidden mb-5">
	<div class="row align-items-center">
		<div class="col-lg-6">
			<span class="badge badge-soft mb-3">
				<i class="bi bi-heart-pulse me-2"></i>Trusted patient data platform
			</span>
			<h1 class="display-5 fw-bold mb-3">Organise patient insights with confidence and clarity.</h1>
			<p class="lead text-muted mb-4">Patient Manager keeps every record in sync, offers personalised dashboards, and protects your data with enterprise-grade security.</p>
			<div class="d-flex flex-wrap gap-3">
				<a class="btn btn-primary-soft px-4 py-2" href="<?php echo $currentUser ? 'dashboard.php' : 'register.php'; ?>">
					<i class="bi bi-rocket-takeoff-fill me-2"></i><?php echo $currentUser ? 'Open your dashboard' : 'Get started free'; ?>
				</a>
				<a class="btn btn-outline-light text-dark border-0 px-4 py-2 hero-cta" href="#features">
					<i class="bi bi-play-circle me-2"></i>See how it works
				</a>
			</div>
			<div class="d-flex gap-4 align-items-center mt-4">
				<div class="d-flex align-items-center gap-2">
					<i class="bi bi-shield-lock-fill text-primary fs-4"></i>
					<div>
						<small class="d-block text-muted">Secure by design</small>
						<span class="fw-semibold text-slate">GDPR-friendly & cookies aware</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 mt-5 mt-lg-0">
			<div id="homeCarousel" class="carousel slide shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
				<div class="carousel-inner">
					<div class="carousel-item active">
						<img src="https://images.unsplash.com/photo-1527613426441-4da17471b66d?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Healthcare team collaborating">
						<div class="carousel-caption text-start">
							<h5 class="fw-semibold">Collaborative care</h5>
							<p class="small">Share dashboards and insights with your team instantly.</p>
						</div>
					</div>
					<div class="carousel-item">
						<img src="https://images.unsplash.com/photo-1584982751601-97dcc096659c?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Doctor reviewing data">
						<div class="carousel-caption text-start">
							<h5 class="fw-semibold">Real-time updates</h5>
							<p class="small">Track patient progress and visits with powerful analytics.</p>
						</div>
					</div>
					<div class="carousel-item">
						<img src="https://images.unsplash.com/photo-1517148815978-75f6acaaf32c?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Secure technology">
						<div class="carousel-caption text-start">
							<h5 class="fw-semibold">Security-first</h5>
							<p class="small">Encrypted authentication, cookie consent, and session controls.</p>
						</div>
					</div>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>
		</div>
	</div>
</section>

<section id="features" class="mb-5">
	<div class="row g-4">
		<div class="col-md-4">
			<div class="p-4 bg-white rounded-4 feature-card h-100 shadow-sm">
				<div class="feature-icon mb-3"><i class="bi bi-journal-check"></i></div>
				<h5 class="fw-semibold mb-2">Smart patient registry</h5>
				<p class="text-muted mb-0">Create, edit, and organise patient profiles with guided forms and inline validation built for healthcare workflows.</p>
			</div>
		</div>
		<div class="col-md-4">
			<div class="p-4 bg-white rounded-4 feature-card h-100 shadow-sm">
				<div class="feature-icon mb-3"><i class="bi bi-people"></i></div>
				<h5 class="fw-semibold mb-2">Personal dashboards</h5>
				<p class="text-muted mb-0">Every team member gets their own dashboard tailored to their patients, complete with quick actions and insights.</p>
			</div>
		</div>
		<div class="col-md-4">
			<div class="p-4 bg-white rounded-4 feature-card h-100 shadow-sm">
				<div class="feature-icon mb-3"><i class="bi bi-cookie"></i></div>
				<h5 class="fw-semibold mb-2">Privacy-ready cookies</h5>
				<p class="text-muted mb-0">Offer transparent cookie consent, secure sessions, and optional remember-me tokens with a single click.</p>
			</div>
		</div>
	</div>
</section>

<section class="mb-5">
	<div class="row g-4 align-items-center">
		<div class="col-lg-6">
			<div class="p-4 p-lg-5 bg-white rounded-4 shadow-sm h-100">
				<h4 class="fw-semibold mb-3">Everything you need to stay ahead</h4>
				<ul class="list-unstyled d-grid gap-3">
					<li class="d-flex gap-3 align-items-start">
						<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill"></i></span>
						<div>
							<span class="fw-semibold d-block">Cookie-aware authentication</span>
							<small class="text-muted">Keep users signed in securely with session & remember-me tokens guarded by hashed cookies.</small>
						</div>
					</li>
					<li class="d-flex gap-3 align-items-start">
						<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill"></i></span>
						<div>
							<span class="fw-semibold d-block">Hover-perfect buttons</span>
							<small class="text-muted">Delight users with micro-interactions every time they hover over actionable elements.</small>
						</div>
					</li>
					<li class="d-flex gap-3 align-items-start">
						<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill"></i></span>
						<div>
							<span class="fw-semibold d-block">Icon-first navigation</span>
							<small class="text-muted">Guide users effortlessly with iconography matched to every action and section.</small>
						</div>
					</li>
				</ul>
				<div class="mt-4 d-flex gap-3">
					<a href="<?php echo $currentUser ? 'dashboard.php' : 'register.php'; ?>" class="btn btn-primary-soft px-4">
						<?php echo $currentUser ? 'Manage patients' : 'Create your space'; ?>
					</a>
					<a href="<?php echo $currentUser ? 'create.php' : 'login.php'; ?>" class="btn btn-outline-secondary px-4">
						<i class="bi bi-arrow-right-circle me-2"></i><?php echo $currentUser ? 'Add new patient' : 'Sign in'; ?>
					</a>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="row g-4">
				<div class="col-sm-6">
					<div class="p-4 bg-white rounded-4 shadow-sm text-center h-100">
						<div class="feature-icon mx-auto mb-3"><i class="bi bi-speedometer"></i></div>
						<h6 class="fw-semibold mb-1">Realtime dashboards</h6>
						<p class="text-muted small mb-0">Filter records by tags, status, and follow-up dates instantly.</p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="p-4 bg-white rounded-4 shadow-sm text-center h-100">
						<div class="feature-icon mx-auto mb-3"><i class="bi bi-bell"></i></div>
						<h6 class="fw-semibold mb-1">Smart reminders</h6>
						<p class="text-muted small mb-0">Stay on top of follow-up appointments and lab alerts.</p>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="p-4 bg-white rounded-4 shadow-sm text-center h-100">
						<div class="feature-icon mx-auto mb-3"><i class="bi bi-layers"></i></div>
						<h6 class="fw-semibold mb-1">Modular architecture</h6>
						<p class="text-muted small mb-0">Extend modules for analytics, teleconsults, or billing in a snap.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="mb-5 bg-white rounded-4 shadow-sm p-4 p-lg-5">
	<div class="row align-items-center g-4">
		<div class="col-lg-7">
			<h4 class="fw-semibold mb-3">Built for teams that care deeply about patient experiences.</h4>
			<p class="text-muted mb-4">From independent clinics to large healthcare organisations, Patient Manager keeps sensitive information secure, accessible, and actionable with a delightful interface.</p>
			<div class="d-flex flex-wrap gap-4">
				<div>
					<span class="h3 fw-bold text-primary d-block">99.9%</span>
					<small class="text-muted">Uptime & availability</small>
				</div>
				<div>
					<span class="h3 fw-bold text-primary d-block">3x</span>
					<small class="text-muted">Faster patient onboarding</small>
				</div>
				<div>
					<span class="h3 fw-bold text-primary d-block">24/7</span>
					<small class="text-muted">Support-ready platform</small>
				</div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="p-4 bg-gradient rounded-4 text-white" style="background: linear-gradient(135deg, #2F3C7E, #4F5ABF);">
				<h5 class="fw-semibold mb-3"><i class="bi bi-chat-dots me-2"></i>What our users say</h5>
				<div class="small fst-italic">"Patient Manager transformed how our clinicians collaborate. The dashboards feel tailored, the hover interactions make the interface lively, and the cookie consent gave us compliance peace of mind."</div>
				<div class="d-flex align-items-center gap-2 mt-4">
					<div class="btn btn-fab btn-outline-light border-0">
						<i class="bi bi-person-heart"></i>
					</div>
					<div>
						<span class="fw-semibold d-block">Dr. Marvin Baafi Owusu</span>
						<small class="text-light">CareOne Clinics</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="mb-5 text-center">
	<h4 class="fw-semibold mb-3">Ready to elevate your patient data experience?</h4>
	<p class="text-muted mb-4">Sign up in minutes, invite colleagues, and deliver exceptional care with informed decisions.</p>
	<div class="d-flex flex-wrap justify-content-center gap-3">
		<a href="<?php echo $currentUser ? 'dashboard.php' : 'register.php'; ?>" class="btn btn-primary-soft px-4">Start now</a>
		<a href="<?php echo $currentUser ? 'create.php' : 'login.php'; ?>" class="btn btn-outline-secondary px-4">Take a tour</a>
	</div>
</section>

<?php include __DIR__ . '/footer.php'; ?>
