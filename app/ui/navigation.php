<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $_PROFILE ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>navigation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform navigation-body" style="overflow: hidden;">
    <script src="/assets/js/common.js"></script>
    <div class="navigation-container-inner container" style="margin-left: 20px; overflow: hidden;">
        <div id="navigation-left">
            <div id="navigation-gradient" style="background-image: radial-gradient(circle, rgba(122,195,245,.25) 0%, rgba(255,255,255,0) 75%); top: -256px; left: 0; right: 0; height: calc(512px + var(--android-status-bar)); position: fixed; z-index: -1;"></div>
            <div id="navigation-top" style="-webkit-app-region: drag; position: fixed; top: 0; right: 0; left: 0; height: calc(128px + var(--android-status-bar));"></div>
            <br><br><br>
            <h3 style="margin-top: var(--android-status-bar); margin-left: 10px;">
                <img src="/assets/logo-display.png" alt="" style="width: 32px; height: 32px; vertical-align: middle;">
                <span style="vertical-align: middle;" class="navigation-desktop">&nbsp;Mist</span>
            </h3>
            <br>
        </div>

        <div id="navigation-container">
            <div id="home" class="navigation-item" onclick="window.parent.openUI('home');">
                <img class="icon" alt="" src="/assets/icons/home.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Home</span>
            </div>
            <div id="lyrics" class="navigation-item" onclick="window.parent.showLyrics();">
                <img class="icon" alt="" src="/assets/icons/now.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Now playing</span>
            </div>
            <div id="queue" class="navigation-item" onclick="window.parent.openUI('queue');">
                <img class="icon" alt="" src="/assets/icons/playlist.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Queue</span>
            </div>
            <div id="library" class="navigation-item-mobile navigation-item" onclick="window.parent.openUI('library');">
                <img class="icon" alt="" src="/assets/icons/library.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Library</span>
            </div>
            <div id="albums" class="navigation-item-desktop navigation-item active" onclick="window.parent.openUI('albums');">
                <img class="icon" alt="" src="/assets/icons/album.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Albums</span>
            </div>
            <div id="songs" class="navigation-item-desktop navigation-item" onclick="window.parent.openUI('songs');">
                <img class="icon" alt="" src="/assets/icons/song.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Songs</span>
            </div>
            <div id="favorites" class="navigation-item-desktop navigation-item" onclick="window.parent.openUI('favorites');">
                <img class="icon" alt="" src="/assets/icons/favorites.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Favorites</span>
            </div>
            <div id="settings" class="navigation-item" onclick="window.parent.openUI('settings');">
                <img class="icon" alt="" src="/assets/icons/settings.svg" style="vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop">Settings</span>
            </div>
        </div>

        <div id="navigation-version">
            <a style="position: fixed; width: 100%; bottom: 60px; color: inherit; text-decoration: inherit; display: block;" id="account" class="navigation-item" href="https://account.equestria.dev/hub/users/<?= $_PROFILE["id"] ?>" target="_blank">
                <img alt="" src="https://account.equestria.dev/hub/api/rest/avatar/<?= $_PROFILE["id"] ?>?dpr=2&size=32" style="filter: none !important; border-radius: 999px; vertical-align: middle; width: 32px;"><span style="vertical-align: middle; margin-left: 5px;" class="navigation-desktop"><?= $_PROFILE["name"] ?></span>
            </a>
        </div>
    </div>
</body>
</html>