<?php
// Capture query string if it exists
$queryParams = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';

// Set the CTA link to the external Evergreen Botanicals page
$ctaLink = 'https://sundayscaries.com/products/delta-9-gummies?utm_source=stlnwk&utm_medium=quiz-me&discount=CHILL15' . $queryParams;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="robots" content="noindex, nofollow">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>The Calm Institute & Resource Center | Free Information</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
	<link rel="stylesheet" href="style-scaries.css" />
	<link rel="stylesheet" href="modal-style.css?v=<?= time() ?>">
	<link rel="shortcut icon" href="images/favicon-calm.png" type="image/x-icon" />
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
			<img src="images/the-calm-institute-logo.png" alt="Company Logo" class="logo mx-auto d-block" />
		</div>
	</header>
	<div class="wrapper">
			<div class="main">
				<!-- Intro Slide -->
				<div class="quiz-slide intro-slide">
					<h2 class="mb-2">Finally: The Natural Solution That Helps Men Perform With Confidence</h2>
					<p class="text-sub-blue">You could qualify for a one-time, limited time discount of <strong>up-to 40% off</strong> this all-natural breakthrough</p>
					<p class="discount-note" id="discount-count">Only <span>21 discounts</span> left today!</p>
					<button class="start-btn btn btn-dark answer-tight" data-next="question-1" data-progress="20">GET STARTED</button>
				</div>
				<!-- Question 1 -->
				<div class="quiz-slide hidden" id="question-1">
					<div class="container">
						<div class="row mb-1">
							<div class="col">
								<h2>What affects your confidence most in intimate situations?</h2>
							</div>
						</div>
						<div class="row pb-4 pb-md-3">
							<div class="col text-sub-blue">Select what holds you back the most.</div>
						</div>
						<div class="row">
							<div class="col d-grid gap-1">
								<button class="start-btn btn btn-dark answer-tight" data-next="question-2" data-progress="60" data-status="Pre-Approved">Racing thoughts and overthinking</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="60" data-status="Pre-Approved">Physical tension and stress</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="60" data-status="Pre-Approved">Worry about disappointing my partner</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="60" data-status="Pre-Approved">General anxiety kills the mood</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Question 2 -->
				<div class="quiz-slide hidden" id="question-2">
					<div class="container">
						<div class="row mb-1">
							<div class="col">
								<h2>How does stress show up during intimate moments?</h2>
							</div>
						</div>
						<div class="row pb-4 pb-md-3">
							<div class="col text-sub-blue">Choose your most common experience.</div>
						</div>
						<div class="row">
							<div class="col d-grid gap-1">
								<button class="start-btn btn btn-dark answer-tight" data-next="question-3" data-progress="80" data-status="Approved">My mind won't quiet down</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="80" data-status="Approved">I get physically tense and can't relax</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="80" data-status="Approved">I worry about my performance</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="80" data-status="Approved">I avoid intimacy altogether</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Question 3 -->
				<div class="quiz-slide hidden" id="question-3">
					<div class="container">
						<div class="row mb-1">
							<div class="col">
								<h2>What have you tried to reduce sexual anxiety?</h2>
							</div>
						</div>
						<div class="row pb-4 pb-md-3">
							<div class="col text-sub-blue">What's your current approach?</div>
						</div>
						<div class="row">
							<div class="col d-grid gap-1">
								<button class="start-btn btn btn-dark answer-tight" data-next="question-4" data-progress="100" data-status="Congratulations">Nothing really works for me</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="100" data-status="Congratulations">A drink to calm my nerves</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="100" data-status="Congratulations">Trying to "power through" it</button>
								<button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="100" data-status="Congratulations">Avoiding the situation entirely</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Question 4 -->
				<div class="quiz-slide hidden" id="question-4">
					<div class="container">
						<div class="row mb-1">
							<div class="col">
								<h2>Are you ready to feel relaxed and confident in bed?</h2>
							</div>
						</div>
						<div class="row pb-4 pb-md-3">
							<div class="col text-sub-blue">Natural stress relief could help you be fully present with your partner.</div>
						</div>
						<div class="row">
							<div class="col d-grid gap-1">
								<button class="start-btn btn btn-success answer-tight" data-next="loading-slide">Yes, I want that confidence back</button>
								<button class="next-btn btn btn-outline-secondary answer-tight" data-next="loading-slide">No, I'll figure it out myself</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Loading Slide -->
				<div class="quiz-slide hidden" id="loading-slide">
	    	<div class="container text-center">
	        <div class="row mb-3">
	            <div class="col">
	                <h2>Analyzing Your Responses...</h2>
	            </div>
	        </div>
	        <div class="row mb-3">
	            <div class="col">
	                <img src="images/loading.gif" alt="Loading..." class="loading-gif">
	            </div>
	        </div>
					<div class="row mb-3">
	            <div class="col">
	               <h3>Matching your profile with available offers and solutions...</h3>
	            </div>
	        </div>
	    </div>
			</div>
				<!-- CTA Slide -->
				<div class="quiz-slide hidden" id="cta-slide">
					<div class="container">
						<div class="row mb-2"><div class="col"><h2 class="congrats">🎉 Congratulations! 🎉</h2></div></div>
						<div class="row mb-1"><div class="col">
						<p class="narrow">You've been selected to receive a <span class="discount-text">15% off discount</span>!</p>
						</div></div>
						<div class="row mb-1"><div class="col">
						<a href="<?php echo $ctaLink; ?>"><img src="images/1-bottle-sunday-scaries-gummy.png" alt="1 Bottle Discount" class="img-fluid" /></a>
						</div></div>
						<div class="row mb-1"><div class="col">
						<a href="<?php echo $ctaLink; ?>"><img src="images/sunday-scaries-badges.svg" alt="1 Bottle Discount" class="img-fluid badges-svg" /></a>
						</div></div>
						<div class="row mb-1">
						<div class="col"><a href="<?php echo $ctaLink; ?>"><div class="coupon-box"><span>Code:</span> CHILL15</div></a></div></div>
						<div class="row">
						<div class="col">
						<a href="<?php echo $ctaLink; ?>" class="cta-button">CLAIM MY DISCOUNT NOW</a>
						</div>
						<div><span class="applied">Applied automatically on next page!</span></div>
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
					<p class="mb-3 trust-quote">"My marriage was losing it's spark and I was feeling depressed.  I completed the simple form above and let's say the result is <strong>the most sex I've had since my 20s</strong>.”</p>
				</blockquote>
				<footer class="blockquote-footer"><cite title="Source Title">Mark R., Phoenix AZ</cite></footer>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<img loading="lazy" class="hipaa hide-tablet-down" src="images/as-seen-on-09-25.png" alt="">
      <img loading="lazy" class="hipaa show-tablet-down" src="images/as-seen-on-09-25-mobile.png" alt="">
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
		<p class="copyright">&copy; 2025 Customer Reports, LLC. All rights reserved.</p>
	</footer>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/custom.js"></script>

	<script>
	// Countdown
	const discountText = document.getElementById("discount-count");
	let count = 23;
	const interval = setInterval(() => {
		count--;
		if (count <= 21) clearInterval(interval);
		discountText.innerHTML = `Only <span>${count} discounts</span> left today!`;
	}, 2500);

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

	// Auto-advance from loading screen after 3 seconds
	function showSlide(slideId) {
		document.querySelectorAll('.quiz-slide').forEach(slide => {
			slide.classList.add('hidden');
		});
		document.getElementById(slideId).classList.remove('hidden');
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	}

	// Check for loading slide and auto-advance
	const observer = new MutationObserver(function(mutations) {
		mutations.forEach(function(mutation) {
			if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
				const loadingSlide = document.getElementById('loading-slide');
				if (loadingSlide && !loadingSlide.classList.contains('hidden')) {
					setTimeout(function() {
						showSlide('cta-slide');
					}, 3000);
				}
			}
		});
	});

	// Start observing
	document.addEventListener('DOMContentLoaded', function() {
		const loadingSlide = document.getElementById('loading-slide');
		if (loadingSlide) {
			observer.observe(loadingSlide, { attributes: true });
		}
	});
</script>



</body>

</html>
