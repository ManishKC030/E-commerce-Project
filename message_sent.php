<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Sent</title>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@2.0.8/dist/lottie-player.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap');

        body {
            margin: 0;
            padding: 0;

            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .success-container {
            margin-top: 90px;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .success-message {
            margin-top: 37px;
            font-size: 40px;
            font-family: "DM Serif Text", serif;
            color: #333;
        }
    </style>
</head>

<body>
    <?php
    include("navbar.php");
    ?>
    <div class="success-container">
        <lottie-player
            id="success-animation"
            src="assets/animation/message-sent.json"
            background="transparent"
            speed="1"
            style="height: 350px;"
            autoplay>
        </lottie-player>
        <p class="success-message">Your message has been sent successfully!</p>
    </div>

    <script>
        const animation = document.getElementById('success-animation');

        // Redirect to contact page after animation ends
        animation.addEventListener('complete', () => {
            window.location.href = 'contact.php';
        });
    </script>
    <br><br><br><br> <br><br>
    <?php
    include("footer.php");
    ?>
</body>

</html>