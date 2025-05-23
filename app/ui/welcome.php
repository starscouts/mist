<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.parent.openModal === "undefined") {
            location.href = "/app/";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform" style="background-color: transparent !important;">
    <script src="/assets/js/common.js"></script>
    <div style="padding: 1rem;">
        <div style="text-align: center;">
            <img src="/assets/logo-display.png" alt="Logo" style="width: 96px; margin-bottom: 15px; margin-top: 30px;">
            <h2>Welcome to<br>Mist</h2>

            <h4 style="margin-top: 30px; font-weight: normal;">The best way to discover and rediscover your favorite music.</h4>

            <p style="margin-top: 100px;" class="small text-muted">Your searches, favorites and library help improve the service. The administrators can provide you with more information about how your data is managed.</p>
            <p class="small text-muted">With your Equestria.dev Account, you will be able to sign in to available services. Equestria.dev records certain data for security, support and reporting purposes. <a target="_blank" href="https://equestria.dev/legal/privacy">See how this data is managed.</a></p>

            <a style="margin-bottom: 30px; display: block;" class="btn btn-primary" onclick="localStorage.setItem('welcomed', 'true'); localStorage.setItem('lastUpdate', '<?= trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/version")) ?>'); window.parent._modal.hide();">Continue</a>
        </div>
    </div>

    <script>
        window.sizeInterval = setInterval(() => {
            if (document.body.clientHeight > 0) {
                clearInterval(sizeInterval);
                window.parent.document.getElementById("modal-frame").style.height = document.body.clientHeight + "px";
            }
        });
    </script>
</body>
</html>