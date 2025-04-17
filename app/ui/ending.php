<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $_PROFILE; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/settings";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mist End of Support</title>
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
    <script src="/assets/js/common.js"></script>
    <div class="container">
        <br>
        <h2 class="desktop-title" style="margin-top: 10px; margin-bottom: 20px; margin-left: 10px;">Mist End of Support</h2>

        <div style="margin-left: 10px;">
            <h5>Mist is going out of support on March 17</h5>
            <p>After a few months of good service, Mist will be going offline on April 10. Learn what happens and what this means for Mist and for you on this page. Mist's end of support is being extended over the span of 3 months, from January 10 to April 10. During these 3 months, Mist will be progressively disabled according to this schedule:</p>
            <ul>
                <li><b>December 17:</b> Users are made aware of Mist going out of support and being given options.</li>
                <li><b>January 17:</b> No new content is added to Mist past this date. Music videos and Mist Stella songs are removed.</li>
                <li><b>February 17:</b> Streaming music from Mist is disabled and only downloads are available.</li>
                <li><b>March 17:</b> Mist goes offline completely and all the songs are permanently deleted.</li>
            </ul>

            <h5>What do I need to do?</h5>
            <p>If you were not using Mist, nothing. If you are using Mist, nothing special, other than playing your music through another streaming platform. Regardless, all of your Mist user data will be permanently deleted after Mist goes offline, so you don't have to worry about asking us to delete it. However, you can request a copy of it if you like.</p>

            <h5>What solutions do I have?</h5>
            <p><b>If you don't mind paying for your music</b>, we recommend <a target="_blank" href="https://www.apple.com/music">Apple Music</a> or <a target="_blank" href="https://www.deezer.com/">Deezer</a>. Avoid Spotify and YouTube Music at all costs! If you want to try emerging streaming platforms, you can also try <a target="_blank" href="https://www.tidal.com">Tidal</a> or <a target="_blank" href="https://www.amazon.com/music/unlimited/">Amazon Music Unlimited</a>. And if you want to own your music, check out <a target="_blank" href="https://www.qobuz.com/">Qobuz</a>.</p>
            <p><b>If you don't want to pay for music,</b> you can download music from Mist and use any music player to play them back (assuming you have downloaded the FLAC version). On macOS, use the <a target="_blank" href="https://support.apple.com/guide/music/welcome/mac">Music</a> app. On Windows, use the <a target="_blank" href="https://apps.microsoft.com/detail/9WZDNCRFJ3PT">Media Player</a> app. On Linux, use <a target="_blank" href="https://wiki.gnome.org/Apps/Lollypop">Lollypop</a> or <a target="_blank" href="https://apps.kde.org/elisa/">Elisa</a>. On Android, use <a target="_blank" href="https://play.google.com/store/apps/details?id=code.name.monkey.retromusic">Retro Music Player</a>, <a target="_blank" href="https://play.google.com/store/apps/details?id=com.sec.android.app.music">Samsung Music</a>, <a target="_blank" href="https://play.google.com/store/apps/details?id=org.videolan.vlc">VLC</a>, or <a target="_blank" href="https://play.google.com/store/apps/details?id=com.kyant.vanilla">Vanilla Music Player</a></a>.</p>
        </div>
    </div>
</body>
</html>