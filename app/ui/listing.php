<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $_PROFILE; global $library;

if (isset($list)) {
    $presetList = true;
} else {
    $presetList = false;
}

if (!$presetList) {
    global $albums; global $songs; global $favorites;

    if (isset($_GET["a"]) && isset($albums[$_GET["a"]])) {
        $hasAlbum = true;
        $list = [];

        foreach ($albums[$_GET["a"]]["tracks"] as $id) {
            $list[$id] = $songs[$id];
        }
    } else {
        $hasAlbum = false;
        $list = $songs;

        foreach ($albums as $id => $album) {
            foreach ($album["tracks"] as $track) {
                $list[$track]["_albumID"] = $id;
            }
        }

        uasort($list, function ($a, $b) {
            return strcmp($a["title"], $b["title"]);
        });

        $list = array_filter($list, function ($i) {
            global $library;
            return in_array($i["_albumID"], $library);
        });
    }
}

if (!isset($onlyStella)) $onlyStella = 0;

if ($onlyStella === 1) {
    $hasAlbum = false;
    $list = $songs;

    foreach ($albums as $id => $album) {
        foreach ($album["tracks"] as $track) {
            $list[$track]["_albumID"] = $id;
        }
    }

    uasort($list, function ($a, $b) {
        return strcmp($a["title"], $b["title"]);
    });

    $list = array_filter($list, function ($i) {
        return file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $i . ".stella");
    }, ARRAY_FILTER_USE_KEY);
} elseif ($onlyStella === 2) {
    $hasAlbum = false;
    $list = $songs;

    foreach ($albums as $id => $album) {
        foreach ($album["tracks"] as $track) {
            $list[$track]["_albumID"] = $id;
        }
    }

    uasort($list, function ($a, $b) {
        return strcmp($a["title"], $b["title"]);
    });

    $list = array_filter($list, function ($i) {
        return file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $i . ".webm");
    }, ARRAY_FILTER_USE_KEY);
}

