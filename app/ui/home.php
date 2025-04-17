<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $history; global $profile; global $_PROFILE; global $songs; global $albums; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/home";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
    <style>
        #play-item:hover {
            background-color: rgba(0, 0, 0, .05);
        }

        #play-item:active {
            background-color: rgba(0, 0, 0, .1);
        }
    </style>
</head>
<body class="crossplatform">
    <script>
        window.parent.location.hash = "#/home";
    </script>
    <script src="/assets/js/common.js"></script>
    <div class="container">
        <div style="margin-left: 10px;">
            <?php if (trim($profile["banner"]) !== ""): ?>
            <div id="banner" style="background-size: cover; background-position: center; background-image: url(&quot;<?= str_replace('"', '', $profile["banner"]) ?>&quot;); background-color: #eee; height: 256px; margin-top: 20px; border-radius: 20px;">
                <div style="background-color: rgba(255, 255, 255, .25); height: 100%; border-radius: 20px;"></div>
            <?php else: ?>
            <div id="banner" style="background-size: cover; background-position: center; background-image: url('https://account.equestria.dev/hub/api/rest/avatar/<?= $_PROFILE["id"] ?>?dpr=2&size=32'); background-color: #eee; height: 256px; margin-top: 20px; border-radius: 20px;">
                <div style="background-color: rgba(255, 255, 255, .25); height: 100%; backdrop-filter: blur(100px); -webkit-backdrop-filter: blur(50px); border-radius: 20px;"></div>
            <?php endif; ?>
            </div>
            <br>

            <div style="display: grid; grid-template-columns: 64px 1fr max-content; grid-gap: 15px;">
                <img alt="" src="https://account.equestria.dev/hub/api/rest/avatar/<?= $_PROFILE["id"] ?>?dpr=2&size=128" style="filter: none !important; border-radius: 999px; vertical-align: middle; width: 64px;">
                <div style="display: flex; align-items: center;">
                    <div id="name">
                        <h4>Welcome <?= $_PROFILE["name"] ?></h4>
                    </div>

                    <div style="width: 100%; display: none;" id="search">
                        <div style="width: 100%;">
                            <input name="q" type="text" id="query" class="form-control" placeholder="Search on Mist">
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center;">
                    <span onclick="toggleSearch();" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-search">
                        <img class="icon" alt="" src="/assets/icons/search.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-search-icon">
                    </span>
                </div>
            </div>
        </div>

        <div class="list-group" style="margin-top: 20px; display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px;">
            <?php usort($history, function ($a, $b) {
                return strtotime($b["date"]) - strtotime($a["date"]);
            }); foreach (array_slice(array_values(array_unique(array_map(function ($i) { return $i["item"]; }, array_values($history)))), 0, 12) as $item): $song = $songs[$item]; ?>
                <div onclick="window.parent.playSong('<?= $item ?>')" id="play-item" class="list-group-item" style="border-radius: 5px !important; border: none !important; display: grid; grid-template-columns: 64px 1fr; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">
                    <div style="display: flex; align-items: center;">
                        <img alt="" src="/assets/content/<?= $item ?>.jpg" style="width: 48px; height: 48px; background-color: #eee; border-radius: 3px;">
                    </div>
                    <div style="display: flex; align-items: center;">
                        <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">
                            <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $song["title"] ?></div>
                            <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;" class="text-muted"><?= $song["artist"] ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="album-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); margin-top: 20px;">
            <?php global $albums;

            $albums = array_filter($albums, function ($i) {
                global $library;
                return in_array($i, $library);
            }, ARRAY_FILTER_USE_KEY);

            $albums = array_reverse($albums);

            foreach ($albums as $id => $album): ?>
                <a id="album-<?= $id ?>" data-item-track="<?= $album["title"] ?>" data-item-artist="<?= $album["artist"] ?>" href="listing.php?a=<?= $id ?>" style="padding: 10px; color: inherit; text-decoration: inherit;" class="album">
                    <img class="album-list-art" alt="" src="/assets/content/<?= $id ?>.jpg" style="width: 100%; aspect-ratio: 1; border-radius: 5px; margin-bottom: 5px;">
                    <div class="album-list-item" style="max-width: calc(80vw / 5); white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["title"] ?></div>
                    <div class="album-list-item" style="max-width: calc(80vw / 5); opacity: .5; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["artist"] ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.getElementById("query").onkeydown = (e) => {
            if (e.code === "Enter") {
                toggleSearch();
            }
        }

        function toggleSearch() {
            if (document.getElementById("search").style.display === "none") {
                document.getElementById("search").style.display = "";
                document.getElementById("name").style.display = "none";
                document.getElementById("query").focus();
            } else {
                document.getElementById("query").blur();

                if (document.getElementById("query").value.trim() === "") {
                    document.getElementById("search").style.display = "none";
                    document.getElementById("name").style.display = "";
                } else {
                    window.parent.location.hash = '#/search/' + encodeURIComponent(document.getElementById('query').value);
                    location.href = "ui/search.php?q=" + encodeURIComponent(document.getElementById("query").value);
                }
            }
        }
    </script>

    <br><br>
</body>
</html>