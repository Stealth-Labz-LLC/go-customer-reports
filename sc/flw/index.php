<?php
// Capture query string if it exists
//$queryParams = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';

// Set the CTA link to the external Evergreen Botanicals page
//$ctaLink = 'https://buy.evergreen-botanicals.com/rd-hs-1/' . $queryParams;
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Solar Cancellation Resource Center | Learn More</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
	<link rel="stylesheet" href="style-solar.css" />
	<link rel="stylesheet" href="modal-style.css?v=<?= time() ?>">
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />
	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-5NRH7CBL');
	</script>
	<!-- End Google Tag Manager -->
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NRH7CBL"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<header class="site-header">
		<div class="container text-center">
			<img src="images/solar-police-logo.png" alt="Company Logo" class="logo mx-auto d-block" />
		</div>
	</header>
	<div class="wrapper">
		<div class="main">
			<!-- Intro Slide -->
			<div class="quiz-slide intro-slide">
				<h3 class="mb-2">Some Solar Contracts Can Be Cancelled Right Now!</h3>
				<p class="text-sub-blue">Due to changes in consumer protection regulations you could cancel your solar contract. <strong>Check eligibility now for free!</strong></p>
				<h2 class="pt-3 pb-2">Were you lied to or misled in anyway during the solar sales process?</h2>
				<button class="start-btn btn btn-dark answer-tight" data-next="question-1" data-progress="20">YES</button>
				<button class="next-btn btn btn-dark answer-tight" data-next="question-1" data-progress="20">NO</button>
				<div>
					<img loading="lazy" class="trust-logos-mob" src="images/as-seen-on-mob.png" alt="">
				</div>
			</div>
			<!-- Question 1 -->
			<div class="quiz-slide hidden" id="question-1">
				<div class="container">
					<div class="row mb-1">
						<div class="col">
							<h2>Do you lease or own your solar panels?</h2>
						</div>
					</div>
					<div class="row pb-4 pb-md-3">
						<div class="col text-sub-blue">In both situations, you have options!</div>
					</div>
					<div class="row">
						<div class="col d-grid gap-1">
							<button class="start-btn btn btn-dark answer-tight" data-next="question-2" data-progress="60" data-status="Pre-Approved">Own</button>
							<button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="60" data-status="Pre-Approved">Lease</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Question 2 -->
			<div class="quiz-slide hidden" id="question-2">
				<div class="container">
					<div class="row mb-1">
						<div class="col">
							<h2>What started the solar sales process for you?</h2>
						</div>
					</div>
					<div class="row pb-4 pb-md-3">
						<div class="col text-sub-blue">Even if you don't remember the specifics, we can still help.</div>
					</div>
					<div class="row">
						<div class="col d-grid gap-1">
							<button class="start-btn btn btn-dark answer-tight" data-next="question-3" data-progress="80" data-status="Approved">Door-to-door salesperson</button>
							<button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="80" data-status="Approved">I was cold-called</button>
							<button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="80" data-status="Approved">Other</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Question 3 -->
			<div class="quiz-slide hidden" id="question-3">
				<div class="container">
					<div class="row mb-1">
						<div class="col">
							<h2>Has your solar system become an unwanted financial or emotional burden?</h2>
						</div>
					</div>
					<div class="row pb-4 pb-md-3">
						<div class="col text-sub-blue">You aren't alone, we're here to help!</div>
					</div>
					<div class="row">
						<div class="col d-grid gap-1">
							<button class="start-btn btn btn-dark answer-tight" data-next="question-4" data-progress="100" data-status="Congratulations">Yes</button>
							<button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="100" data-status="Congratulations">No</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Question 4 -->
			<div class="quiz-slide hidden" id="question-4">
				<div class="container">
					<div class="row mb-1">
						<div class="col">
							<h2>Are you ready for the possibility of being solar-free again?</h2>
						</div>
					</div>
					<div class="row pb-4 pb-md-3">
						<div class="col text-sub-blue">This could be the solution you’ve been waiting for — don’t stop now.</div>
					</div>
					<div class="row">
						<div class="col d-grid gap-1">
							<button class="start-btn btn btn-success answer-tight" data-next="cta-slide">Yes, please help me!</button>
							<button class="next-btn btn btn-outline-secondary answer-tight" data-next="cta-slide">No, I'm happy with my solar</button>
						</div>
					</div>
				</div>
			</div>

			<!-- CTA Slide -->
			<div class="quiz-slide hidden" id="cta-slide">
				<div class="container">
					<div class="row mb-2">
						<div class="col">
							<h2 class="congrats">🎉 Congratulations! 🎉</h2>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col">
							<p class="narrow">Based on your answers, you are likely eligible to <strong>end your solar contract</strong>. You may even <em>receive a settlement</em> from the solar sales company for gross misrepresentation.</p>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-lg-10 col-xl-10">
							<form id="leadForm">
								<div class="row">
									<div class="col-md-12 mb-2">
										<input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName" required>
									</div>
									<div class="col-md-12 mb-2">
										<input type="text" class="form-control" id="lastName" placeholder="Last Name" name="lastName" required>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 mb-2">
										<input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
									</div>
									<div class="col-md-12 mb-3">
										<input type="tel" class="form-control" id="phone" placeholder="Phone Number" name="phone" required>
									</div>
								</div>
								<div class="row">
									<div class="col-12 mb-1">
										<button type="submit" class="cta-button">CLAIM MY FREE CONSULTATION NOW</button>
									</div>
								</div>
							</form>
						</div>
						<span class="applied"><i class="bi bi-lock-fill"></i> Your information is secure and confidential.</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="trust-section container mt-1">
		<div class="row align-items-center">

			<!-- Left Column: Image -->
			<div class="col-md-2 text-center">
				<img src="images/mark-m.png" alt="Mark R." class="img-fluid rounded" />
			</div>

			<!-- Right Column: Testimonial -->
			<div class="col-md-10">
				<blockquote class="blockquote">
					<p class="mb-3 trust-quote">"I felt trapped in my solar contract and didn’t know where to turn. The free consultation explained all my options and <strong>outlined the path to being solar-free again</strong>.”</p>
				</blockquote>
				<footer class="blockquote-footer"><cite title="Source Title"><strong>Mark R.</strong>, Phoenix AZ</cite></footer>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<img loading="lazy" class="hipaa" src="images/as-seen-on.png" alt="">
		</div>
	</div>
	<footer class="site-footer">
		<!--<p><img loading="lazy" class="hipaa" src="images/as-seen-on.png" alt=""></p>-->
		<div class="security-note">
			<img src="images/lock.png" alt="Lock icon" />
			<span>256-BIT TLS SECURITY</span>

		</div>
		<ul class="footer-links brkLi">
			<li>
				<a href="javascript:void(0);" onclick="javascript:openNewWindow('page-contact.php','modal');" data-bs-toggle="modal">Contact Us</a>
				<a href="javascript:void(0);" onclick="javascript:openNewWindow('page-privacy.php','modal');" data-bs-toggle="modal">Privacy Policy</a>
				<a href="javascript:void(0);" onclick="javascript:openNewWindow('page-terms.php','modal');" data-bs-toggle="modal">Terms And Conditions</a>
				<a href="javascript:void(0);" onclick="javascript:openNewWindow('page-disclosure.php','modal');" data-bs-toggle="modal">Disclosure</a>
			</li>
		</ul>
		<p class="copyright">&copy; 2025 Consumer Reports, LLC. All rights reserved.</p>
	</footer>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/custom.js"></script>

	<script>
		// Countdown

		// const discountText = document.getElementById("discount-count");
		// let count = 23;
		// const interval = setInterval(() => {
		// 	count--;
		// 	if (count <= 21) clearInterval(interval);
		// 	discountText.innerHTML = `Only <span>${count} free consultations</span> left today!`;
		// }, 2500);



		// Slide navigation
		document.querySelectorAll('.next-btn, .start-btn').forEach(button => {
			button.addEventListener('click', () => {
				// grab the nearest ".quiz-slide" parent
				const currentSlide = button.closest('.quiz-slide');
				const nextId = button.dataset.next;
				const progress = button.dataset.progress;
				const status = button.dataset.status;
				const nextSlide = document.getElementById(nextId);

				// hide current
				currentSlide.classList.add('hidden');

				if (nextSlide) {
					nextSlide.classList.remove('hidden');
					const fill = nextSlide.querySelector('.progress-bar-fill');
					const statusText = nextSlide.querySelector('.status-text');

					if (fill && progress) fill.style.width = progress + '%';
					if (statusText && status) statusText.textContent = status;
				}

				window.scrollTo({
					top: 0,
					behavior: 'smooth'
				});
			});
		});
	</script>



</body>

</html>
