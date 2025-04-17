<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
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
    <title>player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform">
    <script>
        window.transitionTimeout = null;
        window.lastPosY = null;
        window.log = false;

        document.body.ontouchmove = (e) => {
            if (window.log) {
                console.log(e.touches[0]);
                window.lastPosY = e.touches[0].clientY +
                    window.parent.document.getElementById("player").offsetTop +
                    (window.parent.innerHeight - window.parent.document.getElementById("player").offsetTop);
                window.parent.document.getElementById("player-mobile-container").style.bottom = -(e.touches[0].clientY +
                    window.parent.document.getElementById("player").offsetTop +
                    (window.parent.innerHeight - window.parent.document.getElementById("player").offsetTop)) + "px";
                console.log(window.parent.document.getElementById("player-mobile-container").style.bottom);
            }
        }

        document.body.ontouchstart = () => {
            if (window.parent.currentSongID !== null) {
                window.log = true;
                window.parent.document.getElementById("player-mobile-container").style.transition = "";
            }
        }

        document.body.ontouchend = () => {
            if (window.log) {
                window.log = false;
                window.parent.document.getElementById("player-mobile-container").style.transition = "bottom 200ms ease 0s";
                window.parent.document.getElementById("player-mobile-container").style.bottom = "-100vh";

                if (lastPosY) {
                    console.log(window.parent.innerHeight - lastPosY);

                    if (window.parent.innerHeight - lastPosY >= 10 && lastPosY >= 10) {
                        window.parent.showMobilePlayer();
                    }
                }

                window.lastPosY = null;
            }
        }
    </script>
    <script src="/assets/js/common.js"></script>
    <div id="player" class="bg-white desktop-player" style="position: fixed; bottom: 0; left: 0; right: 0; height: 64px; z-index: 9999;">
        <div id="desktop-player-action" onclick="window.parent.showMobilePlayer();" style="display: none;"></div>

        <audio id="player-audio"></audio>
        <audio id="player-audio-stella-side1"></audio>
        <audio id="player-audio-stella-side2"></audio>
        <audio id="player-audio-stella-side3"></audio>
        <audio id="player-audio-stella-side4"></audio>
        <audio id="player-audio-stella-side5"></audio>

        <div class="container" style="display: grid; grid-template-columns: 1fr 1.5fr 1fr;">
            <div id="buttons" style="text-align: center; display: flex; align-items: center; justify-content: center;">
                <div style="height: 48px; margin-top: 8px; margin-bottom: 8px;">
                    <span onclick="window.parent.toggleShuffle();" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-shuffle">
                        <img class="icon" alt="" src="/assets/icons/shuffle-off.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-shuffle-icon">
                    </span>
                        <span onclick="window.parent.previous();" class="player-btn disabled" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-previous">
                        <img class="icon" alt="" src="/assets/icons/previous.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-previous-icon">
                    </span>
                        <span onclick="window.parent.playPause();" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-play">
                        <img class="icon" alt="" src="/assets/icons/play.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-play-icon">
                    </span>
                        <span onclick="window.parent.next();" class="player-btn disabled" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-next">
                        <img class="icon" alt="" src="/assets/icons/next.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-next-icon">
                    </span>
                        <span onclick="window.parent.toggleRepeat();" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-repeat">
                        <img class="icon" alt="" src="/assets/icons/repeat-off.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-repeat-icon">
                    </span>
                </div>
            </div>
            <div>
                <span class="player-badge-desktop" data-bs-html="true" data-bs-toggle="tooltip" id="badge-cd" style="z-index: 9999; display: none;position: absolute;margin-left: 71px;"><img src="/assets/icons/lossless.svg" alt="" style="height: 12px;opacity: .5;" class="icon"></span>
                <span class="player-badge-desktop" data-bs-html="true" data-bs-toggle="tooltip" id="badge-hires" style="z-index: 9999; display: none;position: absolute;margin-left: 71px;"><img src="/assets/icons/lossless.svg" alt="" style="height: 12px;opacity: .5;" class="icon"></span>
                <span class="player-badge-desktop" data-bs-html="true" title="<img src='/assets/icons/stella-full.svg' class='stella-logo-full'>" data-bs-toggle="tooltip" id="badge-stella" style="z-index: 9999; display: none;position: absolute;margin-left: 71px;"><img src="/assets/icons/stella.svg" alt="" style="height: 12px;opacity: .5;" class="icon"></span>
                <style>
                    .stella-logo-full {
                        height: 12px;
                        filter: invert(1);
                    }
                </style>
                <div id="info" style="display: none; grid-template-columns: 64px 1fr; height: 64px; border-left: 1px solid rgba(0, 0, 0, .25); border-right: 1px solid rgba(0, 0, 0, .25);">
                    <img alt="" id="album-art" style="background-color: rgba(0, 0, 0, .1); height: 64px; width: 64px;">
                    <div id="info-grid" style="z-index: 9; display: grid; grid-template-rows: 2px 22px 22px 12px 6px;">
                        <div id="info-grid-sep"></div>
                        <div id="info-grid-title" style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis; display: flex; font-size: 0.91rem; align-items: end; text-align: center; justify-content: center;"><span onclick="openSong();" class="clickable" id="title">Title</span></div>
                        <div id="info-grid-info" style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis; display: flex; font-size: 0.91rem; align-items: start; text-align: center; justify-content: center; opacity: .5;"><span onclick="openArtist();" class="clickable" id="artist">Artist</span><span class="player-badge-desktop">&nbsp;—&nbsp;<span onclick="openAlbum();" class="clickable" id="album">Album</span></span></div>
                        <div id="info-grid-time" style="font-size: 9px; opacity: .5; margin-left: 2px; margin-right: 2px;">
                            <span id="elapsed-time">0:00</span>
                            <span id="remaining-time" style="float: right;">-0:00</span>
                        </div>
                        <div style="background-color: rgba(0, 0, 0, .05);" id="seekbar-container">
                            <div id="seekbar" style="pointer-events: none; background-color: rgba(0, 0, 0, .25); width: 0; height: 8px;"></div>
                        </div>
                    </div>
                </div>
                <div id="cover" style="display: grid; grid-template-columns: 64px 1fr; height: 64px; border-left: 1px solid rgba(0, 0, 0, .25); border-right: 1px solid rgba(0, 0, 0, .25);">
                    <img alt="" src="/assets/nothing.svg" id="album-art" style="background-color: rgba(0, 0, 0, .1); height: 64px; width: 64px;">
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <img class="icon" src="/assets/logo-transparent.svg" style="filter: grayscale(1) invert(1); width: 32px; height: 32px;" alt="">
                    </div>
                </div>
            </div>
            <div style="text-align: center; display: flex; align-items: center; justify-content: center;" id="badges">
                <img src="/assets/icons/volume-down.svg" alt="" class="icon" style="margin-right: 10px; display: inline-block;">
                <input onchange="window.parent.setPlayerVolume();" oninput="window.parent.setPlayerVolume();" min="0" max="100" step="0.01" value="100" type="range" class="form-range" id="volume" style="width: 128px;">
                <img src="/assets/icons/volume-up.svg" alt="" class="icon" style="margin-left: 10px; display: inline-block;">
            </div>
        </div>
    </div>

    <script>
        if (localStorage.getItem("volume")) {
            let vol = parseFloat(localStorage.getItem("volume"));

            if (!isNaN(vol) && vol >= 0 && vol <= 100) {
                document.getElementById("volume").value = vol;
                window.parent.setPlayerVolume();
            } else {
                localStorage.setItem("volume", "100");
            }
        } else {
            localStorage.setItem("volume", "100");
        }

        window.buildTooltips = () => {
            console.log("Build tooltip");
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }

        if (navigator.userAgent.includes("MistNative/win32")) {
            document.getElementById("badges").style.marginRight = "96px";
        }

        function openSong() {
            window.parent.openModal((window.parent.songs[window.parent.currentSongID]?.artist ?? "Unknown artist") + " - " + (window.parent.songs[window.parent.currentSongID]?.title ?? "Unknown song"), "info.php?i=" + window.parent.currentSongID, true);
        }

        function openArtist() {
            window.parent.location.hash = "#/search/" + encodeURIComponent(window.parent.songs[window.parent.currentSongID]?.artist ?? "Unknown artist");
            window.parent.document.getElementById("ui").src = "ui/search.php?q=" + encodeURIComponent(window.parent.songs[window.parent.currentSongID]?.artist ?? "Unknown artist")
            window.parent.redoNavigation("home");
        }

        async function openAlbum() {
            if (Object.entries(window.parent.albums).filter(i => i[1].tracks.includes(window.parent.currentSongID))) {
                await window.parent.redownloadMedia();
            }

            window.parent.location.hash = "#/albums/" + Object.entries(window.parent.albums).filter(i => i[1].tracks.includes(window.parent.currentSongID))[0][0];
            window.parent.redoNavigation("albums");
        }
    </script>
    <style>
        .clickable {
            cursor: pointer;
        }

        .clickable:hover {
            text-decoration: underline;
        }
    </style>
</body>
</html>