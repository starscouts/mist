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
    <title>player-mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="/assets/js/shortcuts.js"></script>
    <style>
        .player-btn {
            filter: invert(1);
            opacity: .75;
        }
    </style>
</head>
<body>
    <script>
        let style = document.createElement("style");

        if (window.MistAndroid) {
            style.innerHTML =
            `:root {
                --android-navigation-bar: ${window.MistAndroid.getNavigationBarHeight()}px;
                --android-status-bar: ${window.MistAndroid.getStatusBarHeight()}px;
            }`;
        } else {
            style.innerHTML =
            `:root {
                --android-navigation-bar: 0px;
                --android-status-bar: 0px;
            }`;
        }

        document.head.append(style);
    </script>
    <div id="act" onclick="window.parent.hideMobilePlayer();" style="position: fixed; z-index: 9999999; top: 0; bottom: calc(175px + var(--android-navigation-bar)); height: calc(100vh - (175px + var(--android-navigation-bar))); left: 0; right: 0;"></div>
    <div id="android" style="position: fixed; z-index: 9999999; bottom: 0; right: 0; left: 0; height: var(--android-navigation-bar); border-top: 1px solid rgba(255, 255, 255, .1);"></div>

    <script>
        window.transitionTimeout = null;
        window.lastPosY = null;
        window.initialPosY = null;
        window.log = false;

        document.body.ontouchmove = (e) => {
            if (window.log) {
                window.lastPosY = e.touches[0].clientY;
                let diff = initialPosY - lastPosY;

                if (diff < 0) {
                    window.parent.document.getElementById("player-mobile-container").style.bottom = diff + "px";
                    window.parent.document.getElementById("lyrics-page").classList.remove("mobile-show");
                }
            }
        }

        document.getElementById("act").ontouchstart = (e) => {
            window.initialPosY = e.touches[0].clientY;

            if (window.parent.currentSongID !== null) {
                window.log = true;
                window.parent.document.getElementById("player-mobile-container").style.transition = "";
            }
        }

        document.body.ontouchend = () => {
            if (window.log) {
                window.log = false;
                window.parent.document.getElementById("player-mobile-container").style.transition = "bottom 200ms ease 0s";
                window.parent.document.getElementById("player-mobile-container").style.bottom = "0";
                window.parent.document.getElementById("lyrics-page").classList.add("mobile-show");
                let diff = initialPosY - lastPosY;

                if (diff < 0 && diff <= -10) {
                    window.parent.hideMobilePlayer();
                }
            }
        }
    </script>

    <div id="album-art-bg" style="position: fixed;inset: 0;background-position: center;background-size: cover; z-index: 5;"></div>
    <div id="album-art-bg2" style="background-color: rgba(0, 0, 0, .1); z-index: 10; position: fixed;inset: 0; backdrop-filter: blur(100px);"></div>
    <div id="player" class="bg-white mobile-player" style="background-color: transparent !important; color: white;position: fixed; bottom: var(--android-navigation-bar); left: 0; right: 0; height: 64px; z-index: 9999;">
        <div class="container" style="display: grid; grid-template-columns: 1fr 1.5fr 1fr;">
            <div id="buttons" style="height: 48px;position: fixed;width: max-content;left: 0;right: 0;margin: 8px auto;bottom: calc(30px + var(--android-navigation-bar));">
                <span onclick="window.parent.toggleShuffle();" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-shuffle">
                    <img alt="" src="/assets/icons/shuffle-off.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-shuffle-icon">
                </span>
                <span onclick="window.parent.previous();" class="player-btn disabled" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-previous">
                    <img alt="" src="/assets/icons/previous.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-previous-icon">
                </span>
                <span onclick="window.parent.playPause();" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-play">
                    <img alt="" src="/assets/icons/play.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-play-icon">
                </span>
                <span onclick="window.parent.next();" class="player-btn disabled" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-next">
                    <img alt="" src="/assets/icons/next.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-next-icon">
                </span>
                <span onclick="window.parent.toggleRepeat();" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-repeat">
                    <img alt="" src="/assets/icons/repeat-off.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-repeat-icon">
                </span>
            </div>
            <div style="position: fixed;top: calc(30px + var(--android-status-bar));left: 30px;right: 30px;">
                <img alt="" id="album-art" style="border-radius: 5px; background-color: rgba(0, 0, 0, .1); height: 48px !important; width: 48px !important;">
                <div id="info-grid" style="display: grid;grid-template-rows: 22px 22px;position: fixed;top: calc(30px + var(--android-status-bar));left: 88px;">
                    <div id="info-grid-title" style="white-space: nowrap;overflow: hidden !important;text-overflow: ellipsis;display: flex;font-size: 0.91rem;align-items: end;text-align: left;justify-content: left; opacity: .75;"><span id="title">Title</span></div>
                    <div id="info-grid-info" style="white-space: nowrap;overflow: hidden !important;text-overflow: ellipsis;display: flex;font-size: 0.91rem;align-items: start;text-align: left;justify-content: left;opacity: .35;"><span id="artist">Artist</span><span style="display: none;">&nbsp;—&nbsp;<span id="album">Album</span></span></div>
                    <div id="info-grid-time" style="font-size: 9px;opacity: .5;margin-left: 2px;margin-right: 2px;position: fixed;bottom: calc(125px + var(--android-navigation-bar));left: 30px;right: 30px;">
                        <span id="elapsed-time">0:00</span>
                        <span id="remaining-time" style="float: right;">-0:00</span>
                    </div>
                    <div style="background-color: rgba(255, 255, 255, .05);position: fixed;bottom: calc(150px + var(--android-navigation-bar));left: 30px;right: 30px;border-radius: 999px;" id="seekbar-container">
                        <div id="seekbar" style="pointer-events: none;background-color: rgba(255, 255, 255, .25); width: 0; height: 8px;border-radius: 999px;"></div>
                    </div>
                </div>
            </div>
            <div style="text-align: right; display: flex; align-items: center; justify-content: right;" id="badges">
                <span id="badge-lossy" style="display: none;"></span>
                <span id="badge-cd" style="display: block;border: 1px solid transparent;color: rgba(255, 255, 255, .5);background-color: rgba(255, 255, 255, .1);padding: 2px 5px;border-radius: 5px;font-size: 12px;position: fixed;margin-left: auto;margin-right: auto;width: max-content;left: 0;right: 0;bottom: calc(120px + var(--android-navigation-bar));">
                    <span style="display: grid; grid-template-columns: max-content max-content">
                        <span><img src="/assets/icons/lossless.svg" alt="" class="player-badge-icon" style="filter: invert(1); opacity: .5;">Lossless</span>
                    </span>
                </span>
                <span id="badge-hires" style="display: block;border: 1px solid transparent;color: rgba(255, 255, 255, .5);background-color: rgba(255, 255, 255, .1);padding: 2px 5px;border-radius: 5px;font-size: 12px;position: fixed;margin-left: auto;margin-right: auto;width: max-content;left: 0;right: 0;bottom: calc(120px + var(--android-navigation-bar));">
                    <span style="display: grid; grid-template-columns: max-content max-content">
                        <span><img src="/assets/icons/lossless.svg" alt="" class="player-badge-icon" style="filter: invert(1); opacity: .5;">Hi-Res Lossless</span>
                    </span>
                </span>
                <span id="badge-stella" style="display: block;border: 1px solid transparent;color: rgba(255, 255, 255, .5);background-color: rgba(255, 255, 255, .1);padding: 2px 5px;border-radius: 5px;font-size: 12px;position: fixed;margin-left: auto;margin-right: auto;width: max-content;left: 0;right: 0;bottom: calc(120px + var(--android-navigation-bar));">
                    <span style="display: grid; grid-template-columns: max-content max-content">
                        <span><img src="/assets/icons/stella.svg" alt="" class="player-badge-icon" style="height: 14.4px; width: 14.4px !important; filter: invert(1); opacity: .5;"><img src='/assets/icons/stella-full.svg' class='stella-logo-full'></span>
                        <style>
                            .stella-logo-full {
                                height: 12px;
                                filter: invert(1);
                            }
                        </style>
                    </span>
                </span>
            </div>
        </div>
    </div>
</body>
</html>