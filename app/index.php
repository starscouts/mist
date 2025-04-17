<?php
header("X-Frame-Options: DENY");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $_PROFILE; ?>
<!--

🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️

   __                                _       __    __       __
  / /__________ _____  _____   _____(_)___ _/ /_  / /______/ /
 / __/ ___/ __ `/ __ \/ ___/  / ___/ / __ `/ __ \/ __/ ___/ /
/ /_/ /  / /_/ / / / (__  )  / /  / / /_/ / / / / /_(__  )_/
\__/_/   \__,_/_/ /_/____/  /_/  /_/\__, /_/ /_/\__/____(_)
                                   /____/

🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️🏳️‍⚧️

-->

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mist</title>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>-->
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <script src="/assets/js/normalizer.js"></script>
    <script src="/assets/js/pako.js"></script>
    <script src="/assets/js/stella.js"></script>
    <link rel="shortcut icon" href="/assets/logo-display.svg" type="image/svg+xml">
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-status-bar" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="apple-mobile-web-app-status-bar" content="#000000" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-status-bar-style" content="white-translucent" media="(prefers-color-scheme: light)">
    <meta name="description" content="Mist Audio Player">
    <style>
        body {
            font-family: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue","Noto Sans","Liberation Sans",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
        }
    </style>
</head>
<body <?php if (!str_contains($_SERVER['HTTP_USER_AGENT'], "MistNative/")): ?>class="web"<?php else: ?>class="native"<?php endif; ?>>
    <script src="/assets/js/common.js"></script>
    <script>
        if (location.hash.trim() === "") {
            location.hash = "#/home";
        }

        if (window.MistNative) {
            MistNative.version("<?= explode("|", trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/version")))[0]  ?>", "<?= trim(file_exists($_SERVER['DOCUMENT_ROOT'] . "/build.txt") ? file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/build.txt") : (file_exists("/opt/spotify/build.txt") ? file_get_contents("/opt/spotify/build.txt") : "trunk")) ?>");
            MistNative.userInfo(`<?= str_replace("`", "\\`", json_encode($_PROFILE)) ?>`);
        }
    </script>
    <div id="loading" style="z-index: 999999; position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; background-color: white;">
        <img src="/assets/logo-transparent.svg" style="width: 256px; height: 256px;" alt="Mist">
        <span id="loading-text" style="display: none;">Initializing...</span>
    </div>

    <div id="mobile-navbar" style="padding: 0 10px; border-bottom: 1px solid rgba(0, 0, 0, .1); height: 48px; top: var(--android-status-bar); left: 0; right: 0; position: fixed; display: none; grid-template-columns: max-content 1fr;">
        <div style="display: flex; align-items: center;" onclick="window.back();">
            <img alt="Back" src="/assets/icons/back-mobile.svg" style="height: 32px; width: 32px;">
        </div>
        <div style="display: flex; align-items: center; margin-left: 10px;" id="mobile-navbar-title"></div>
    </div>

    <iframe title="Player" id="player" src="ui/player.php" style="position: fixed; top: var(--android-status-bar); left: 320px; right: 0; width: calc(100vw - 320px); height: 64px; border-bottom: 1px solid rgba(0, 0, 0, .25); z-index: 9999;"></iframe>
    <iframe title="Navigation" id="navigation" src="ui/navigation.php" style="position: fixed; top: 0; bottom: var(--android-navigation-bar); left: 0; height: calc(100vh - var(--android-navigation-bar)); width: 320px; z-index: 9999;"></iframe>
    <iframe title="UI" id="ui" style="position: fixed; top: calc(65px + var(--android-status-bar)); bottom: var(--android-navigation-bar); left: 320px; height: calc(100vh - 64px - var(--android-status-bar) - var(--android-navigation-bar)); width: calc(100vw - 320px); z-index: 9999;"></iframe>
    <iframe title="Lyrics" id="lyrics-page" src="ui/lyrics.php" style="display: none; position: fixed; top: calc(64px + var(--android-status-bar)); bottom: var(--android-navigation-bar); left: 320px; height: calc(100vh - 64px - var(--android-status-bar) - var(--android-navigation-bar)); width: calc(100vw - 320px); z-index: 9999;"></iframe>
    <div id="player-mobile-container" style="background-color: white; position: fixed; left: 0; right: 0; height: 100vh; bottom: -100vh; transition: bottom 200ms; z-index: 99999;">
        <iframe title="Mobile player" style="background: #ddd; width: 100%; height: 100%;" src="ui/player-mobile.php" id="player-mobile"></iframe>
    </div>

    <div id="mouse-logging" style="display: block; inset: 0; position: fixed; z-index: 99999999; pointer-events: none;"></div>
    <iframe id="modal" src="ui/modal.php" style="width: 100vw; height: 100vh; border: none; inset: 0; position: fixed; z-index: 99999999; display: none;"></iframe>

    <script>
        window.modalLoaded = false;
        /*document.getElementById("modal").onload = () => {
            window.modalLoaded = true;

            if (localStorage.getItem("welcomed") !== "true") {
                openModal("Welcome to Mist", "welcome.php", true);
            } else {
                if (localStorage.getItem("lastUpdate") !== "<?= trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/version")) ?>") {
                    openModal("What's new in Mist?", "update.php", true);
                }
            }
        }*/

        window.onerror = (_1, _2, _3, _4, err) => {
            let loadWait = setInterval(() => {
                if (window.modalLoaded) {
                    clearInterval(loadWait);

                    document.getElementById("modal").style.display = "";
                    document.getElementById("modal").contentDocument.getElementById("error-content").innerText = err.stack;
                    document.getElementById("modal").contentWindow._error.show();

                    setInterval(() => {
                        document.getElementById("modal").contentWindow._modal.hide();
                        window.parent.document.getElementById("modal").style.display = "";
                    });
                }
            });
        }

        window.back = () => {
            if (document.getElementById('ui').contentWindow.history.length > 1) {
                document.getElementById('ui').contentWindow.history.back();
            } else {
                if (window.MistAndroid) {
                    window.MistAndroid.quitApp();
                }
            }
        }

        window.playlist = [];

        window.showLyrics = () => {
            location.hash = "#/lyrics";
            document.getElementById("lyrics-page").style.display = "";
            document.getElementById("ui").style.display = "none";
            Array.from(document.getElementById("navigation").contentDocument.getElementsByClassName("navigation-item")).map(i => i.classList.remove("active"));
            if (document.getElementById("navigation").contentDocument.getElementById("lyrics")) document.getElementById("navigation").contentDocument.getElementById("lyrics").classList.add("active");
        }

        function openUI(name) {
            hideMobilePlayer();
            location.hash = "#/" + name;
            document.getElementById("lyrics-page").style.display = "none";
            document.getElementById("ui").style.display = "";
            document.getElementById("ui").src = "ui/" + name + ".php";
            Array.from(document.getElementById("navigation").contentDocument.getElementsByClassName("navigation-item")).map(i => i.classList.remove("active"));
            if (document.getElementById("navigation").contentDocument.getElementById(name)) document.getElementById("navigation").contentDocument.getElementById(name).classList.add("active");
        }

        window.onhashchange = window.loadHash = () => {
            window.name = location.hash.split("/")[1];

            function setSrcIfDifferent(src) {
                if (document.getElementById("ui").contentWindow.location.pathname.substring(5) !== src) {
                    document.getElementById("ui").src = src;
                }
            }

            if (name === "lyrics") {
                showLyrics();
            } else if (name === "albums" && location.hash.split("/")[2]) {
                document.getElementById("lyrics-page").style.display = "none";
                document.getElementById("ui").style.display = "";
                setSrcIfDifferent("ui/listing.php?a=" + location.hash.split("/")[2]);
            } else if (name === "favorites" && location.hash.split("/")[2]) {
                document.getElementById("lyrics-page").style.display = "none";
                document.getElementById("ui").style.display = "";
                setSrcIfDifferent("ui/favorites.php?u=" + location.hash.split("/")[2]);
            } else if (name === "search" && location.hash.split("/")[2]) {
                document.getElementById("lyrics-page").style.display = "none";
                document.getElementById("ui").style.display = "";
                setSrcIfDifferent("ui/search.php?q=" + location.hash.split("/")[2]);
                name = "home";
            } else {
                document.getElementById("lyrics-page").style.display = "none";
                document.getElementById("ui").style.display = "";
                setSrcIfDifferent("ui/" + name + ".php");
            }
        }

        window.setPlayerVolume = () => {
            let volume = parseFloat(document.getElementById("player").contentDocument.getElementById("volume").value);
            localStorage.setItem("volume", volume);

            document.getElementById("player").contentDocument.getElementById("player-audio").volume = volume / 100;
            document.getElementById("player").contentDocument.getElementById("player-audio-stella-side1").volume = volume / 100;
            document.getElementById("player").contentDocument.getElementById("player-audio-stella-side2").volume = volume / 100;
            document.getElementById("player").contentDocument.getElementById("player-audio-stella-side3").volume = volume / 100;
            document.getElementById("player").contentDocument.getElementById("player-audio-stella-side4").volume = volume / 100;
            document.getElementById("player").contentDocument.getElementById("player-audio-stella-side5").volume = volume / 100;
        }

        loadHash();

        document.getElementById("ui").onload = () => {
            window.resizeHandler();
            document.getElementById("mobile-navbar-title").innerText = document.getElementById("ui").contentDocument.title;
        }

        document.getElementById("navigation").onload = window.redoNavigation = (name) => {
            class MistNavigationError extends Error {
                constructor(message, stack) {
                    super(message);
                    this.name = "MistNavigationError";
                    this.message = message;
                    this.stack = this.stack + "\n\n" + stack;
                }
            }

            try {
                if (!name || typeof name !== "string") name = window.name;
                if (name === "lyrics") showLyrics();
                if (name === "search") name = "home";
                if (name === "stella") name = "settings";
                if (name === "video") name = "settings";
                if (name === "ending") name = "settings";
                Array.from(document.getElementById("navigation").contentDocument.getElementsByClassName("navigation-item")).map(i => i.classList.remove("active"));
                document.getElementById("navigation").contentDocument.getElementById(name).classList.add("active");
            } catch (e) {
                throw new MistNavigationError("Error while navigating to " + name + ": " + e.message, e.stack);
            }
        }

        let loadedPlayers = 0;

        document.getElementById("player").onload = document.getElementById("player-mobile").onload = () => {
            loadedPlayers++;
            if (loadedPlayers === 2) {
                window.resizeHandler();
                continueLoading();
            }
        }

        window.onresize = window.onload = window.resizeHandler = () => {
            if (window.innerWidth <= 863) {
                if (document.getElementById("player").contentDocument && document.getElementById("player").contentDocument.getElementById("player")) document.getElementById("player").contentDocument.getElementById("player").classList.add("mobilified");
                if (document.getElementById("ui").contentDocument && document.getElementById("ui").contentDocument.body) document.getElementById("ui").contentDocument.body.classList.add("mobile-ui");
            } else {
                if (document.getElementById("player").contentDocument && document.getElementById("player").contentDocument.getElementById("player")) document.getElementById("player").contentDocument.getElementById("player").classList.remove("mobilified");
                if (document.getElementById("ui").contentDocument && document.getElementById("ui").contentDocument.body) document.getElementById("ui").contentDocument.body.classList.remove("mobile-ui");
            }
        }

        window.needInitializeNormalizer = false;

        function continueLoading() {
            window.playerDocument = document.getElementById("player").contentDocument;
            window.playerDocumentMobile = document.getElementById("player-mobile").contentDocument;
            window.needInitializeNormalizer = true;

            if (!localStorage.getItem("data-saving")) {
                localStorage.setItem("data-saving", "false");
            }

            localStorage.setItem("show-stella-settings", "true");

            if (!localStorage.getItem("normalize")) {
                localStorage.setItem("normalize", "true");
            }

            if (!localStorage.getItem("enable-stella")) {
                localStorage.setItem("enable-stella", "true");
            }

            if (!localStorage.getItem("overamp")) {
                localStorage.setItem("overamp", "false");
            }

            if (!localStorage.getItem("desktop-notification")) {
                localStorage.setItem("desktop-notification", "true");
            }

            playerDocument.getElementById("seekbar-container").onclick = (e) => {
                playerDocument.getElementById("player-audio").currentTime = (e.offsetX / playerDocument.getElementById("seekbar-container").clientWidth) * playerDocument.getElementById("player-audio").duration;

                if (playingStella) {
                    playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                }
            }

            playerDocumentMobile.getElementById("seekbar-container").onclick = (e) => {
                playerDocument.getElementById("player-audio").currentTime = (e.offsetX / playerDocumentMobile.getElementById("seekbar-container").clientWidth) * playerDocument.getElementById("player-audio").duration;

                if (playingStella) {
                    playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                }
            }

            function parseTime(subject, max) {
                let minutesLength = Math.ceil(max / 60).toString().length;

                let minutes = Math.floor(subject / 60);
                let seconds = Math.floor(subject - (minutes * 60));

                let minutesStr = "0".repeat(minutesLength).substring(0, minutesLength - minutes.toString().length) + minutes.toString();
                let secondsStr = "00".substring(0, 2 - seconds.toString().length) + seconds.toString();

                return minutesStr + ":" + secondsStr;
            }

            playerDocument.getElementById("player-audio").onended = () => {
                next();
            }

            window.seekTo = (time) => {
                document.getElementById("player").contentDocument.getElementById("player-audio").currentTime = time;
                updateAndroidNotification();

                if (playingStella) {
                    playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                    playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                }
            }

            window.stop = () => {
                if (window.MistAndroid) {
                    window.MistAndroid.removeNotification();
                    window.MistAndroid.removeService();
                }

                if (window.discordRichPresenceData !== null) window.discordRichPresenceData = {
                    largeImageKey: "logo",
                    buttons : [
                        { label: 'View profile', url: 'https://mist.equestria.horse/profile/?/<?= $_PROFILE["id"] ?>' }
                    ]
                };

                document.title = "Mist";
                window.currentSong = null;
                window.currentSongID = null;
                window.currentPlaylistID = null;
                window.playlist = [];

                document.getElementById("player").contentWindow.location.reload();
                document.getElementById("player").onload = () => {
                    window.playerDocument = document.getElementById("player").contentDocument;
                    window.needInitializeNormalizer = true;

                    window.resizeHandler();
                    initializePlayerDocument();
                }

                document.getElementById("player-mobile").contentWindow.location.reload();
                document.getElementById("player-mobile").onload = () => {
                    window.playerDocumentMobile = document.getElementById("player-mobile").contentDocument;
                }

                hideMobilePlayer();
            }

            window.showMobilePlayer = () => {
                if (window.MistAndroid) window.MistAndroid.setStatusBarTheme(false);

                if (window.currentSongID !== null) {
                    document.getElementById("player-mobile-container").style.bottom = "0";
                    document.getElementById("lyrics-page").classList.add("mobile-show");
                    document.getElementById("lyrics-page").style.pointerEvents = "none";
                }
            }

            window.hideMobilePlayer = () => {
                if (window.MistAndroid) window.MistAndroid.setStatusBarTheme(true);

                document.getElementById("player-mobile-container").style.bottom = "-100vh";
                document.getElementById("lyrics-page").classList.remove("mobile-show");
                document.getElementById("lyrics-page").style.pointerEvents = "";
            }

            document.getElementById("player-mobile-container").onclick = (e) => {
                if (e.target.id === "player-mobile-container") {
                    hideMobilePlayer();
                }
            }

            window.currentPlaylistPosition = 0;
            window.buffering = false;
            window.calledNextRecently = false;

            window.next = () => {
                if (window.calledNextRecently) return;
                window.calledNextRecently = true;

                if (window.repeat) {
                    playlist.push(playlist[currentPlaylistPosition]);
                }

                if (playlist[currentPlaylistPosition + 1]) {
                    currentPlaylistPosition++;
                    playSong(playlist[currentPlaylistPosition], "keep", false);
                } else {
                    stop();
                }
            }

            window.previous = () => {
                if (window.calledNextRecently) return;
                window.calledNextRecently = true;

                if (playlist[currentPlaylistPosition - 1]) {
                    playSong(playlist[currentPlaylistPosition - 1], "keep");
                    currentPlaylistPosition--;
                } else {
                    stop();
                }
            }

            function initializePlayerDocument() {
                playerDocument.getElementById("player-audio").ontimeupdate = playerDocument.getElementById("player-audio").onchange = playerDocument.getElementById("player-audio").onunload = playerDocument.getElementById("player-audio").onstop = () => {
                    updateDisplay(false);

                    if (playingStella) {
                        if (playerDocument.getElementById("player-audio").paused) {
                            if (!playerDocument.getElementById("player-audio-stella-side1").paused) {
                                playerDocument.getElementById("player-audio-stella-side1").pause();
                                playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (!playerDocument.getElementById("player-audio-stella-side2").paused) {
                                playerDocument.getElementById("player-audio-stella-side2").pause();
                                playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (!playerDocument.getElementById("player-audio-stella-side3").paused) {
                                playerDocument.getElementById("player-audio-stella-side3").pause();
                                playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (!playerDocument.getElementById("player-audio-stella-side4").paused) {
                                playerDocument.getElementById("player-audio-stella-side4").pause();
                                playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (!playerDocument.getElementById("player-audio-stella-side5").paused) {
                                playerDocument.getElementById("player-audio-stella-side5").pause();
                                playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                        } else {
                            if (playerDocument.getElementById("player-audio-stella-side1").paused) {
                                playerDocument.getElementById("player-audio-stella-side1").play();
                                playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (playerDocument.getElementById("player-audio-stella-side2").paused) {
                                playerDocument.getElementById("player-audio-stella-side2").play();
                                playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (playerDocument.getElementById("player-audio-stella-side3").paused) {
                                playerDocument.getElementById("player-audio-stella-side3").play();
                                playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (playerDocument.getElementById("player-audio-stella-side4").paused) {
                                playerDocument.getElementById("player-audio-stella-side4").play();
                                playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                            if (playerDocument.getElementById("player-audio-stella-side5").paused) {
                                playerDocument.getElementById("player-audio-stella-side5").play();
                                playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                        }
                    }
                }

                playerDocument.getElementById("player-audio").onplay = () => {
                    document.getElementById("lyrics-page").contentWindow.updateVideo();
                    updateAndroidNotification();

                    if (window.preloadedGains[window.currentSongID]) {
                        window.currentNormalizationSource.connect(window.preloadedGains[window.currentSongID]);

                        if (playingStella) {
                            window.currentNormalizationSource2.connect(window.preloadedGainsBoosted1[window.currentSongID]);
                            window.currentNormalizationSource3.connect(window.preloadedGainsBoosted2[window.currentSongID]);
                        }

                        window.preloadedGains[window.currentSongID].connect(window.currentNormalizationContext.destination);
                    }

                    if (playingStella) {
                        for (let player of [
                            window.currentNormalizationSource2,
                            window.currentNormalizationSource3,
                            window.currentNormalizationSource4,
                            window.currentNormalizationSource5
                        ]) {
                            player.connect(window.preloadedGainsBoosted1[window.currentSongID]);
                            window.preloadedGainsBoosted1[window.currentSongID].connect(window.currentNormalizationContext.destination);
                        }

                        window.currentNormalizationSource1.connect(window.preloadedGainsBoosted2[window.currentSongID]);
                        window.preloadedGainsBoosted2[window.currentSongID].connect(window.currentNormalizationContext.destination);
                    }

                    updateDisplay(false);
                }

                window.needsDurationChange = true;

                playerDocument.getElementById("player-audio").ondurationchange = () => {
                    if (window.needsDurationChange) {
                        updateAndroidNotification();
                        window.needsDurationChange = false;
                    }
                }

                playerDocument.getElementById("player-audio").onpause = () => {
                    document.getElementById("lyrics-page").contentWindow.updateVideo();

                    if (window.preloadedGains[window.currentSongID]) {
                        try {
                            window.currentNormalizationSource.disconnect();

                            if (playingStella) {
                                window.currentNormalizationSource2.disconnect();
                                window.currentNormalizationSource3.disconnect();
                            }

                            window.preloadedGains[window.currentSongID].disconnect();
                        } catch (e) {
                            console.error(e);
                        }

                        if (playingStella) {
                            for (let player of [
                                window.currentNormalizationSource2,
                                window.currentNormalizationSource3,
                                window.currentNormalizationSource4,
                                window.currentNormalizationSource5
                            ]) {
                                try {
                                    player.disconnect();
                                    window.preloadedGainsBoosted1[window.currentSongID].disconnect();
                                } catch (e) {
                                    console.error(e);
                                }
                            }

                            try {
                                window.currentNormalizationSource1.disconnect();
                                window.preloadedGainsBoosted2[window.currentSongID].disconnect();
                            } catch (e) {
                                console.error(e);
                            }
                        }
                    }

                    if (playerDocument.getElementById("player-audio").currentTime >= playerDocument.getElementById("player-audio").duration) {
                        next();
                        return;
                    }

                    updateDisplay(false);
                    updateAndroidNotification();
                }
            }

            initializePlayerDocument();

            function updateAndroidNotification() {
                if (window.MistAndroid && currentSong) {
                    window.MistAndroid.setNotificationData(currentSong.title, currentSong.artist, currentSong.album, Math.round(playerDocument.getElementById("player-audio").currentTime * 1000), Math.round(playerDocument.getElementById("player-audio").duration * 1000), !playerDocument.getElementById("player-audio").paused, buffering);
                }
            }

            window.updateDisplay = (initial) => {
                if (initial) {
                    if (playerDocument.getElementById("player-audio").paused) {
                        document.title = "Mist";
                    } else if (currentSong) {
                        document.title = currentSong.artist + " — " + currentSong.title;
                    } else {
                        document.title = "Mist";
                    }

                    if (window.discordRichPresenceData !== null && currentSong) window.discordRichPresenceData = {
                        largeImageKey: "https://" + location.hostname + "/albumart.php?i=" + currentSongID,
                        buttons : [
                            { label: 'View profile', url: 'https://mist.equestria.horse/profile/?/<?= $_PROFILE["id"] ?>' }
                        ],
                        state: currentSong.title,
                        details: currentSong.artist,
                        smallImageKey: "logo",
                        smallImageText: "Listening on Mist",
                        largeImageText: currentSong.album ?? currentSong.artist
                    };

                    playerDocument.getElementById("info").style.display = "grid";
                    playerDocument.getElementById("cover").style.display = "none";
                }

                if ('mediaSession' in document.getElementById("player").contentWindow.navigator) {
                    if (initial) {
                        document.getElementById("player").contentWindow.navigator.mediaSession.playbackState = playerDocument.getElementById("player-audio").paused ? "paused" : "playing";

                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("play", () => {
                            playPause();
                        });
                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("pause", () => {
                            playPause();
                        });
                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("stop", () => {
                            stop();
                        });
                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("seekbackward", (e) => {
                            let time = e.seekOffset ?? 10;

                            if (playerDocument.getElementById("player-audio").currentTime >= time) {
                                playerDocument.getElementById("player-audio").currentTime -= time;
                            } else {
                                playerDocument.getElementById("player-audio").currentTime = 0;
                            }

                            if (playingStella) {
                                playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                            }
                        });
                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("seekforward", (e) => {
                            let time = e.seekOffset ?? 10;

                            if (playerDocument.getElementById("player-audio").currentTime + time < playerDocument.getElementById("player-audio").duration) {
                                playerDocument.getElementById("player-audio").currentTime += time;

                                if (playingStella) {
                                    playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                }
                            } else {
                                next();
                            }
                        });
                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("seekto", (e) => {
                            if (e.seekTime) {
                                playerDocument.getElementById("player-audio").currentTime = e.seekTime;

                                if (playingStella) {
                                    playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                }
                            }
                        });
                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("previoustrack", () => {
                            if (playlist[currentPlaylistPosition - 1]) {
                                previous();
                            } else {
                                playerDocument.getElementById("player-audio").currentTime = 0;

                                if (playingStella) {
                                    playerDocument.getElementById("player-audio-stella-side1").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side2").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side3").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side4").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                    playerDocument.getElementById("player-audio-stella-side5").currentTime = playerDocument.getElementById("player-audio").currentTime;
                                }
                            }
                        });
                        document.getElementById("player").contentWindow.navigator.mediaSession.setActionHandler("nexttrack", () => {
                            next();
                        });
                    }

                    let state = {
                        duration: isFinite(playerDocument.getElementById("player-audio").duration) ? playerDocument.getElementById("player-audio").duration : 0,
                        position: playerDocument.getElementById("player-audio").currentTime,
                        playbackRate: 1
                    }
                    document.getElementById("player").contentWindow.navigator.mediaSession.setPositionState(state);
                }

                if (initial) {
                    if (currentPlaylistPosition === 0) {
                        playerDocument.getElementById("btn-previous").classList.add("disabled");
                        playerDocumentMobile.getElementById("btn-previous").classList.add("disabled");
                    } else {
                        playerDocument.getElementById("btn-previous").classList.remove("disabled");
                        playerDocumentMobile.getElementById("btn-previous").classList.remove("disabled");
                    }

                    if (currentPlaylistPosition === playlist.length - 1) {
                        playerDocument.getElementById("btn-next").classList.add("disabled");
                        playerDocumentMobile.getElementById("btn-next").classList.add("disabled");
                    } else {
                        playerDocument.getElementById("btn-next").classList.remove("disabled");
                        playerDocumentMobile.getElementById("btn-next").classList.remove("disabled");
                    }
                }

                if (initial) {
                    if (window.currentSong) {
                        playerDocument.getElementById("title").innerText = playerDocumentMobile.getElementById("title").innerText = window.currentSong.title;
                        playerDocument.getElementById("artist").innerText = playerDocumentMobile.getElementById("artist").innerText = window.currentSong.artist;
                        playerDocument.getElementById("album").innerText = playerDocumentMobile.getElementById("album").innerText = window.currentSong.album;
                        playerDocument.getElementById("album-art").src = playerDocumentMobile.getElementById("album-art").src = "/assets/content/" + window.currentSongID + ".jpg";
                        playerDocumentMobile.getElementById("album-art-bg").style.backgroundImage = "url('/assets/content/" + window.currentSongID + ".jpg')";
                    }
                }

                if (document.hidden) return;

                if (isFinite(playerDocument.getElementById("player-audio").duration)) {
                    playerDocument.getElementById("seekbar").style.width = playerDocumentMobile.getElementById("seekbar").style.width = ((playerDocument.getElementById("player-audio").currentTime / playerDocument.getElementById("player-audio").duration) * 100) + "%";
                } else {
                    playerDocument.getElementById("seekbar").style.width = playerDocumentMobile.getElementById("seekbar").style.width = "0";
                }

                if (isFinite(playerDocument.getElementById("player-audio").duration)) {
                    playerDocument.getElementById("elapsed-time").innerText = playerDocumentMobile.getElementById("elapsed-time").innerText = parseTime(playerDocument.getElementById("player-audio").currentTime, playerDocument.getElementById("player-audio").duration);
                    playerDocument.getElementById("remaining-time").innerText = playerDocumentMobile.getElementById("remaining-time").innerText = "-" + parseTime(playerDocument.getElementById("player-audio").duration - playerDocument.getElementById("player-audio").currentTime, playerDocument.getElementById("player-audio").duration);
                } else {
                    playerDocument.getElementById("elapsed-time").innerText = playerDocumentMobile.getElementById("elapsed-time").innerText = parseTime(0, currentSong.length);
                    playerDocument.getElementById("remaining-time").innerText = playerDocumentMobile.getElementById("remaining-time").innerText = "-" + parseTime(currentSong.length, currentSong.length);
                }

                if (playerDocument.getElementById("player-audio").paused) {
                    playerDocument.getElementById("btn-play-icon").src = playerDocumentMobile.getElementById("btn-play-icon").src = "/assets/icons/play.svg";
                } else {
                    playerDocument.getElementById("btn-play-icon").src = playerDocumentMobile.getElementById("btn-play-icon").src = "/assets/icons/pause.svg";
                }
            }

            window.playPause = () => {
                if (playlist.length === 0) return;

                if (playerDocument.getElementById("player-audio").paused) {
                    playerDocument.getElementById("player-audio").play();
                    if (window.MistAndroid) window.MistAndroid.updateNotificationAlbumArt("https://" + location.hostname + "/albumart.php?i=" + currentSongID);
                } else {
                    playerDocument.getElementById("player-audio").pause();
                    if (window.MistAndroid) window.MistAndroid.removeService();
                }
            }

            window.redownloadFavorites = async () => {
                document.getElementById("loading-text").innerText = "Downloading favorites...";
                window.favorites = await (await fetch("/api/getFavorites.php?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();
            }

            window.redownloadLibrary = async () => {
                document.getElementById("loading-text").innerText = "Downloading library...";
                window.library = await (await fetch("/api/getLibrary.php?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();
            }

            window.redownloadMedia = async () => {
                document.getElementById("loading-text").innerText = "Downloading list of songs...";
                window.songs = await (await fetch("/assets/content/songs.json?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();

                document.getElementById("loading-text").innerText = "Downloading list of albums...";
                window.albums = await (await fetch("/assets/content/albums.json?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();
            }

            (async () => {
                document.getElementById("loading-text").innerText = "Downloading list of songs...";
                window.songs = await (await fetch("/assets/content/songs.json?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();

                document.getElementById("loading-text").innerText = "Downloading list of albums...";
                window.albums = await (await fetch("/assets/content/albums.json?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();

                document.getElementById("loading-text").innerText = "Downloading favorites...";
                window.favorites = await (await fetch("/api/getFavorites.php?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();

                document.getElementById("loading-text").innerText = "Downloading library...";
                window.library = await (await fetch("/api/getLibrary.php?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();

                document.getElementById("loading-text").innerText = "Saving database...";
                await localforage.setItem("albums", window.albums);
                await localforage.setItem("songs", window.songs);
                await localforage.setItem("favorites", window.favorites);
                await localforage.setItem("library", window.library);

                document.getElementById("loading-text").innerText = "Done loading.";
                document.getElementById("loading").style.display = "none";
                eolModal();
            })();

            window.playingStella = false;
            window.currentSong = null;
            window.currentSongID = null;
            window.preloaded = {};
            window.preloadedURLs = {};
            window.preloadedGains = {};
            window.preloadedGainsBoosted1 = {};
            window.preloadedGainsBoosted2 = {};
            window.preloadedBlobs = {};
            window.shuffle = false;
            window.repeat = false;

            window.toggleRepeat = () => {
                window.repeat = !window.repeat;
                updateRepeat();
            }

            window.setRepeat = (value) => {
                window.repeat = value;
                updateRepeat();
            }

            window.updateRepeat = () => {
                if (window.currentSong) {
                    updateDisplay(false);
                }

                if (window.repeat) {
                    playerDocument.getElementById("btn-repeat-icon").src = playerDocumentMobile.getElementById("btn-repeat-icon").src = "/assets/icons/repeat-on.svg";
                } else {
                    playerDocument.getElementById("btn-repeat-icon").src = playerDocumentMobile.getElementById("btn-repeat-icon").src = "/assets/icons/repeat-off.svg";
                }
            }

            function shuffleArray(array) {
                let currentIndex = array.length, temporaryValue, randomIndex;

                while (0 !== currentIndex) {
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;

                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            }

            window.toggleShuffle = () => {
                window.shuffle = !window.shuffle;
                updateShuffle();
            }

            window.setShuffle = (value) => {
                window.shuffle = value;
                updateShuffle();
            }

            window.updateShuffle = () => {
                if (window.currentSong) {
                    updateDisplay(false);
                }

                if (window.shuffle) {
                    window.playlist = shuffleArray(window.playlist);
                    playerDocument.getElementById("btn-shuffle-icon").src = playerDocumentMobile.getElementById("btn-shuffle-icon").src = "/assets/icons/shuffle-on.svg";
                } else {
                    if (currentPlaylistID) {
                        if (currentPlaylistID === "favorites") {
                            window.playlist = favorites;
                        } else if (currentPlaylistID.startsWith("album:")) {
                            window.playlist = albums[currentPlaylistID.substring(6)].tracks;
                        } else if (currentPlaylistID !== "keep") {
                            window.playlist = [currentSongID];
                        }
                    } else {
                        window.playlist = [currentSongID];
                    }

                    playerDocument.getElementById("btn-shuffle-icon").src = playerDocumentMobile.getElementById("btn-shuffle-icon").src = "/assets/icons/shuffle-off.svg";
                }
            }

            window.shuffleList = async (playlistID) => {
                window.shuffle = true;
                playerDocument.getElementById("btn-shuffle-icon").src = playerDocumentMobile.getElementById("btn-shuffle-icon").src = "/assets/icons/shuffle-on.svg";

                if (playlistID) {
                    if (playlistID === "favorites") {
                        window.currentPlaylistID = playlistID;
                        window.playlist = await (await fetch("/api/randomFavorites.php")).json();
                    } else if (playlistID.startsWith("album:")) {
                        window.currentPlaylistID = playlistID;
                        window.playlist = albums[playlistID.substring(6)].tracks;
                    } else if (playlistID !== "keep") {
                        window.playlist = [];
                    }
                } else {
                    window.currentPlaylistID = null;
                    window.playlist = [];
                }

                if (playlistID && playlistID !== "favorites") {
                    window.playlist = shuffleArray(window.playlist);
                }

                window.playSong(window.playlist[0], "keep");
            }

            window.currentPlaylistID = null;

            window.playSong = async (id, playlistID, updatePosition) => {
                playerDocument.getElementById("badge-cd").style.display = "none";
                playerDocument.getElementById("badge-stella").style.display = "none";
                playerDocument.getElementById("badge-hires").style.display = "none";
                playerDocumentMobile.getElementById("badge-cd").style.display = "none";
                playerDocumentMobile.getElementById("badge-stella").style.display = "none";
                playerDocumentMobile.getElementById("badge-hires").style.display = "none";
                document.getElementById("player").contentWindow.buildTooltips();

                if (!window.currentNormalizationContext) {
                    window.currentNormalizationContext = new AudioContext();
                    window.currentNormalizationContext2 = new AudioContext();
                    window.currentNormalizationContext3 = new AudioContext();
                }

                if (window.needInitializeNormalizer) {
                    window.currentNormalizationSource = window.currentNormalizationContext.createMediaElementSource(playerDocument.getElementById("player-audio"));
                    window.currentNormalizationSource1 = window.currentNormalizationContext.createMediaElementSource(playerDocument.getElementById("player-audio-stella-side1"));
                    window.currentNormalizationSource2 = window.currentNormalizationContext.createMediaElementSource(playerDocument.getElementById("player-audio-stella-side2"));
                    window.currentNormalizationSource3 = window.currentNormalizationContext.createMediaElementSource(playerDocument.getElementById("player-audio-stella-side3"));
                    window.currentNormalizationSource4 = window.currentNormalizationContext.createMediaElementSource(playerDocument.getElementById("player-audio-stella-side4"));
                    window.currentNormalizationSource5 = window.currentNormalizationContext.createMediaElementSource(playerDocument.getElementById("player-audio-stella-side5"));
                    window.needInitializeNormalizer = false;
                }

                playerDocument.getElementById("player-audio").pause();
                playerDocument.getElementById("player-audio-stella-side1").pause();
                playerDocument.getElementById("player-audio-stella-side2").pause();
                playerDocument.getElementById("player-audio-stella-side3").pause();
                playerDocument.getElementById("player-audio-stella-side4").pause();
                playerDocument.getElementById("player-audio-stella-side5").pause();
                playerDocument.getElementById("player-audio").currentTime = 0;
                window.playingStella = false;

                if (playlistID) {
                    if (playlistID === "favorites") {
                        window.currentPlaylistID = playlistID;
                        window.playlist = favorites;
                        window.currentPlaylistPosition = favorites.indexOf(id) ?? 0;
                    } else if (playlistID.startsWith("album:")) {
                        window.currentPlaylistID = playlistID;
                        window.playlist = albums[playlistID.substring(6)].tracks;
                        window.currentPlaylistPosition = albums[playlistID.substring(6)].tracks.indexOf(id) ?? 0;
                    } else if (playlistID !== "keep") {
                        window.playlist = [id];
                        window.currentPlaylistPosition = 0;
                    } else if (updatePosition !== false) {
                        window.currentPlaylistPosition = window.playlist.indexOf(id) ?? 0;
                    }
                } else {
                    window.currentPlaylistID = null;
                    window.playlist = [id];
                    window.currentPlaylistPosition = 0;
                }

                window.currentSong = songs[id];
                window.currentSongID = id;
                updateDisplay(true);
                updateAndroidNotification();
                if (document.getElementById("ui").contentWindow.refreshQueue) document.getElementById("ui").contentWindow.refreshQueue();

                let stellaCompatible = false;

                if (localStorage.getItem("enable-stella") === "true" && localStorage.getItem("data-saving") !== "true") {
                    stellaCompatible = await (await fetch("/api/hasStella.php?id=" + id)).text() === "true";
                }

                if (stellaCompatible) {
                    if (!window.preloaded[id]) {
                        window.preloaded[id] = await Stella.build("/assets/content/" + id + ".stella");
                        window.preloadedGains[id] = await normalizeAudio(window.preloaded[id].stems.vocals.buffer, 0, true);
                        window.preloadedGainsBoosted1[id] = await normalizeAudio(window.preloaded[id].stems.vocals.buffer, .05, true);
                        window.preloadedGainsBoosted2[id] = await normalizeAudio(window.preloaded[id].stems.vocals.buffer, .1, true);
                    }
                } else {
                    if (!window.preloaded[id]) {
                        window.buffering = true;

                        if (localStorage.getItem("data-saving") === "true") {
                            window.preloaded[id] = await (await fetch("/assets/content/" + id + ".m4a")).arrayBuffer();
                            window.preloadedBlobs[id] = new Blob([window.preloaded[id]], { type: "audio/mp4" });
                        } else {
                            window.preloaded[id] = await (await fetch("/assets/content/" + id + ".flac")).arrayBuffer();
                            window.preloadedBlobs[id] = new Blob([window.preloaded[id]], { type: "audio/flac" });
                        }

                        window.preloadedGains[id] = await normalizeAudio(window.preloaded[id], 0);
                        window.preloadedGainsBoosted1[id] = await normalizeAudio(window.preloaded[id], .05);
                        window.preloadedGainsBoosted2[id] = await normalizeAudio(window.preloaded[id], .1);
                    }
                }

                cleanupPreload();
                preloadMore();
                window.needsDurationChange = true;

                if (window.currentSongID !== id) return;

                try {
                    window.currentNormalizationSource.disconnect();

                    if (playingStella) {
                        window.currentNormalizationSource2.disconnect();
                        window.currentNormalizationSource3.disconnect();
                    }

                    window.preloadedGains[window.currentSongID].disconnect();
                } catch (e) {
                    console.error(e);
                }

                if (playingStella) {
                    for (let player of [
                        window.currentNormalizationSource2,
                        window.currentNormalizationSource3,
                        window.currentNormalizationSource4,
                        window.currentNormalizationSource5
                    ]) {
                        try {
                            player.disconnect();
                            window.preloadedGainsBoosted1[window.currentSongID].disconnect();
                        } catch (e) {
                            console.error(e);
                        }
                    }

                    try {
                        window.currentNormalizationSource1.disconnect();
                        window.preloadedGainsBoosted2[window.currentSongID].disconnect();
                    } catch (e) {
                        console.error(e);
                    }
                }

                if (!stellaCompatible) {
                    if (!window.preloadedURLs[id]) {
                        window.preloadedURLs[id] = URL.createObjectURL(window.preloadedBlobs[id]);
                    }
                } else {
                    window.playingStella = true;
                    playerDocument.getElementById("player-audio").src = window.preloaded[id].urls.hpf;
                    playerDocument.getElementById("player-audio-stella-side1").src = window.preloaded[id].urls.bass;
                    playerDocument.getElementById("player-audio-stella-side2").src = window.preloaded[id].urls.drums;
                    playerDocument.getElementById("player-audio-stella-side3").src = window.preloaded[id].urls.other;
                    playerDocument.getElementById("player-audio-stella-side4").src = window.preloaded[id].urls.piano;
                    playerDocument.getElementById("player-audio-stella-side5").src = window.preloaded[id].urls.vocals;
                    playerDocument.getElementById("player-audio").play();
                    window.buffering = false;
                }

                if (!stellaCompatible) {
                    playerDocument.getElementById("player-audio").src = window.preloadedURLs[id];
                    playerDocument.getElementById("player-audio").play();
                    window.buffering = false;
                }

                window.calledNextRecently = false;

                if (localStorage.getItem("data-saving") === "true") {
                    playerDocument.getElementById("badge-cd").style.display = "none";
                    playerDocument.getElementById("badge-stella").style.display = "none";
                    playerDocument.getElementById("badge-hires").style.display = "none";
                    playerDocumentMobile.getElementById("badge-cd").style.display = "none";
                    playerDocumentMobile.getElementById("badge-stella").style.display = "none";
                    playerDocumentMobile.getElementById("badge-hires").style.display = "none";
                    document.getElementById("player").contentWindow.buildTooltips();
                } else {
                    if (window.playingStella) {
                        playerDocument.getElementById("badge-cd").style.display = "none";
                        playerDocument.getElementById("badge-stella").style.display = "inline";
                        playerDocument.getElementById("badge-hires").style.display = "none";
                        playerDocumentMobile.getElementById("badge-cd").style.display = "none";
                        playerDocumentMobile.getElementById("badge-stella").style.display = "inline";
                        playerDocumentMobile.getElementById("badge-hires").style.display = "none";
                        document.getElementById("player").contentWindow.buildTooltips();
                    } else {
                        if (window.currentSong && window.currentSong.hiRes) {
                            playerDocument.getElementById("badge-cd").style.display = "none";
                            playerDocument.getElementById("badge-stella").style.display = "none";
                            playerDocument.getElementById("badge-hires").style.display = "inline";
                            playerDocument.getElementById("badge-hires").title = "<b>Hi-Res Lossless</b><br>" + window.currentSong.bitDepth + "-bit " + (window.currentSong.sampleRate / 1000) + " kHz";
                            playerDocumentMobile.getElementById("badge-cd").style.display = "none";
                            playerDocumentMobile.getElementById("badge-stella").style.display = "none";
                            playerDocumentMobile.getElementById("badge-hires").style.display = "inline";
                            document.getElementById("player").contentWindow.buildTooltips();
                        } else if (window.currentSong) {
                            playerDocument.getElementById("badge-cd").style.display = "inline";
                            playerDocument.getElementById("badge-hires").style.display = "none";
                            playerDocumentMobile.getElementById("badge-cd").style.display = "inline";
                            playerDocumentMobile.getElementById("badge-stella").style.display = "none";
                            playerDocumentMobile.getElementById("badge-hires").style.display = "none";
                            playerDocument.getElementById("badge-cd").title = "<b>Lossless</b><br>" + window.currentSong.bitDepth + "-bit " + (window.currentSong.sampleRate / 1000) + " kHz";
                            document.getElementById("player").contentWindow.buildTooltips();
                        }
                    }
                }

                if (window.MistNative && localStorage.getItem("desktop-notification") === "true") {
                    window.MistNative.notification(currentSong, await (async function() {
                        let blob = await fetch("/assets/content/" + currentSongID + ".jpg").then(r => r.blob());
                        return await new Promise(resolve => {
                            let reader = new FileReader();
                            reader.onload = () => resolve(reader.result);
                            reader.readAsDataURL(blob);
                        });
                    })());
                }

                if ('mediaSession' in document.getElementById("player").contentWindow.navigator) {
                    document.getElementById("player").contentWindow.navigator.mediaSession.metadata = new MediaMetadata({
                        title: currentSong.title,
                        artist: currentSong.artist,
                        album: currentSong.album,
                        artwork: [
                            {
                                src: location.protocol + "//" + location.host + '/assets/content/' + currentSongID + '.jpg',
                                sizes: playerDocument.getElementById("album-art").naturalWidth + "x" + playerDocument.getElementById("album-art").naturalHeight,
                                type: 'image/jpeg'
                            },
                        ]
                    });
                }

                if (window.MistAndroid) {
                    window.MistAndroid.updateNotificationAlbumArt("https://" + location.hostname + "/albumart.php?i=" + currentSongID);
                }

                await fetch("/api/addHistory.php?i=" + currentSongID);
            }
        }

        async function preloadMore() {
            for (let i = 1; i <= 10; i++) {
                if (playlist[currentPlaylistPosition + i]) {
                    let id = playlist[currentPlaylistPosition + i];
                    let stellaCompatible = false;

                    if (localStorage.getItem("enable-stella") === "true" && localStorage.getItem("data-saving") !== "true") {
                        stellaCompatible = await (await fetch("/api/hasStella.php?id=" + id)).text() === "true";
                    }

                    if (stellaCompatible) {
                        if (!window.preloaded[id]) {
                            window.preloaded[id] = await Stella.build("/assets/content/" + id + ".stella");
                            window.preloadedGains[id] = await normalizeAudio(window.preloaded[id].stems.other.buffer, 0, true);
                            window.preloadedGainsBoosted1[id] = await normalizeAudio(window.preloaded[id].stems.other.buffer, .05, true);
                            window.preloadedGainsBoosted2[id] = await normalizeAudio(window.preloaded[id].stems.other.buffer, .1, true);
                        }
                    } else {
                        if (!window.preloaded[id]) {
                            if (localStorage.getItem("data-saving") === "true") {
                                window.preloaded[id] = await (await fetch("/assets/content/" + id + ".m4a")).arrayBuffer();
                                window.preloadedBlobs[id] = new Blob([window.preloaded[id]], { type: "audio/mp4" });
                            } else {
                                window.preloaded[id] = await (await fetch("/assets/content/" + id + ".flac")).arrayBuffer();
                                window.preloadedBlobs[id] = new Blob([window.preloaded[id]], { type: "audio/flac" });
                            }

                            window.preloadedGains[id] = await normalizeAudio(window.preloaded[id], 0);
                            window.preloadedGainsBoosted1[id] = await normalizeAudio(window.preloaded[id], .05);
                            window.preloadedGainsBoosted2[id] = await normalizeAudio(window.preloaded[id], .1);
                        }
                    }
                }
            }

            cleanupPreload();
        }

        function cleanupPreload() {
            let keys = Object.keys(window.preloaded).slice(-20);

            for (let key of Object.keys(window.preloaded)) {
                if (!keys.includes(key)) {
                    if (window.preloadedURLs[key]) URL.revokeObjectURL(window.preloadedURLs[key]);
                    delete window.preloaded[key];
                }
            }
        }
    </script>

    <script>
        function openModal(title, url, hideTitle) {
            document.getElementById("modal").style.display = "";

            if (hideTitle) {
                document.getElementById("modal").contentWindow.document.getElementById("modal-header").style.display = "none";
            } else {
                document.getElementById("modal").contentWindow.document.getElementById("modal-header").style.display = "";
            }

            document.getElementById("modal").contentWindow.document.getElementById("modal-title").innerText = title;
            document.getElementById("modal").contentWindow.document.getElementById("modal-frame").src = url;
            document.getElementById("modal").contentWindow.document.getElementById("modal-frame").style.height = "calc(100vh - 130px)";
            document.getElementById("modal").contentWindow._modal.show();
        }

        function eolModal() {
            openModal("Mist is going away", "eol.php", true);
        }

        if (!localStorage.getItem("rich-presence")) {
            localStorage.setItem("rich-presence", "true");
        }

        if (localStorage.getItem("rich-presence") === "false") {
            window.discordRichPresenceData = null;
        } else {
            window.discordRichPresenceData = {
                largeImageKey: "logo",
                buttons : [
                    { label: 'View profile', url: 'https://mist.equestria.horse/profile/?/<?= $_PROFILE["id"] ?>' }
                ]
            };
        }
    </script>
</body>
</html>