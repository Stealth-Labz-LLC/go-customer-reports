<?php
// Capture query string if it exists
$queryParams = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';

// Set the CTA link to the external Evergreen Botanicals page
$ctaLink = 'https://buy.evergreen-botanicals.com/rd-hs-1/' . $queryParams;
?>

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Pain Relief Questionnaire | 5 Questions Only</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="style.css" />

  <link rel="stylesheet" href="modal-style.css?v=<?= time() ?>">
  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon" />
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-5NRH7CBL');</script>
  <!-- End Google Tag Manager -->
</head>

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NRH7CBL"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <header class="site-header">

    <div class="container text-center">

      <img src="images/cr-logo.svg" alt="Company Logo" class="logo mx-auto d-block" />

    </div>

  </header>


  <div class="wrapper">
    <div class="main">

      <!-- Intro Slide -->
      <div class="intro-slide">
        <h1 class="mb-2">Discover the Natural Solution That's Ending Neuropathy Pain in Minutes</h1>
        <p class="text-sub-blue">You could qualify for a one-time, limited-time <strong>48% discount</strong> on this all-natural pain relief breakthrough.</p>
        <p class="discount-note" id="discount-count">Only <span>23 discounts</span> left today!</p>
        <button class="start-btn btn btn-primary">GET STARTED</button>
      </div>

      <!-- Question 1 -->
      <div class="quiz-slide hidden" id="question-1">
        <div class="container">
          <div class="row mb-2 mb-md-3">
            <div class="col">
              <div class="progress"><div class="progress-bar bg-success" style="width: 16%;"></div></div>
            </div>
          </div>
          <div class="row mb-1"><div class="col"><h2>Where do you feel the most pain?</h2></div></div>
          <div class="row mb-2 mb-md-3"><div class="col text-sub-blue">Select the area that bothers you the most.</div></div>
          <div class="row"><div class="col d-grid gap-1">
            <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">My Joints</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">My Muscles</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">My Nerves</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">ALL OVER</button>
          </div></div>
        </div>
      </div>
      <!-- Question 2 -->
      <div class="quiz-slide hidden" id="question-2">
        <div class="container">
          <!-- Progress Bar with responsive spacing -->
          <div class="row mb-2 mb-md-3">
            <div class="col">
              <div class="progress">
                <div class="progress-bar bg-success" style="width: 33%;"></div>
              </div>
            </div>
          </div>
          <div class="row mb-1">
            <div class="col">
              <h2>How does the pain affect your daily life?</h2>
            </div>
          </div>
          <div class="row mb-2 mb-md-3">
            <div class="col text-sub-blue">
              Choose the most disruptive symptom.
            </div>
          </div>
          <div class="row">
            <div class="col d-grid gap-1">
              <button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="60" data-status="Pre-Approved">I Can't Sleep Through The Night</button>
              <button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="60" data-status="Pre-Approved">I Stopped Doing What I Love</button>
              <button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="60" data-status="Pre-Approved">Simple Tasks Drain My Energy</button>
              <button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="60" data-status="Pre-Approved">I'm Physically/Mentally Exhausted</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Question 3 -->
      <div class="quiz-slide hidden" id="question-3">
        <div class="container">
          <div class="row mb-2 mb-md-3">
            <div class="col"><div class="progress"><div class="progress-bar bg-success" style="width: 50%;"></div></div></div>
          </div>
          <div class="row mb-1"><div class="col"><h2>How long have you felt this pain?</h2></div></div>
          <div class="row mb-2 mb-md-3"><div class="col text-sub-blue">Be honest — chronic pain can change over time.</div></div>
          <div class="row"><div class="col d-grid gap-1">
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">It Just Started</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">A Few Months</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">The Past Year</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">Seems Like Forever</button>
          </div></div>
        </div>
      </div>

      <!-- Question 4 -->
      <div class="quiz-slide hidden" id="question-4">
        <div class="container">
          <div class="row mb-2 mb-md-3">
            <div class="col"><div class="progress"><div class="progress-bar bg-success" style="width: 66%;"></div></div></div>
          </div>
          <div class="row mb-1"><div class="col"><h2>What have you tried to ease the pain?</h2></div></div>
          <div class="row mb-2 mb-md-3"><div class="col text-sub-blue">Select the option that you've tried most recently.</div></div>
          <div class="row"><div class="col d-grid gap-1">
            <button class="next-btn btn btn-dark answer-tight" data-next="question-5" data-progress="100" data-status="Congratulations">Over-the-Counter Painkillers</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-5" data-progress="100" data-status="Congratulations">Physical Therapy / Chiropractor</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-5" data-progress="100" data-status="Congratulations">Natural Remedies like CBD</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-5" data-progress="100" data-status="Congratulations">Everything</button>
          </div></div>
        </div>
      </div>

      <!-- Question 5 -->
      <div class="quiz-slide hidden" id="question-5">
        <div class="container">
          <div class="row mb-3">
            <div class="col"><div class="progress"><div class="progress-bar bg-success" style="width: 83%;"></div></div></div>
          </div>
          <div class="row mb-1"><div class="col"><h2>Are you ready to start living pain free?</h2></div></div>
          <div class="row mb-2 mb-md-3"><div class="col">
            <img src="images/pain-free.png" alt="Red Dragon" class="img-fluid mt-3" />
          </div></div>
          <div class="row mb-2 mb-md-3"><div class="col text-sub-blue">This could be the start of your recovery. Don't stop now.</div></div>
          <div class="row"><div class="col d-grid gap-1">
            <button class="next-btn btn btn-success answer-tight" data-next="cta-slide">Yes, please help me!</button>
            <button class="next-btn btn btn-outline-secondary answer-tight" data-next="cta-slide">No, I feel good enough</button>
          </div></div>
        </div>
      </div>

      <!-- CTA Slide -->
      <div class="quiz-slide hidden" id="cta-slide">
        <div class="container">
          <div class="row mb-2"><div class="col"><h2 class="congrats">🎉 Congratulations! 🎉</h2></div></div>
          <div class="row mb-1"><div class="col">
            <p class="narrow">You’ve been selected to receive a <span class="discount-text">48% off discount</span> today only.</p>
            <!--<p class="narrow-quote">92% of users report feeling relief in less than 30 minutes. Take the first step towards pain-free living.</p>-->
          </div></div>
          <div class="row mb-1"><div class="col">
            <a href="<?php echo $ctaLink; ?>"><img src="images/red-dragon-bottle.png" alt="Red Dragon" class="img-fluid" /></a>
          </div></div>
          <div class="row mb-1"><div class="col"><a href="<?php echo $ctaLink; ?>"><div class="coupon-box">DA25171525</div></a></div></div>
          <div class="row"><div class="col">
            <a href="<?php echo $ctaLink; ?>" class="cta-button">CLAIM MY DISCOUNT NOW</a>
          </div>
