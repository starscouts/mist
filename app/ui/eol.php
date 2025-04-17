<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <script src="/assets/js/common.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>eol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
    <style>
        * {
            overflow-x: hidden;
        }
    </style>
</head>
<body class="crossplatform" style="background-color: transparent !important;">
    <script>
        if (navigator.userAgent.includes("MistNative/darwin") || navigator.userAgent.includes("MistNative/win32")) {
            document.getElementById("native-css").disabled = false;
            document.body.classList.remove("crossplatform");
        }
    </script>
    <div style="padding: 1rem;">
        <div style="display: flex; justify-content: center;">
            <div style="position: relative; left: 15px;">
                <div style="display: inline-block; width: 24px; height: 24px; background: #d7e36d; border-radius: 999px;"></div>
                <div style="display: inline-block; z-index: -1; width: calc(165vw / 4 - 23px * 4); height: 8px; background: linear-gradient(90deg, #d7e36d 0%, #e3d96d 100%); position: relative; left: -7px; top: -7px; filter: saturate(0) contrast(0) brightness(190%);"></div>
                <div style="display: inline-block; width: 24px; height: 24px; background: #e3d96d; border-radius: 999px; left: -12px; position: relative; filter: saturate(0) contrast(0) brightness(190%);"></div>
                <div style="display: inline-block; z-index: -1; width: calc(165vw / 4 - 23px * 4); height: 8px; background: linear-gradient(90deg, #e3d96d 0%, #e3a26d 100%); position: relative; left: -17px; top: -7px; filter: saturate(0) contrast(0) brightness(190%);"></div>
                <div style="display: inline-block; width: 24px; height: 24px; background: #e3a26d; border-radius: 999px; left: -22px; position: relative; filter: saturate(0) contrast(0) brightness(190%);"></div>
                <div style="display: inline-block; z-index: -1; width: calc(165vw / 4 - 23px * 4); height: 8px; background: linear-gradient(90deg, #e3a26d 0%, #e36d6d 100%); position: relative; left: -27px; top: -7px; filter: saturate(0) contrast(0) brightness(190%);"></div>
                <div style="display: inline-block; width: 24px; height: 24px; background: #e36d6d; border-radius: 999px; left: -32px; position: relative; filter: saturate(0) contrast(0) brightness(190%);"></div>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); position: relative; left: -38px; width: 108vw;">
            <div style="text-align: center;">December</div>
            <div style="text-align: center;">January</div>
            <div style="text-align: center;">February</div>
            <div style="text-align: center;">March</div>
        </div>

        <hr>

        <p>Mist has served its time well and is now going away. It will progressively be taken offline over the next few months, to give you time to get your data away and know what is going to happen. If you want more information, you can click on "Learn more" below.</p>

        <ul>
            <li><b>Users being warned</b></li>
            <li class="text-muted">No more new content</li>
            <li class="text-muted">Removal of videos and Mist Stella</li>
            <li class="text-muted">Mist goes download only</li>
            <li class="text-muted">Mist is taken offline</li>
        </ul>

        <a class="btn btn-primary" onclick="window.parent._modal.hide(); window.parent.parent.location.hash = '#/ending';">Learn more</span></a>
        <a style="float: right;" class="btn btn-outline-secondary" onclick="window.parent._modal.hide();">Close</a>
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