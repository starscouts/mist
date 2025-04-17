<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/queue";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Queue</title>
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
        <h2 class="desktop-title" style="margin-top: 10px; margin-bottom: 20px; margin-left: 10px;">
            Queue
            <span class="btn btn-primary" style="float: right;" onclick="clearQueue();">Clear queue</span>
        </h2>
        <div class="list-group" style="margin-left: 10px; margin-top: 20px;" id="main-list"></div>
        <div class="text-muted" style="margin-left: 10px; margin-top: 20px; display: none;" id="empty">
            There are no songs playing next. To add songs to the queue, browse your library and select Add to queue.
        </div>
    </div>

    <script>
        function clearQueue() {
            window.parent.playlist = window.parent.playlist.slice(0, window.parent.currentPlaylistPosition + 1);
            refreshQueue();
        }

        function refreshQueue() {
            let list = window.parent.playlist.slice(window.parent.currentPlaylistPosition + 1);

            if (list.length === 0) {
                document.getElementById('main-list').style.display = "none";
                document.getElementById('empty').style.display = "";
            } else {
                document.getElementById('main-list').style.display = "";
                document.getElementById('empty').style.display = "none";

                document.getElementById('main-list').innerHTML = list.map(i => [window.parent.songs[i], i]).map((i, j) => `
                <div data-item-track="${i[0].title}" data-item-artist="${i[0].artist}" id="item-${i[1]}" class="list-group-item track" style="display: grid; grid-template-columns: 32px 1fr max-content;">
                    <div style="opacity: .5; display: flex; align-items: center; justify-content: left;"></div>
                    <div style="display: grid; grid-template-columns: 48px 1fr; grid-gap: 10px;">
                        <img src="/assets/content/${i[1]}.jpg" style="width: 48px; height: 48px;">
                        <div style="width: 50vw; height: 3em; display: flex; align-items: center; justify-content: left;">
                           <div style="max-width: 100%;"><div style="max-width: 100%; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">${i[0].title}</div><div style="max-width: 100%; opacity: .5; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">${i[0].artist}</div></div>
                        </div>
                    </div>
                    <div class="list-actions">
                        <span onclick="removeSong(${j + window.parent.currentPlaylistPosition + 1});" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-remove-${i[1]}">
                            <img class="icon" alt="" src="/assets/icons/remove.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-remove-${i[1]}-icon">
                        </span>
                        <span onclick="window.parent.playSong('${i[1]}', 'keep');" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-play-${i[1]}">
                            <img class="icon" alt="" src="/assets/icons/play.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-play-${i[1]}-icon">
                        </span>
                    </div>
                </div>
                `).join("");
            }
        }

        function removeSong(index) {
            window.parent.playlist.splice(index, 1);
            refreshQueue();
            window.parent.updateDisplay(true);
        }

        refreshQueue();
    </script>

    <br><br>
</body>
</html>