<?php

global $_PROFILE;
$_PROFILE = null;

if (isset($_COOKIE["WAVY_SESSION_TOKEN"])) {
    if (!str_contains($_COOKIE["WAVY_SESSION_TOKEN"], ".") && !str_contains($_COOKIE["WAVY_SESSION_TOKEN"], "/")) {
        if (str_starts_with($_COOKIE["WAVY_SESSION_TOKEN"], "wv_")) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . $_COOKIE["WAVY_SESSION_TOKEN"])) {
                $_PROFILE = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . $_COOKIE["WAVY_SESSION_TOKEN"]), true);
            }
        }
    }
}

if (!isset($_PROFILE)) {
    if (str_contains($_SERVER['HTTP_USER_AGENT'], "MistNative/")) {
        header("Location: /oauth/needs-native/");
    } else {
        header("Location: /oauth/init/");
    }

    die();
}

global $albums; global $songs;

if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users")) mkdir($_SERVER['DOCUMENT_ROOT'] . "/includes/users");
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-favorites.json")) file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-favorites.json", "[]");
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-library.json")) file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-library.json", "[]");
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-history.json")) file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-history.json", "[]");
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-privacy.json")) file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-privacy.json", json_encode([
    "profile" => 2,
    "library" => 0,
    "history" => 0,
    "favorites" => 0,
    "custom" => 1,
    "listen" => 0
]));
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-profileSettings.json")) file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-profileSettings.json", json_encode([
    "nsfw" => false,
    "description" => "",
    "banner" => ""
]));
file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-profile.json", json_encode($_PROFILE));

$albums = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/content/albums.json"), true);
$songs = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/content/songs.json"), true);
$favorites = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-favorites.json"), true);
$library = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-library.json"), true);
$history = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-history.json"), true);
$privacy = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-privacy.json"), true);
$profile = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-profileSettings.json"), true);

$albums = array_map(function ($i) {
    $i["artist"] = str_replace(";", ", ", $i["artist"]);
    return $i;
}, $albums);

