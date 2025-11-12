	</main>
	<footer class="footer py-3 mt-auto">
		<div class="container text-center small">
			<span>&copy; <?php echo date('Y'); ?> Patient Manager</span>
		</div>
	</footer>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<div id="cookie-banner" class="cookie-banner">
		<div class="d-flex align-items-start gap-3">
			<div class="feature-icon flex-shrink-0">
				<i class="bi bi-shield-check"></i>
			</div>
			<div>
				<h6 class="fw-semibold mb-1">We Value Your Privacy</h6>
				<p class="mb-2 text-muted small">We use essential cookies to keep your session secure and remember your preferences. You can review our cookie policy anytime from your dashboard.</p>
				<div class="d-flex gap-2">
					<button id="acceptCookies" class="btn btn-primary-soft btn-sm px-3">Accept</button>
					<a href="#cookies-info" class="btn btn-outline-secondary btn-sm px-3">Learn more</a>
				</div>
			</div>
		</div>
	</div>
	<script>
		(function () {
			const consentCookieName = 'pd_cookie_consent';
			const banner = document.getElementById('cookie-banner');
			const acceptBtn = document.getElementById('acceptCookies');

			function hasConsentCookie() {
				return document.cookie.split('; ').some(row => row.startsWith(consentCookieName + '='));
			}

			function setConsentCookie() {
				const expiry = new Date();
				expiry.setFullYear(expiry.getFullYear() + 1);
				document.cookie = consentCookieName + '=1; expires=' + expiry.toUTCString() + '; path=/; samesite=Lax';
			}

			if (!hasConsentCookie()) {
				banner.classList.add('show');
			}

			acceptBtn?.addEventListener('click', function () {
				setConsentCookie();
				banner.classList.remove('show');
			});
		})();
	</script>
</body>
</html>



