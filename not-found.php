<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 Not Found</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
    }

    .container {
      max-width: 600px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      /* Centers child elements horizontally */
    }

    .animation {
      /* Increased height */
      margin-bottom: 20px;
    }

    h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    p {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }

    a {
      text-decoration: none;
      color: #007bff;
      font-size: 1.2rem;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="animation">
      <lottie-player

        src="assets/animation/not-found.json"
        background="transparent"
        speed="1"
        style="width: 900px;"
        loop
        autoplay></lottie-player>
    </div>
    <h1>Page Not Found</h1>
    <a href="index.php">Return to Home</a>
  </div>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>