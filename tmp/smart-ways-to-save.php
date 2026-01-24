<!DOCTYPE html>
<html lang="en">
  <!-- Header -->
  <?php include 'header.php'; ?>
    <!-- End Header -->

    <main class="container my-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
    <!-- Main Title -->
    <h1 class="text-center mb-3 fw-bold display-5">
      10 Gifts Every Senior Needs This Holiday Season
    </h1>
    <div class="text-center mb-3">
      <img src="images/image.png" alt="Auto Rocket" class="img-fluid rounded">
    </div>
    <!-- Author / Date -->
    <p class="text-center text-muted mb-4">
    <img src="images/elisa.png" alt="Author photo" class="author-image rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
    By Elisa K. | <span id="blog-date"></span>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  const today = new Date();

  // Full date for blog post
  const blogOptions = { year: 'numeric', month: 'long', day: 'numeric' };
  document.getElementById("blog-date").textContent = today.toLocaleDateString(undefined, blogOptions);

  // Year only for footer
  document.getElementById("footer-year").textContent = today.getFullYear();
</script>
</body>
</html>
