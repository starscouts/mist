<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/albums";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Albums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform has-navigation">
    <div id="ui-navigation" style="z-index: 999; background-color: rgba(255, 255, 255, .75); position: fixed; top: 0; left: 0; right: 0; height: 32px; backdrop-filter: blur(50px); -webkit-backdrop-filter: blur(50px);">
        <div style="display: grid; grid-template-columns: max-content 1fr max-content; height: 100%;" class="container">
            <div id="ui-back-button" onclick="history.back();" style="display: flex; align-items: center; justify-content: center; text-align: center; opacity: 0; pointer-events: none;">
                <img src="/assets/icons/back.svg" alt="Back" class="icon">
            </div>
            <div style="display: flex; align-items: center; justify-content: center; text-align: center;"><b>Albums</b></div>
            <div>
                <input placeholder="Filter" id="filter" class="form-control" style="width: 256px;height: 32px;border-top: none;" onchange="updateFilter();" onkeyup="updateFilter();">
            </div>
        </div>
    </div>
    <script src="/assets/js/common.js"></script>
    <div class="container">
        <br>
        <div id="album-grid" style="display: grid; grid-template-columns: repeat(5, 1fr);">
            <?php global $albums;

            $albums = array_filter($albums, function ($i) {
                global $library;
                return in_array($i, $library);
            }, ARRAY_FILTER_USE_KEY);

            uasort($albums, function ($a, $b) {
                return strcmp($a["title"], $b["title"]);
            });

            uasort($albums, function ($a, $b) {
                return strcmp($a["artist"], $b["artist"]);
            });

            foreach ($albums as $id => $album): ?>
            <a id="album-<?= $id ?>" data-item-track="<?= $album["title"] ?>" data-item-artist="<?= $album["artist"] ?>" href="listing.php?a=<?= $id ?>" style="padding: 10px; color: inherit; text-decoration: inherit;" class="album">
                <img class="album-list-art" alt="" src="/assets/content/<?= $id ?>.jpg" style="width: 100%; aspect-ratio: 1; border-radius: 5px; margin-bottom: 5px;">
                <div class="album-list-item" style="max-width: calc(80vw / 5); white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["title"] ?></div>
                <div class="album-list-item" style="max-width: calc(80vw / 5); opacity: .5; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["artist"] ?></div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php if (count($albums) === 0): ?>
            <div class="text-muted" style="position: fixed; display: flex; inset: 0; align-items: center; justify-content: center;">
                <div style="text-align: center;">
                    <img class="icon" src="/assets/logo-transparent.svg" style="filter: grayscale(1) invert(1); width: 96px; height: 96px;" alt="">
                    <h4 style="opacity: .75;">Add music to your library</h4>
                    <p style="max-width: 300px; margin-left: auto; margin-right: auto;">Browse millions of songs and collect your favorites here.</p>
                    <div class="btn btn-primary" onclick="window.parent.openUI('home');">Browse Mist</div>
                </div>
            </div>
        <?php endif; ?>
        <div id="search-results" style="display: none; grid-template-columns: repeat(5, 1fr);"></div>
    </div>

    <script>
        let items = Array.from(document.getElementsByClassName("album")).map(i => { return { title: i.getAttribute("data-item-track"), artist: i.getAttribute("data-item-artist"), id: i.id } });

        const fuse = new Fuse(items, {
            keys: [
                {
                    name: 'title',
                    weight: 1
                },
                {
                    name: 'artist',
                    weight: .5
                }
            ]
        });

        function updateFilter() {
            let query = document.getElementById("filter").value.trim();

            if (query !== "") {
                document.getElementById("search-results").style.display = "grid";
                document.getElementById("album-grid").style.display = "none";

                let results = items.filter(i => i.title.toLowerCase().replace(/[^a-z\d ]/mg, " ").replace(/ +/mg, " ").includes(query.toLowerCase().replace(/[^a-z\d ]/mg, " ").replace(/ +/mg, " ")) || i.artist.toLowerCase().replace(/[^a-z\d ]/mg, " ").replace(/ +/mg, " ").includes(query.toLowerCase().replace(/[^a-z\d ]/mg, " ").replace(/ +/mg, " ")));
                document.getElementById("search-results").innerHTML = "";

                for (let result of results) {
                    document.getElementById("search-results").innerHTML += document.getElementById(result.id).outerHTML;
                }
            } else {
                document.getElementById("search-results").style.display = "none";
                document.getElementById("album-grid").style.display = "grid";
            }
        }
    </script>

    <br><br>
</body>
</html>