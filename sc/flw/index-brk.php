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

  <title>Solar Cancellation Resource Center | Learn More</title>

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
        <h1 class="mb-2">Discover the Legal Solution That's Helping Americans Cancel Their Solar Contracts</h1>
        <p class="text-sub-blue">You could qualify for a one-time, limited-time <strong>FREE consultation</strong> with our solar contract specialities.</p>
        <p class="discount-note" id="discount-count">Only <span>23 consultation</span> slots left today!</p>
        <div class="row"><div class="col d-grid gap-1">
          <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">Yes</button>
          <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">No</button>
        </div></div>
      </div>

      <!-- Question 1 -->
      <div class="quiz-slide hidden" id="question-1">
        <div class="container">
          <div class="row mb-2 mb-md-3">
            <div class="col">
              <div class="progress"><div class="progress-bar bg-success" style="width: 16%;"></div></div>
            </div>
          </div>
          <div class="row mb-1"><div class="col"><h2>Were you lied to or misled in anyway during the solar sales process?</h2></div></div>
          <div class="row mb-2 mb-md-3"><div class="col text-sub-blue">You aren't alone, we're here to help!</div></div>
          <div class="row"><div class="col d-grid gap-1">
            <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">Yes</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-2" data-progress="40" data-status="Unqualified">No</button>
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
              <h2>Do you lease or own your solar panels?</h2>
            </div>
          </div>
          <div class="row mb-2 mb-md-3">
            <div class="col text-sub-blue">
            </div>
          </div>
          <div class="row">
            <div class="col d-grid gap-1">
              <button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="60" data-status="Pre-Approved">Own</button>
              <button class="next-btn btn btn-dark answer-tight" data-next="question-3" data-progress="60" data-status="Pre-Approved">Lease</button>
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
          <div class="row mb-1"><div class="col"><h2>What started the solar sales process for you?</h2></div></div>
          <div class="row mb-2 mb-md-3"><div class="col text-sub-blue">Even if you don't remember the specifics, we can still help.</div></div>
          <div class="row"><div class="col d-grid gap-1">
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">Door-to-door salesperson</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">I was cold-called</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">I saw an online ad</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-4" data-progress="80" data-status="Approved">Other</button>
          </div></div>
        </div>
      </div>

      <!-- Question 4 -->
      <div class="quiz-slide hidden" id="question-4">
        <div class="container">
          <div class="row mb-2 mb-md-3">
            <div class="col"><div class="progress"><div class="progress-bar bg-success" style="width: 66%;"></div></div></div>
          </div>
          <div class="row mb-1"><div class="col"><h2>Has your solar system become an unwanted financial or emotional burden?</h2></div></div>
          <div class="row mb-2 mb-md-3"><div class="col text-sub-blue">Select the option that you've tried most recently.</div></div>
          <div class="row"><div class="col d-grid gap-1">
            <button class="next-btn btn btn-dark answer-tight" data-next="question-5" data-progress="100" data-status="congratulations">Yes</button>
            <button class="next-btn btn btn-dark answer-tight" data-next="question-5" data-progress="100" data-status="congratulations">No</button>
            </div></div>
        </div>
      </div>

      <!-- CTA slide -->
  <div class="quiz-slide hidden" id="congratulations">
  <div class="container">
    <div class="row mb-2"><div class="col"><h2 class="congrats">🎉 Congratulations! 🎉</h2></div></div>
    <div class="row mb-1"><div class="col">
      <p class="narrow">Based on your answers, you are likely eligible to end your solar contract <span class="discount-text">end your solar contract</span>.</p>
      <p class="narrow-quote">Some of our clients may even receive a settlement from the solar sales company for gross misreprentation.</p>
    </div></div>
    <div class="row mb-1"><div class="col">
      <form id="leadForm">
        <div class="mb-3">
          <label for="firstName" class="form-label">First Name *</label>
          <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>
        <div class="mb-3">
          <label for="lastName" class="form-label">Last Name *</label>
          <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address *</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="phone" class="form-label">Phone Number *</label>
          <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
          <button type="submit" class="cta-button">CLAIM MY FREE CONSULTATION NOW</button>
        </div>
      </form>
    </div>
  </div>
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
    discountText.innerHTML = `Only <span>${count} free consultations</span> left today!`;
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
