<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shipping Charges</title>
  <style>
    .container {
      max-width: 890px;
      margin: 50px auto;
      padding: 20px;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    .head_repo {
      font-size: 2em;
      color: #007bff;
      text-align: center;
      margin-bottom: 20px;
    }

    .head2_repo {
      font-size: 1.5em;
      margin-top: 20px;
      color: #555;
    }

    .paragraph {
      margin: 10px 0;
    }

    .repo {
      margin: 10px 0 10px 20px;
      list-style: disc;
    }

    a {
      color: #007bff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .below {
      text-align: center;
      margin-top: 30px;
      font-size: 0.9em;
      color: #666;
    }
  </style>
</head>

<body>
  <?php
  include("navbar.php");
  ?>
  <div class="container">
    <h1 class="head_repo">Shipping Charges</h1>

    <p class="paragraph">
      At <strong>ShoesHub</strong>, we strive to provide transparent and fair shipping rates. Below you will find detailed information about our shipping charges and policies.
    </p>

    <h2 class="head2_repo">Shipping Rates</h2>
    <ul class="repo">
      <li><strong>Standard Shipping:</strong> Flat rate of NPR 150 for orders below NPR 3,000. Free for orders above NPR 3,000.</li>
      <li><strong>Express Shipping:</strong> Additional NPR 200 on top of the standard rate for faster delivery.</li>
    </ul>

    <h2 class="head2_repo">Delivery Areas</h2>
    <p class="paragraph">
      We currently deliver to all major cities and towns across Nepal. For remote areas, additional shipping charges may apply. Our customer service team will contact you with details if applicable.
    </p>

    <h2 class="head2_repo">Estimated Delivery Time</h2>
    <ul class="repo">
      <li><strong>Standard Shipping:</strong> 3-7 business days.</li>
      <li><strong>Express Shipping:</strong> 1-3 business days.</li>
    </ul>

    <h2 class="head2_repo">International Shipping</h2>
    <p class="paragraph">
      We do not currently offer international shipping. Stay tuned for updates as we expand our services!
    </p>

    <h2 class="head2_repo">Additional Charges</h2>
    <p class="paragraph">
      In rare cases where custom duties or additional local taxes apply, customers will be notified and required to bear the extra charges.
    </p>

    <p class="below">
      If you have any questions about our Shipping Charges, please contact us at
      <a href="mailto:shipping@shoeshub.com">shipping@shoeshub.com</a>.
    </p>
  </div>

  <?php
  include("footer.php");
  ?>
</body>

</html>