?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/songs";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php if (isset($favoritesList) && !$hasAlbum): ?>
            Favorites
        <?php elseif ($hasAlbum): ?>
            <?= $albums[$_GET["a"]]["title"] ?>
        <?php elseif ($onlyStella === 1): ?>
            Mist Stella
        <?php elseif ($onlyStella === 2): ?>
            Music videos
        <?php else: ?>
            Songs
        <?php endif; ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform has-navigation">
    <div id="ui-navigation" style="z-index: 999; background-color: rgba(255, 255, 255, .75); position: fixed; top: 0; left: 0; right: 0; height: 32px; backdrop-filter: blur(50px); -webkit-backdrop-filter: blur(50px);">
        <div style="display: grid; grid-template-columns: max-content 1fr max-content; height: 100%;" class="container">
            <div id="ui-back-button" onclick="history.back();" style="display: flex; align-items: center; justify-content: center; text-align: center;<?php if (!$hasAlbum): ?>pointer-events: none; opacity: 0;<?php endif; ?>">
                <img src="/assets/icons/back.svg" alt="Back" class="icon">
            </div>
            <div style="display: flex; align-items: center; justify-content: center; text-align: center;"><b><?php if (!$hasAlbum && !isset($favoritesList) && $onlyStella === 0): ?>Songs<?php elseif ($onlyStella === 1): ?>Mist Stella<?php elseif ($onlyStella === 2): ?>Music videos<?php endif; ?></b></div>
            <?php if (!$hasAlbum): ?>
            <div>
                <input placeholder="Filter" id="filter" class="form-control" style="width: 256px;height: 32px;border-top: none;" onchange="updateFilter();" onkeyup="updateFilter();">
            </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="/assets/js/common.js"></script>
    <?php if ($hasAlbum): ?>
    <script>
        window.parent.location.hash = "#/albums/<?= $_GET["a"] ?>";
    </script>
    <?php endif; if (isset($favoritesList)): global $userId; ?>
    <script>
        window.parent.location.hash = "#/favorites/<?= $userId ?>";
    </script>
    <?php endif; ?>
    <div class="container">
        <br>
        <?php if (isset($favoritesList) && !$hasAlbum): global $userId; ?>
            <div id="album-info" style="display: grid; grid-template-columns: 20vw 1fr; margin-top: 10px; margin-left: 10px; grid-gap: 30px;">
                <img id="album-info-art" alt="" src="/assets/favorites.svg" style="height: 20vw; width: 20vw; border-radius: .75vw;">
                <div id="album-info-text" style="padding: 30px 0; display: grid; grid-template-rows: 1fr max-content;">
                    <div><h2>Favorites</h2>
                        <h2 style="opacity: .5;">
                            <select onchange="changeView();" id="favorites-user-select" class="form-select" style="width: max-content;font-size: inherit;margin: -0.375rem 0 -0.375rem -0.75rem;">
                                <option <?= $userId === $_PROFILE["id"] ? "selected" : "" ?> value="<?= $_PROFILE["id"] ?>"><?= $_PROFILE["name"] ?></option>
                                <?php foreach (scandir($_SERVER['DOCUMENT_ROOT'] . "/includes/users") as $user):
                                    if (str_ends_with($user, "-privacy.json")):
                                        $userPrivacy = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $user), true);
                                        $userProfile = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . substr($user, 0, -13) . "-profile.json"), true);
                                        if ($userPrivacy["listen"] >= 1 && $userProfile["id"] !== $_PROFILE["id"]): ?>
                                <option <?= $userId === $userProfile["id"] ? "selected" : "" ?> value="<?= $userProfile["id"] ?>"><?= $userProfile["name"] ?></option>
                                <?php endif; endif; endforeach; ?>
                            </select>
                        </h2>
                        <div style="opacity: .5;">
                            Click on the heart icon near a song to add it to this list.
                        </div>
                    </div>
                    <div id="album-info-buttons">
                        <a class="btn btn-primary <?= count(array_keys($list)) <= 0 || $userId !== $_PROFILE["id"] ? "disabled" : "" ?>" onclick="window.parent.playSong('<?= array_keys($list)[0] ?? '' ?>', 'favorites');" style="width: 100px;">Play</a>
                        <a class="btn btn-outline-primary <?= count(array_keys($list)) <= 0 || $userId !== $_PROFILE["id"] ? "disabled" : "" ?>" style="width: 100px;" onclick="window.parent.shuffleList('favorites');">Shuffle</a>
                    </div>
                </div>
            </div>
        <?php elseif ($hasAlbum):

        $albums[$_GET["a"]]["stella"] = false;

        foreach ($albums[$_GET["a"]]["tracks"] as $track) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $track . ".stella")) {
                $albums[$_GET["a"]]["stella"] = true;
            }
        }

        ?>
            <div id="album-info" style="display: grid; grid-template-columns: 20vw 1fr; margin-top: 10px; margin-left: 10px; grid-gap: 30px;">
                <img id="album-info-art" alt="" src="/assets/content/<?= $_GET["a"] ?>.jpg" style="height: 20vw; width: 20vw; border-radius: .75vw;">
                <div id="album-info-text" style="padding: 30px 0; display: grid; grid-template-rows: 1fr max-content;">
                    <div><h2><?= $albums[$_GET["a"]]["title"] ?></h2>
                        <h2 style="opacity: .5;"><?= $albums[$_GET["a"]]["artist"] ?></h2>
                        <div style="opacity: .5;">
                            <?php if (isset($albums[$_GET["a"]]["date"]) && $albums[$_GET["a"]]["date"] > 0): ?>
                                <?= $albums[$_GET["a"]]["date"] ?>
                                <?php if ($albums[$_GET["a"]]["hiRes"] || $albums[$_GET["a"]]["stella"]): ?> · <?php endif; ?>
                            <?php endif; if ($albums[$_GET["a"]]["hiRes"]): ?>
                                <img src='/assets/icons/lossless.svg' alt='' class='icon player-badge-icon'>Hi-Res Lossless
                                <?php if ($albums[$_GET["a"]]["stella"]): ?><span class="mist-stella"> · </span><?php endif; ?>
                            <?php endif; if ($albums[$_GET["a"]]["stella"]): ?>
                                <span class="mist-stella"><img src='/assets/icons/stella.svg' alt='' style="height: 14.4px !important; width: 14.4px !important;" class='icon player-badge-icon'><img src='/assets/icons/stella-full.svg' alt='' class='mist-stella-text icon player-badge-icon'></span>
                            <?php endif; ?>
                        </div>
                        <style>
                            .mist-stella-text {
                                width: 68px !important;
                                position: relative !important;
                                top: -1px !important;
                            }
                        </style>
                    </div>
                    <div id="album-info-buttons" <?php if (!in_array($_GET["a"], $library)): ?>class="nolibrary"<?php endif; ?>>
                        <?php if (in_array($_GET["a"], $library)): ?>
                        <a class="btn btn-primary" onclick="window.parent.playSong('<?= array_keys($list)[0] ?>', 'album:<?= $_GET["a"] ?>');" style="width: 100px;">Play</a>
                        <a class="btn btn-outline-primary" style="width: 100px;" onclick="window.parent.shuffleList('album:<?= $_GET["a"] ?>');">Shuffle</a>
                        <?php else: ?>
                        <a class="btn btn-primary" onclick="window.addToLibrary();" id="library-button" style="width: 200px;">Add to library</a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php displayList($list, $hasAlbum); ?>
        <div class="list-group" style="margin-left: 10px; margin-top: 20px; display: none;" id="search-results"></div>
        <?php if (count($list) === 0 && !isset($favoritesList)): ?>
            <div class="text-muted" style="position: fixed; display: flex; inset: 0; align-items: center; justify-content: center;">
                <div style="text-align: center;">
                    <img class="icon" src="/assets/logo-transparent.svg" style="filter: grayscale(1) invert(1); width: 96px; height: 96px;" alt="">
                    <h4 style="opacity: .75;">Add music to your library</h4>
                    <p style="max-width: 300px; margin-left: auto; margin-right: auto;">Browse millions of songs and collect your favorites here.</p>
                    <div class="btn btn-primary" onclick="window.parent.openUI('home');">Browse Mist</div>
                </div>
            </div>
        <?php endif; ?>
        <br>
        <div style="margin-left: 10px;">
        <?php if ($hasAlbum && trim($albums[$_GET["a"]]["copyright"]) !== ""): ?>
        <div class="text-muted"><?= $albums[$_GET["a"]]["copyright"] ?></div>
        <?php endif; if ($hasAlbum && in_array($_GET["a"], $library)): ?><a class="link" id="library-button" href="#" onclick="removeFromLibrary();">Remove from library</a>
        <?php endif; ?>
        </div>
    </div>

    <script>
        <?php if ($hasAlbum): ?>
        async function addToLibrary() {
            document.getElementById("library-button").classList.add("disabled");
            await fetch("/api/addLibrary.php?i=<?= $_GET["a"] ?>");
            window.parent.redownloadLibrary();
            location.reload();
        }

        async function removeFromLibrary() {
            document.getElementById("library-button").classList.add("disabled");
            await fetch("/api/removeLibrary.php?i=<?= $_GET["a"] ?>");
            window.parent.redownloadLibrary();
            location.reload();
        }
        <?php endif; ?>

        let items = Array.from(document.getElementsByClassName("track")).map(i => { return { title: i.getAttribute("data-item-track").toLowerCase().replace(/[^a-z\d ]/mg, " ").replace(/ +/mg, " "), artist: i.getAttribute("data-item-artist").toLowerCase().replace(/[^a-z\d ]/mg, " ").replace(/ +/mg, " "), id: i.id } });

        function updateFilter() {
            let query = document.getElementById("filter").value.trim().toLowerCase().replace(/[^a-z\d ]/mg, " ").replace(/ +/mg, " ");

            if (query !== "") {
                document.getElementById("search-results").style.display = "flex";
                document.getElementById("main-list").style.display = "none";

                let results = items.filter(i => i.title.includes(query) || i.artist.includes(query)).slice(0, 50);
                document.getElementById("search-results").innerHTML = "";

                for (let result of results) {
                    document.getElementById("search-results").innerHTML += document.getElementById(result.id).outerHTML;
                }
            } else {
                document.getElementById("search-results").style.display = "none";
                document.getElementById("main-list").style.display = "flex";
            }
        }

        function changeView() {
            location.href = "favorites.php?u=" + document.getElementById("favorites-user-select").value;
        }
    </script>

    <style>
        #favorites-user-select {
            background-color: transparent;
            border-color: transparent;
        }

        #favorites-user-select:hover, #favorites-user-select:active, #favorites-user-select:focus {
            background-color: var(--bs-body-bg);
            border-color: var(--bs-border-color);
        }
    </style>

    <br><br>
</body>
</html>