<span class="applied">Applied automatically on next page!</span>
        </div>
        </div>
      </div>

    </div>
</div>
<div class="trust-section container mt-1">
<div class="row align-items-center">

  <!-- Left Column: Doctor Image -->
  <div class="col-md-4 text-center">
    <img src="images/dr-beckmane.png" alt="Dr. Kevin Beckmane" class="img-fluid rounded" />
    <p class="mt-2 mb-0 text-blue"><strong>Dr. Kevin Beckmane</strong><br>Board-Certified Pain Specialist</p>
  </div>

  <!-- Right Column: Testimonial -->
  <div class="col-md-8">
    <blockquote class="blockquote">
      <p class="mb-3 trust-quote">"Over <strong>80% of my patients have experienced measurable relief</strong> with this all-natural breakthrough. It's safe, fast-acting, and backed by science."</p>
      <footer class="blockquote-footer"><cite title="Source Title">Impact of MIT on Chronic Pain (2024), AAPM</cite></footer>
    </blockquote>
  </div>
</div>
</div>

  <footer class="site-footer">
    <p><img loading="lazy" class="hipaa" src="images/protected.png" alt="Federal HIPAA compliant"></p>
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

    const discountText = document.getElementById("discount-count");
  let count = 23;
  const interval = setInterval(() => {
    count--;
    if (count <= 21) clearInterval(interval);
    discountText.innerHTML = `Only <span>${count} discounts</span> left today!`;
  }, 2500);

    // Start quiz

    document.querySelector('.start-btn').addEventListener('click', () => {

      document.querySelector('.intro-slide').classList.add('hidden');

      document.getElementById('question-1').classList.remove('hidden');

    });



    // Slide navigation

    document.querySelectorAll('.next-btn').forEach(button => {

      button.addEventListener('click', () => {

        const currentSlide = button.closest('.quiz-slide');

        const nextId = button.dataset.next;

        const progress = button.dataset.progress;

        const status = button.dataset.status;

        const nextSlide = document.getElementById(nextId);



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
