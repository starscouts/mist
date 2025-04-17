<?php header("X-Frame-Options: DENY"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container">
        <br>
        <h2>Log in required</h2>
        <p>Please use the tab opened in your default browser to log into Mist. This page will refresh automatically after you log in.</p>
        <a onclick="MistNative.auth();" href="#">Having trouble?</a>
    </div>

    <script>
        window.onload = () => {
            MistNative.auth();
        }
    </script>
</body>
</html>