function displayList($list, $hasAlbum = false) { global $albums; global $favorites; ?>
    <div class="list-group" style="margin-left: 10px; margin-top: 20px;" id="main-list">
        <?php foreach ($list as $id => $song): ?>
            <div data-item-track="<?= $song["title"] ?>" data-item-artist="<?= $song["artist"] ?>" id="item-<?= $id ?>" class="list-group-item track" style="display: grid; grid-template-columns: 32px 1fr max-content;">
                <div style="opacity: .5; display: flex; align-items: center; justify-content: left;"><?= $hasAlbum && $song["track"] > 0 ? $song["track"] : "" ?></div>
                <?php if (!$hasAlbum): ?>
                <div style="display: grid; grid-template-columns: 48px 1fr; grid-gap: 10px;">
                    <img src="/assets/content/<?= $id ?>.jpg" style="width: 48px; height: 48px;">
                <?php endif; ?>
                    <div style="width: <?php if (!$hasAlbum): ?>50<?php else: ?>55<?php endif; ?>vw; height: 3em; display: flex; align-items: center; justify-content: left;">
                        <?php if ($hasAlbum && $song["artist"] === $albums[$_GET["a"]]["artist"]): ?>
                            <div style="max-width: 100%;"><div style="max-width: 100%; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $song["title"] ?></div></div>
                        <?php else: ?>
                            <div style="max-width: 100%;"><div style="max-width: 100%; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $song["title"] ?></div><div style="max-width: 100%; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis; opacity: .5;"><?= $song["artist"] ?></div></div>
                        <?php endif; ?>
                    </div>
            <?php if (!$hasAlbum): ?>
                </div>
            <?php endif; ?>
                <div class="list-actions">
                    <span class="list-icons-desktop" style="margin-right: 10px;">
                        <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $id . ".stella")): ?>
                            <img data-bs-html="true" title="<img src='/assets/icons/stella-full.svg' class='stella-logo-full'>" data-bs-toggle="tooltip" alt="" src="/assets/icons/stella.svg" style="vertical-align: middle; width: 16px; height: 16px; position: relative; top: -11px;">
                            <style>
                                .stella-logo-full {
                                    height: 12px;
                                    filter: invert(1);
                                }
                            </style>
                        <?php endif; ?><?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $id . ".webm")): ?>
                            <img title="Music video available" data-bs-toggle="tooltip" alt="" src="/assets/icons/video.svg" style="vertical-align: middle; width: 24px; height: 24px; position: relative; top: -12px;">
                        <?php endif; ?>
                        <script>
                            aTooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                            [...aTooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
                        </script>
                    </span>
                    <!--<span onclick="<?= in_array($id, $favorites) ? "un" : "" ?>favoriteSong('<?= $id ?>');" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-favorite-<?= $id ?>">
                        <img class="icon" alt="" src="/assets/icons/favorite-<?= in_array($id, $favorites) ? "on" : "off" ?>.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-favorite-<?= $id ?>-icon">
                    </span>-->
                    <span onclick="window.parent.playSong('<?= $id ?>'<?php if ($hasAlbum): ?>, 'album:<?= $_GET["a"] ?>'<?php endif; ?>);" class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-play-<?= $id ?>">
                        <img class="icon" alt="" src="/assets/icons/play.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-play-<?= $id ?>-icon">
                    </span>
                    <span class="dropdown">
                        <span class="player-btn" style="border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; height: 48px; width: 48px;" id="btn-menu-<?= $id ?>" data-bs-toggle="dropdown">
                            <img class="icon" alt="" src="/assets/icons/menu.svg" style="pointer-events: none; width: 32px; height: 32px;" id="btn-menu-<?= $id ?>-icon">
                        </span>
                        <ul class="dropdown-menu">
                            <li><a onclick="playNext('<?= $id ?>');" class="dropdown-item" style="cursor: pointer;"><img alt="" src="/assets/icons/playnext.svg" style="pointer-events: none; width: 24px; height: 24px; margin-right: 5px;">Play next</a></li>
                            <li><a onclick="enqueue('<?= $id ?>');" class="dropdown-item" style="cursor: pointer;"><img alt="" src="/assets/icons/add.svg" style="pointer-events: none; width: 24px; height: 24px; margin-right: 5px;">Add to queue</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a id="btn-favorite-<?= $id ?>" onclick="<?= in_array($id, $favorites) ? "un" : "" ?>favoriteSong('<?= $id ?>');" class="dropdown-item" style="cursor: pointer;"><img id="btn-favorite-<?= $id ?>-icon" alt="" src="/assets/icons/favorite-<?= in_array($id, $favorites) ? "on" : "off" ?>.svg" style="pointer-events: none; width: 24px; height: 24px; margin-right: 5px;"><span id="btn-favorite-<?= $id ?>-text"><?= in_array($id, $favorites) ? "Remove from favorites" : "Add to favorites" ?></span></a></li>
                            <li><a onclick="downloadSong('<?= $id ?>');" class="dropdown-item" style="cursor: pointer;"><img alt="" src="/assets/icons/download.svg" style="pointer-events: none; width: 24px; height: 24px; margin-right: 5px;">Download</a></li>
                            <li><a onclick="getInfo('<?= $id ?>');" class="dropdown-item" style="cursor: pointer;"><img alt="" src="/assets/icons/info.svg" style="pointer-events: none; width: 24px; height: 24px; margin-right: 5px;">Get info</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a onclick="navigator.clipboard.writeText('<?= $id ?>');" class="dropdown-item" style="cursor: pointer;"><img alt="" src="/assets/icons/copy.svg" style="pointer-events: none; width: 24px; height: 24px; margin-right: 5px;">Copy ID</a></li>
                        </ul>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        async function favoriteSong(id) {
            document.getElementById("btn-favorite-" + id + "-icon").src = "/assets/icons/favorite-on.svg";
            document.getElementById("btn-favorite-" + id + "-text").innerText = "Remove from favorites";
            document.getElementById("btn-favorite-" + id).onclick = () => {
                unfavoriteSong(id);
            }
            await fetch("/api/addFavorite.php?i=" + id);

            window.parent.redownloadFavorites();
        }

        function playNext(id) {
            if (window.parent.playlist.length === 0) {
                window.parent.playSong(id);
            } else {
                window.parent.playlist.splice(window.parent.currentPlaylistPosition + 1, 0, id);
                window.parent.updateDisplay(true);
            }
        }

        function downloadSong(id) {
            window.parent.openModal("Download song", "download.php?i=" + id);
        }

        function getInfo(id) {
            window.parent.openModal((window.parent.songs[id]?.artist ?? "Unknown artist") + " - " + (window.parent.songs[id]?.title ?? "Unknown song"), "info.php?i=" + id, true);
        }

        function enqueue(id) {
            if (window.parent.playlist.length === 0) {
                window.parent.playSong(id);
            } else {
                window.parent.playlist.push(id);
                window.parent.updateDisplay(true);
            }
        }

        async function unfavoriteSong(id) {
            document.getElementById("btn-favorite-" + id + "-icon").src = "/assets/icons/favorite-off.svg";
            document.getElementById("btn-favorite-" + id + "-text").innerText = "Add to favorites";
            document.getElementById("btn-favorite-" + id).onclick = () => {
                favoriteSong(id);
            }
            await fetch("/api/removeFavorite.php?i=" + id);

            <?php if (isset($favoritesList)): ?>
            location.reload();
            <?php endif; ?>

            window.parent.redownloadFavorites();
        }
    </script>
<?php }