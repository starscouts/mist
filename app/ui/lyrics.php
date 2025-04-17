<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/lyrics";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>lyrics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
    <style>
        .synced-lyrics-item {
            opacity: .25;
            font-size: 4vw;
            min-font-size: 22px;
            margin-bottom: 10px;
            transition: opacity 200ms;
        }

        .synced-lyrics-item.active {
            opacity: 1;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        #lyrics-synced {
            transition-property: top;
            transition-duration: 500ms;
            transition-timing-function: cubic-bezier(0.54, 0, 0.23, 0.79);
        }
    </style>
</head>
<body style="background-color: transparent;">
    <script src="/assets/js/common.js"></script>
    <div id="lyrics-outer">
        <div id="not-playing" style="display: none; position: fixed; inset: 16px; align-items: center; justify-content: center; opacity: .5; text-align: center;">
            Lyrics and music videos will appear here when you start playing a song. If supported, lyrics will also scroll as the song plays.
        </div>
        <div id="not-playing-2" style="display: none; position: fixed; inset: 16px; align-items: center; justify-content: center; opacity: .5; text-align: center;">
            Lyrics will appear here when you start playing a song. If supported, they will also scroll as the song plays.
        </div>

        <div id="not-available" style="display: none; position: fixed; inset: 16px; align-items: center; justify-content: center; opacity: .5; text-align: center;">
            No lyrics are available for this song. Try again later.
        </div>

        <div id="loading" style="display: none; position: fixed; inset: 16px; align-items: center; justify-content: center; opacity: .5; text-align: center;">
            Loading lyrics...
        </div>

        <video id="video" style="width: 100%; height: 100%; display: none; position: fixed; inset: 0; background-color: black;"></video>

        <div id="lyrics-unsynced" style="display: none; position: fixed; inset: 16px; overflow: auto;"></div>
        <div id="lyrics-synced" style="text-align: center; display: none; position: fixed; left: 16px; right: 16px; top: 0; bottom: 0; z-index: 5;"></div>
        <div id="lyrics-synced-fade" style="display: none; position: fixed; inset: 0; z-index: 10; background-image: linear-gradient(180deg, rgba(255,0,0,0) 25%, rgba(255,255,255,1) 100%);"></div>
    </div>

    <script>

        let lastID = null;
        let lastTimestamp = null;
        window.lyrics = {};

        setInterval(async () => {
            if (!window.delays) window.delays = await (await fetch("/assets/delays.json?_=" + [...crypto.getRandomValues(new Uint8Array(40))].map(m=>('0'+m.toString(16)).slice(-2)).join(''))).json();

            if (window.parent.currentSongID !== lastID) {
                lastID = window.parent.currentSongID;

                if (lastID === null) {
                    document.getElementById("lyrics-synced").style.display = "none";
                    document.getElementById("video").style.display = "none";
                    document.getElementById("lyrics-synced-fade").style.display = "none";
                    document.getElementById("lyrics-unsynced").style.display = "none";
                    document.getElementById("not-available").style.display = "none";
                    document.getElementById("loading").style.display = "none";
                    document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "flex";
                } else {
                    document.getElementById("lyrics-synced").style.display = "none";
                    document.getElementById("video").style.display = "none";
                    document.getElementById("lyrics-synced-fade").style.display = "none";
                    document.getElementById("lyrics-unsynced").style.display = "none";
                    document.getElementById("not-available").style.display = "none";
                    document.getElementById("loading").style.display = "flex";
                    document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "none";

                    window.hasVideo = await (await fetch("/api/hasVideo.php?id=" + lastID)).json();

                    if (window.hasVideo && !window.parent.document.getElementById("lyrics-page").classList.contains("mobile-show") && localStorage.getItem("data-saving") !== "true") {
                        document.getElementById("lyrics-synced").style.display = "none";
                        document.getElementById("lyrics-synced-fade").style.display = "none";
                        document.getElementById("lyrics-unsynced").style.display = "none";
                        document.getElementById("video").style.display = "block";
                        document.getElementById("not-available").style.display = "none";
                        document.getElementById("loading").style.display = "none";
                        document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "none";

                        document.getElementById("video").src = "/assets/content/" + lastID + ".webm";
                    } else {
                        if (!window.lyrics[lastID]) {
                            window.lyricsLoadTimeout = setTimeout(() => {
                                location.reload();
                            }, 10000);

                            try {
                                window.lyrics[lastID] = await (await fetch("/api/lyrics.php?id=" + lastID)).json();
                            } catch (e) {
                                window.lyrics[lastID] = {
                                    synced: false,
                                    payload: null
                                }
                            }

                            clearTimeout(window.lyricsLoadTimeout);

                            if (window.lyrics[lastID] && window.lyrics[lastID].payload) {
                                if (window.lyrics[lastID].synced) {
                                    document.getElementById("video").style.display = "none";
                                    document.getElementById("lyrics-synced").style.display = "";
                                    document.getElementById("lyrics-synced-fade").style.display = "";
                                    document.getElementById("lyrics-unsynced").style.display = "none";
                                    document.getElementById("not-available").style.display = "none";
                                    document.getElementById("loading").style.display = "none";
                                    document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "none";

                                    document.getElementById("lyrics-synced").style.top = "0px";
                                    document.getElementById("lyrics-synced").innerHTML = "<div style='height: 16px;'></div>" + window.lyrics[lastID].payload.map(i => `
                                <div class="synced-lyrics-item" id="synced-lyrics-${i.startTimeMs}">${i.words}</div>
                                `).join("") + "<div style='height: 16px;'></div>";
                                } else {
                                    document.getElementById("video").style.display = "none";
                                    document.getElementById("lyrics-synced").style.display = "none";
                                    document.getElementById("lyrics-synced-fade").style.display = "none";
                                    document.getElementById("lyrics-unsynced").style.display = "";
                                    document.getElementById("not-available").style.display = "none";
                                    document.getElementById("loading").style.display = "none";
                                    document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "none";

                                    document.getElementById("lyrics-unsynced").scrollTop = false;
                                    document.getElementById("lyrics-unsynced").innerText = window.lyrics[lastID].payload.replaceAll("\n\n\n", "\n");
                                }
                            } else {
                                document.getElementById("video").style.display = "none";
                                document.getElementById("lyrics-synced").style.display = "none";
                                document.getElementById("lyrics-synced-fade").style.display = "none";
                                document.getElementById("lyrics-unsynced").style.display = "none";
                                document.getElementById("not-available").style.display = "flex";
                                document.getElementById("loading").style.display = "none";
                                document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "none";
                            }
                        }
                    }
                }
            }

            if (window.lyrics[lastID] && window.lyrics[lastID].synced) {
                document.getElementById("video").style.display = "none";
                document.getElementById("lyrics-synced").style.display = "";
                document.getElementById("lyrics-synced-fade").style.display = "";
                document.getElementById("lyrics-unsynced").style.display = "none";
                document.getElementById("not-available").style.display = "none";
                document.getElementById("loading").style.display = "none";
                document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "none";

                for (let item of [...window.lyrics[lastID].payload].reverse()) {
                    if (parseInt(item.startTimeMs) / 1000 <= window.parent.document.getElementById("player").contentDocument.getElementById("player-audio").currentTime) {
                        if (item.startTimeMs !== lastTimestamp) {
                            Array.from(document.getElementsByClassName("synced-lyrics-item")).map(i => i.classList.remove("active"));
                            document.getElementById("lyrics-synced").style.top = "-" + (document.getElementById("synced-lyrics-" + item.startTimeMs).offsetTop - 33) + "px";
                            lastTimestamp = item.startTimeMs;
                            document.getElementById("synced-lyrics-" + item.startTimeMs).classList.add("active");
                        }

                        break;
                    }
                }
            }
        }, 100);

        window.updateVideo = () => {
            if (window.hasVideo && !window.parent.document.getElementById("lyrics-page").classList.contains("mobile-show") && localStorage.getItem("data-saving") !== "true") {
                document.getElementById("video").currentTime = window.parent.playerDocument.getElementById("player-audio").currentTime + (window.delays[window.parent.currentSongID] ?? 0);

                if (window.parent.playerDocument.getElementById("player-audio").paused) {
                    document.getElementById("video").pause();
                } else {
                    document.getElementById("video").play();
                }
            }
        }

        document.onvisibilitychange = () => {
            window.updateVideo();
        }

        setInterval(() => {
            if (Math.abs(window.parent.playerDocument.getElementById("player-audio").currentTime - document.getElementById("video").currentTime) > 0.5 + Math.abs((window.delays[window.parent.currentSongID] ?? 0))) {
                window.updateVideo();
            }
        }, 1000);

        document.getElementById(localStorage.getItem("data-saving") === "true" ? "not-playing-2" : "not-playing").style.display = "flex";
    </script>
</body>
</html>