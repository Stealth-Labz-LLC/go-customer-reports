<?php
// Capture query string if it exists
$queryParams = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';

// Set CTA links
$cta1 = 'https://stltrck.co.za/link/GdeAPzod0i' . $queryParams;
?>

<!DOCTYPE html>
<html lang="en">
  <!-- Header -->
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Customer Reports | Customer Powered Reviews</title>
    <!-- Bootstrap CSS CDN -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all-pages.css" rel="stylesheet">
    <link rel='icon' type='image/png' href='images/favicon.png'>
    <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-5NRH7CBL');</script>
  <!-- End Google Tag Manager -->
  </head>
    <!-- End Header -->
<body>
  <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NRH7CBL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <main class="container my-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
    <!-- Main Title -->
    <h1 class="text-center mb-3 fw-bold display-5">
    Relief for South African car owners is here - <span style="color:#118cf0">Save R500+ / month</span> for being a safe driver.
    </h1>
    <div class="text-center mb-3 mt-5">
      <img src="images/auto-savings.jpg" alt="Auto Rocket" class="img-fluid rounded">
    </div>
    <!-- Author / Date -->
    <!--<p class="text-center mb-4">
    <img src="images/elisa.png" alt="Author photo" class="author-image rounded-circle">
    By <strong>Elisa K.</strong> | <span id="blog-date"></span>
    </p>
    <!-- Featured Image -->

    <!-- Listicle Content -->
    <div id="listicle-content">
      <div class="text-center fs-2 mt-5 mb-5 fw-bold">
        Answer 1 simple question to claim your savings today.
      </div>
      <!-- Offer 1 -->
      <div class="container my-4 pb-3 cta-block">
        <div class="p-1 px-md-5">
          <!-- Body Content -->
          <!-- Requirements -->
        <p class="fw-bold fs-4 mt-5 text-center mb-2">On average, how many km do you drive a day?</p>
          <!-- CTA Buttons Grid -->
          <div class="container pt-3 pb-3">
            <div class="row g-3">
              <!-- First row -->
              <div class="col-12 col-md-6">
                <a href="<?= $cta1 ?>" class="btn btn-primary w-100 btn-lg py-3">Under 80km ➤</a>
              </div>
              <div class="col-12 col-md-6">
                <a href="<?= $cta1 ?>" class="btn btn-primary w-100 btn-lg py-3">Over 80km ➤</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container mt-5">
  <div class="row align-items-center">

    <!-- Left column with image -->
    <div class="col-12 col-md-2 text-center">
      <img src="images/savings-driving.jpg" alt="Happy customer" class="img-fluid py-3" style="width: 150px;">
    </div>

    <!-- Right column with text -->
    <div class="col-12 col-md-10 text-center text-md-start">
      <p class="fs-5 fst-italic">
        "I saved over R7000 last year just be switching my auto insurance.  I was shocked how simple it was to switch."
      </p>
    </div>

  </div>
</div>
    </div>
  </div>
</div>
</main>

  <!-- Footer -->
  <?php include 'footer.php'; ?>
  <!-- End Footer -->

  <!-- Bootstrap JS CDN (optional if you need JS components) -->
  <!-- jQuery first -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Fancybox after jQuery -->
  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Your date script -->
  <script>
    const today = new Date();
    const blogDate = document.getElementById("blog-date");
    if (blogDate) {
      blogDate.textContent = today.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
    }
    const footerYear = document.getElementById("footer-year");
    if (footerYear) {
      footerYear.textContent = today.getFullYear();
    }
  </script>

  <!-- Fancybox init -->
  <script>
  $(document).ready(function() {
    $('[data-fancybox]').fancybox({
      iframe: {
        css: {
          width: '800px',
          height: '600px'
        }
      },
      smallBtn: true,
      toolbar: false
    });
  });
  </script>
</body>
</html>
