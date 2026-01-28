<?php
// Capture query string if it exists
$queryParams = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';

// Set CTA links
$cta1 = 'https://stltrck.co.za/link/GdeAPzod0i' . $queryParams;
$cta2 = 'https://stltrck.co.za/link/1OHanMq4e4' . $queryParams;
$cta3 = 'https://stltrck.co.za/link/5nF9jWDfoN' . $queryParams;
$cta4 = 'https://stltrck.co.za/link/Y8mqOEAzKT' . $queryParams;
$cta5 = 'https://stltrck.co.za/link/JQ1xDB50n2' . $queryParams;
$cta6 = 'https://stltrck.co.za/link/u8kdWnzkJf' . $queryParams;
$cta7 = 'https://stltrck.co.za/link/ckp5KTNgRV' . $queryParams;
$cta8 = 'https://stltrck.co.za/stlbz/362d0f76-346f-4c8c-a06e-4ac55585de8a/0002' . $queryParams;
$cta9 = 'https://stltrck.co.za/link/yFQ1bYLMZS' . $queryParams;
$cta10 = 'https://stltrck.co.za/link/X0f0KTnLv5' . $queryParams;
$cta11 = 'https://stltrck.co.za/link/uYkYsFwax3' . $queryParams;
?>

<!DOCTYPE html>
<html lang="en">
  <!-- Header -->
  <?php include 'header.php'; ?>
    <!-- End Header -->
<body>
  <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NRH7CBL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <main class="container my-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
    <!-- Main Title -->
    <h1 class="text-center mb-3 fw-bold display-5">
      11 Smart Ways South African Retirees Are Using Their R1 050 Pension Boost
    </h1>
    <div class="text-center mb-3 mt-4">
      <img src="images/sassa-old-update.webp" alt="Auto Rocket" class="img-fluid rounded">
    </div>
    <!-- Author / Date -->
    <p class="text-center mb-4">
    <img src="images/elisa.png" alt="Author photo" class="author-image rounded-circle">
    By <strong>Elisa K.</strong> | <span id="blog-date"></span>
    </p>
    <!-- Featured Image -->

    <!-- Listicle Content -->
    <div id="listicle-content">
  <?php include 'smart-ways-to-save-content.php'; ?>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
