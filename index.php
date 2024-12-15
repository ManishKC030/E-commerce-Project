<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ShoesHub - Shoes that fits your style</title>
  <link rel="shortcut icon" href="icons/home.svg" type="image/x-icon">
  <link rel="stylesheet" href="stylesheet/index.css">


</head>

<body>

  <?php
  require 'navbar.php';
  ?>


  <!-- Scrolling Sale Banner -->
  <div class="sale-banner">
    <p>Winter Offer: Upto 50% Off on all of our Products!</p>
  </div>

  <!-- slide show container -->
  <div class="slideshow-container">

    <!-- Slide 1 -->
    <div class="slide fade">
      <img src="assets/Green and Yellow Simple Clean Shoes Sale Banner.png" alt="Image 1">

    </div>

    <!-- Slide 2 -->
    <div class="slide fade">
      <img src="assets/oldskool.jpg" alt="Image 2">

    </div>

    <!-- Slide 3 -->
    <div class="slide fade">
      <img src="assets/blazer.jpg" alt="Image 3">

    </div>



    <!-- Previous and Next Buttons -->
    <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
    <a class="next" onclick="changeSlide(1)">&#10095;</a>
    <!-- Dots/Bullets for navigation -->
    <div style="text-align:center" class="dot-conatiner">
      <span class="dot" onclick="currentSlide(1)"></span>
      <span class="dot" onclick="currentSlide(2)"></span>
      <span class="dot" onclick="currentSlide(3)"></span>

    </div>

  </div>
  <br>
  <br>
  <!-- grid card Section -->
  <DIV class="para">
    <H1>SHOPPING MADE EASY</H1>
    <p>FIND WHAT WORKS FOR YOU</p>
  </DIV>
  <br>
  <div class="grid">
    <div class="card best-selling">
      <h2>Best Selling</h2>

    </div>
    <div class="card new-arrival">
      <h2>New Arrival</h2>

    </div>
    <div class="card classics">
      <h2>Classics</h2>

    </div>
    <div class="card collabs">
      <h2>Collbas</h2>
    </div>
  </div>
  <br>
  <hr><br>
  <!-- Hero Section -->
  <div class="hero">


  </div>

  <script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
      let i;
      let slides = document.getElementsByClassName("slide");
      let dots = document.getElementsByClassName("dot");
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      slideIndex++;
      if (slideIndex > slides.length) {
        slideIndex = 1
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex - 1].style.display = "block";
      dots[slideIndex - 1].className += " active";
      setTimeout(showSlides, 3200); // Change slide everyseconds
    }

    function changeSlide(n) {
      showManualSlides(slideIndex += n);
    }

    function currentSlide(n) {
      showManualSlides(slideIndex = n);
    }

    function showManualSlides(n) {
      let i;
      let slides = document.getElementsByClassName("slide");
      let dots = document.getElementsByClassName("dot");
      if (n > slides.length) {
        slideIndex = 1
      }
      if (n < 1) {
        slideIndex = slides.length
      }
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex - 1].style.display = "block";
      dots[slideIndex - 1].className += " active";
    }
  </script>

  <!-- shop by brand Section -->
  <div class="main-shop-brand">
    <div class="shop-by-brand">
      <h2>Shop by Brand</h2>
      <div class="brand-list">
        <div class="brand-item">
          <img src="assets/brands/Puma-Logo.png" alt="Puma">
        </div>
        <div class="brand-item">
          <img src="assets/brands/New-Balance-Emblem.png" alt="New Balance">
        </div>
        <div class="brand-item">
          <img src="assets/brands/adidas.png" alt="Adidas">
        </div>
        <div class="brand-item">
          <img src="assets/brands/Air-Jordan-Logo.png" alt="Air Jordan">
        </div>
        <div class="brand-item">
          <img src="assets/brands/nike.png" alt="Nike">
        </div>
        <div class="brand-item">
          <img src="assets/brands/Yeezy-Logo.png" alt="Yeezy">
        </div>
        <div class="brand-item">
          <img src="assets/brands/vans.png" alt="Vans">
        </div>
        <div class="brand-item">
          <a href="#">
            <img src="assets/brands/Converse_logo.svg.png" alt="Converse">
          </a>
        </div>
      </div>
      <a href="#" class="shop-button">Shop Now</a>
    </div>

  </div>
  <br>
  <?php
  require 'footer.php';
  ?>



</body>

</html>