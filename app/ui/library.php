<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $songs; global $albums; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/library";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform">
    <script>
        window.parent.location.hash = "#/library";
    </script>
    <script src="/assets/js/common.js"></script>
    <div class="container">
        <br>
        <div style="margin-left: 10px;" class="list-group">
            <a class="list-group-item list-group-item-action" href="albums.php">
                <img src="/assets/icons/album.svg" style="filter: brightness(0%); margin-right: 5px; vertical-align: middle;">Albums
            </a>
            <a class="list-group-item list-group-item-action" href="songs.php">
                <img src="/assets/icons/song.svg" style="filter: brightness(0%); margin-right: 5px; vertical-align: middle;">Songs
            </a>
            <a class="list-group-item list-group-item-action" href="favorites.php">
                <img src="/assets/icons/favorites.svg" style="filter: brightness(0%); margin-right: 5px; vertical-align: middle;">Favorites
            </a>
        </div>
    </div>

    <br><br>
</body>
</html>