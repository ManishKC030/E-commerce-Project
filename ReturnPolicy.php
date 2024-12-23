<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Return Policy</title>
  <style>
    .container {
      max-width: 1100px;
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
    <h1 class="head_repo">Return Policy</h1>

    <p class="paragraph">
      At <strong>ShoesHub</strong>, we aim to ensure your
      satisfaction with every purchase. If you are not entirely satisfied with
      your purchase, we are here to help.
    </p>

    <h2 class="head2_repo">Returns</h2>
    <p class="paragraph">
      You have <strong>30 days</strong> from the date of purchase to initiate
      a return. To be eligible for a return, your item must be:
    </p>
    <ul class="repo">
      <li>Unused and in the same condition that you received it.</li>
      <li>In its original packaging.</li>
      <li>Accompanied by the receipt or proof of purchase.</li>
    </ul>

    <h2 class="head2_repo">Refunds</h2>
    <p class="paragraph">
      Once we receive your item, we will inspect it and notify you of the
      status of your refund. If your return is approved, we will initiate a
      refund to your original payment method. Refunds may take 7-10 business
      days to process.
    </p>

    <h2 class="head2_repo">Exchanges</h2>
    <p class="paragraph">
      If the item you received is defective or damaged, we will replace it
      with the same item. Please contact us at
      <a href="mailto:support@yourstore.com">support@shoeshub.com</a> to
      arrange an exchange.
    </p>

    <h2 class="head2_repo">Non-Returnable Items</h2>
    <p>The following items are not eligible for return:</p>
    <ul>
      <li>Gift cards</li>
      <li>Items on clearance</li>
      <li>Customized or personalized items</li>
    </ul>

    <h2 class="head2_repo">How to Initiate a Return</h2>
    <p class="paragraph">
      To start a return, please contact our customer service team at
      <a href="mailto:returns@yourstore.com">returns@shoeshub.com</a> with
      your order details. We will provide further instructions on how to
      return your item.
    </p>

    <p class="below">
      If you have any questions about our Return Policy, please contact us at
      <a href="mailto:info@yourstore.com">info@shoeshub.com</a>.
    </p>
  </div>

  <?php
  include("footer.php");
  ?>
</body>

</html>