<?php header("X-Frame-Options: SAMEORIGIN");

require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/Parsedown.php"; $Parsedown = new Parsedown();
$albums = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/content/albums.json"), true);
$songs = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/content/songs.json"), true);

function timeAgo($time, $french = false, $isDifference = false, $long = true, $showTense = true): string {
    $lengths = array("60", "60", "24", "7", "4.35", "12", "100");

    if ($long) {
        $periods = ["second", "minute", "hour", "day", "week", "month", "year", "age"];
    } else {
        $periods = ["sec", "min", "hr", "d", "wk", "mo", "y", "ages"];
    }

    if ($isDifference) {
        $difference = $time;
    } else {
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }

        $now = time();
        $difference = $now - $time;
    }

    if ($difference <= 10 && $difference >= 0) {
        return $tense = "now";
    } elseif ($difference > 0) {
        $tense = "ago";
    } else {
        $tense = "later";
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    $period = $periods[$j];

    if ($showTense) {
        if ($long) {
            return "{$difference} {$period}" . ($difference > 1 ? "s" : "") . " {$tense}";
        } else {
            return "{$difference} {$period} {$tense}";
        }
    } else {
        if ($long) {
            return "{$difference} {$period}" . ($difference > 1 ? "s" : "");
        } else {
            return "{$difference} {$period}";
        }
    }
}

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

$available = false;
global $userPrivacy;

$userProfile = [];
$userFavorites = [];
$userHistory = [];
$userLibrary = [];
$userSettings = [];

if (count($_GET) > 0 && str_starts_with(array_keys($_GET)[0], "/")) {
    $hasID = true;
    $selectedId = substr(array_keys($_GET)[0], 1);

    if (preg_match("/[^a-f0-9-]/m", $selectedId) == 0) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $selectedId . "-privacy.json")) {
            $userPrivacy = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $selectedId . "-privacy.json"), true);
            $userProfile = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $selectedId . "-profile.json"), true);

            if ($userPrivacy["profile"] >= 1 && isset($_PROFILE) || $userPrivacy["profile"] === 2 || $_PROFILE["id"] === $userProfile["id"]) {
                $available = true;

                $userFavorites = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $selectedId . "-favorites.json"), true);
                $userHistory = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $selectedId . "-history.json"), true);
                $userLibrary = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $selectedId . "-library.json"), true);
                $userSettings = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $selectedId . "-profileSettings.json"), true);

                $userLibrary = array_values(array_filter($userLibrary, function ($i) {
                    global $albums;
                    return isset($albums[$i]);
                }));
                $userHistory = array_values(array_filter($userHistory, function ($i) {
                    global $songs;
                    return isset($songs[$i["item"]]);
                }));
                $userFavorites = array_values(array_filter($userFavorites, function ($i) {
                    global $songs;
                    return isset($songs[$i]);
                }));
            }
        }
    }
} else {
    $hasID = false;
}

if (!$available && $hasID) {
    header("HTTP/1.1 404 Not Found");
}

function allowed(string $item): bool {
    global $userPrivacy; global $userProfile; global $_PROFILE;
    return $userPrivacy[$item] >= 1 && isset($_PROFILE) || $userPrivacy[$item] === 2 || $_PROFILE["id"] === $userProfile["id"];
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $available ? $userProfile['name'] . " — Mist" : "Mist" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="/assets/logo-display.svg" type="image/svg+xml">
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-status-bar" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="apple-mobile-web-app-status-bar" content="#000000" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" media="(prefers-color-scheme: dark)">
    <meta name="apple-mobile-web-app-status-bar-style" content="white-translucent" media="(prefers-color-scheme: light)">
    <style>
        @media (min-width: 768px) {
            #copyright-separator-mobile {
                display: none;
            }
        }

        @media (max-width: 767px) {
            #copyright-separator-desktop {
                display: none;
            }
        }
    </style>
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@equestriadev" />
    <meta name="twitter:title" content="<?= $available ? $userProfile['name'] . " (@" . $userProfile["login"] . ") on Mist" : "Mist" ?>" />
    <meta name="twitter:description" content="<?= $available ? "View " . $userProfile['name'] . "'s profile on Mist, including their favorite songs, listening history, and album library." : "Mist" ?>" />
    <meta name="twitter:image" content="<?= $available ? "https://account.equestria.dev/hub/api/rest/avatar/" . $userProfile["id"] . "?dpr=2&size=64" : '' ?>" />
    <meta name="description" content="<?= $available ? "View " . $userProfile['name'] . "'s profile on Mist, including their favorite songs, listening history, and album library." : "Mist" ?>">
    <meta property="og:type" content="profile" />
    <meta property="og:title" content="<?= $available ? $userProfile['name'] . " (@" . $userProfile["login"] . ") on Mist" : "Mist" ?>" />
    <meta property="og:description" content="<?= $available ? "View " . $userProfile['name'] . "'s profile on Mist, including their favorite songs, listening history, and album library." : "Mist" ?>" />
    <meta property="og:image" content="<?= $available ? "https://account.equestria.dev/hub/api/rest/avatar/" . $userProfile["id"] . "?dpr=2&size=64" : '' ?>" />
</head>

<body>
    <div id="top-bar" style="position: relative; padding: 10px 20px; text-align: right; height: 52px !important; z-index: 99;">
        <?php if (isset($_PROFILE)): ?>
        <a target="_blank" href="https://account.equestria.dev/hub/users/<?= $_PROFILE["id"] ?>">
            <img alt="" src="https://account.equestria.dev/hub/api/rest/avatar/<?= $_PROFILE["id"] ?>?dpr=2&size=32" style="filter: none !important; border-radius: 999px; vertical-align: middle; width: 32px;">
        </a>
        <?php else: ?>
        <a href="/oauth/init">Sign in</a>
        <?php endif; ?>
    </div>

    <?php if (allowed("custom") && $userSettings["nsfw"]): ?>
        <div class="modal" id="nsfw" style="backdrop-filter: blur(100px);-webkit-backdrop-filter: blur(100px);" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div style="text-align: center;">
                            <img src="/icons/adult.svg" style="width: 64px; height: 64px; margin-bottom: 10px;">
                            <h3>This profile contains adult material that you might not want to view.</h3>
                            <p>The owner of this profile has marked it as not-safe-for-work, indicating that the content on it might not be pleasant for everything. This could include sexually explicit content, violent acts, or ethically controversial imagery.</p>
                            <p>Do you want to continue viewing this profile?</p>
                            <a data-bs-dismiss="modal" class="btn btn-primary">Continue</a>
                            <a href="/profile" class="btn btn-outline-primary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById("nsfw").addEventListener("shown.bs.modal", () => {
                document.getElementById("nsfw").classList.add("fade");
            });

            (new bootstrap.Modal(document.getElementById("nsfw"))).show();
        </script>
    <?php endif; ?>

    <?php if ($available): ?>
        <?php if (allowed("custom") && trim($userSettings["banner"]) !== ""): ?>
        <div id="banner" style="background-size: cover; background-position: center; background-image: url(&quot;<?= str_replace('"', '', $userSettings["banner"]) ?>&quot;); background-color: #eee; height: 256px; margin-top: -52px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
            <div style="background-color: rgba(255, 255, 255, .25); height: 100%; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;"></div>
        <?php else: ?>
        <div id="banner" style="background-size: cover; background-position: center; background-image: url('https://account.equestria.dev/hub/api/rest/avatar/<?= $userProfile["id"] ?>?dpr=2&size=32'); background-color: #eee; height: 256px; margin-top: -52px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
            <div style="background-color: rgba(255, 255, 255, .25); height: 100%; backdrop-filter: blur(100px); -webkit-backdrop-filter: blur(50px); border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;"></div>
        <?php endif; ?>
            <div style="text-align: center; margin-top: -64px; position: relative; z-index: 99;">
                <img alt="" src="https://account.equestria.dev/hub/api/rest/avatar/<?= $userProfile["id"] ?>?dpr=2&size=64" style="border-radius: 999px; width: 128px; height: 128px; box-shadow: 0px 0px 20px rgba(0, 0, 0, .25);">
                <h3 style="margin-top: 10px;"><?= $userProfile['name'] ?></h3>
                <h5 class="text-muted">@<?= $userProfile["login"] ?></h5>
            </div>
        </div>

        <div class="container" style="margin-top: 159px;">
            <?php if (allowed("custom") && trim($userSettings["description"]) !== ""): ?>
            <?= $Parsedown->text($userSettings["description"]) ?>
            <?php endif; ?>

            <?php if (allowed("history")): ?>
            <p class="text-muted">
                <?php if (isset($userHistory[0])): ?>
                Last listened <?= timeAgo($userHistory[count($userHistory) - 1]["date"]) ?>: <?= $songs[$userHistory[count($userHistory) - 1]["item"]]["artist"] ?> - <?= $songs[$userHistory[count($userHistory) - 1]["item"]]["title"] ?>
                <?php else: ?>
                Never listened to anything
                <?php endif; ?>
            </p>

            <?php endif; echo("<hr>"); if (allowed("history")): ?>

            <h3 style="margin-bottom: 15px;">Listening history</h3>
            <div class="list-group">
                <?php usort($userHistory, function ($a, $b) {
                    return strtotime($b["date"]) - strtotime($a["date"]);
                }); foreach (array_slice(array_values($userHistory), 0, 7) as $history): $song = $songs[$history["item"]]; ?>
                <div class="list-group-item" style="display: grid; grid-template-columns: 64px 1fr; grid-gap: 15px;">
                    <div style="display: flex; align-items: center;">
                        <img alt="" src="/albumart.php?i=<?= $history["item"] ?>" style="width: 64px; height: 64px; background-color: #eee; border-radius: 5px;">
                    </div>
                    <div style="display: flex; align-items: center;">
                        <div>
                            <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $song["title"] ?></div>
                            <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;" class="text-muted"><?= $song["artist"] ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <hr>
            <?php endif; ?>

            <?php if (allowed("favorites")): ?>
                <h3 style="margin-bottom: 15px;">Favorite songs</h3>
                <div class="list-group">
                    <?php foreach (array_slice(array_reverse($userFavorites), 0, 7) as $item): $song = $songs[$item]; ?>
                        <div class="list-group-item" style="display: grid; grid-template-columns: 64px 1fr; grid-gap: 15px;">
                            <div style="display: flex; align-items: center;">
                                <img alt="" src="/albumart.php?i=<?= $item ?>" style="width: 64px; height: 64px; background-color: #eee; border-radius: 5px;">
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div>
                                    <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $song["title"] ?></div>
                                    <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;" class="text-muted"><?= $song["artist"] ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($userFavorites) > 15): ?>
                    <div class="modal fade" id="favorites">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Favorite songs</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="list-group">
                                        <?php foreach ($userFavorites as $item): $song = $songs[$item]; ?>
                                            <div class="list-group-item" style="display: grid; grid-template-columns: 64px 1fr; grid-gap: 15px;">
                                                <div style="display: flex; align-items: center;">
                                                    <img alt="" src="/albumart.php?i=<?= $item ?>" style="width: 64px; height: 64px; background-color: #eee; border-radius: 5px;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <div>
                                                        <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $song["title"] ?></div>
                                                        <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;" class="text-muted"><?= $song["artist"] ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="all-favorites" style="margin-top: 1rem;"><a href="#" data-bs-toggle="modal" data-bs-target="#favorites" id="all-favorites-link">View all favorite songs (<?= count($userFavorites) ?>)</a></div>
                <?php endif; ?>

                <hr>
            <?php endif; ?>

            <?php if (allowed("library")): ?>
                <h3 style="margin-bottom: 15px;">Albums in library</h3>
                <div class="list-group">
                    <?php foreach (array_slice(array_reverse($userLibrary), 0, 7) as $item): $album = $albums[$item]; ?>
                        <div class="list-group-item" style="display: grid; grid-template-columns: 64px 1fr; grid-gap: 15px;">
                            <div style="display: flex; align-items: center;">
                                <img alt="" src="/albumart.php?i=<?= $item ?>" style="width: 64px; height: 64px; background-color: #eee; border-radius: 5px;">
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div>
                                    <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["title"] ?></div>
                                    <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;" class="text-muted"><?= $album["artist"] ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($userLibrary) > 15): ?>
                    <div class="modal fade" id="albums">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Albums in library</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="list-group">
                                        <?php foreach ($userLibrary as $item): $album = $albums[$item];  ?>
                                            <div class="list-group-item" style="display: grid; grid-template-columns: 64px 1fr; grid-gap: 15px;">
                                                <div style="display: flex; align-items: center;">
                                                    <img alt="" src="/albumart.php?i=<?= $item ?>" style="width: 64px; height: 64px; background-color: #eee; border-radius: 5px;">
                                                </div>
                                                <div style="display: flex; align-items: center;">
                                                    <div>
                                                        <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["title"] ?></div>
                                                        <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;" class="text-muted"><?= $album["artist"] ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="all-favorites" style="margin-top: 1rem;"><a href="#" data-bs-toggle="modal" data-bs-target="#albums" id="all-albums-link">View all albums in library (<?= count($userLibrary) ?>)</a></div>
                <?php endif; ?>

                <hr>
            <?php endif; ?>

            <div class="text-muted">
                <img class="icon" src="/icons/logo-transparent.svg" style="vertical-align: middle; filter: grayscale(1) invert(1); width: 32px; height: 32px;" alt="">
                <span style="vertical-align: middle;">Powered by <a class="link-secondary" href="https://source.equestria.dev/equestria.dev/mist" target="_blank">Mist</a> (Version <?= str_replace("|", " ", file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/version")) ?>, Build <?= trim(file_exists($_SERVER['DOCUMENT_ROOT'] . "/build.txt") ? file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/build.txt") : (file_exists("/opt/spotify/build.txt") ? file_get_contents("/opt/spotify/build.txt") : "trunk")) ?>)<span id="copyright-separator-desktop"> · </span><span id="copyright-separator-mobile"><br></span>© <?= date('Y') ?> Equestria.dev</span>
            </div>
        </div>

        <br><br>
    <?php else: ?>
        <?php if ($hasID): ?>
            <div class="container">
                <br><br>
                <h2>Searched far and wide... nothing!</h2>
                <p>No Mist profile associated with the provided URL could be found, or you don't have permission to view the requested profile. Make sure the URL is valid and contact the profile owner if you don't have permission to view it.</p>
                <?php if (!isset($_PROFILE)): ?>
                    <p>Note that you are currently logged out. If you think logged-in Mist users can access this profile, you can <a href="/oauth/init">sign in</a> and try again.</p>
                <?php endif; ?>
                <details style="margin-bottom: 1rem !important;">
                    <summary>How do I update the permissions for my profile?</summary>

                    <div class="list-group" style="margin-top: 10px;">
                        <div class="list-group-item">
                            <ul style="margin-top: 1rem !important;">
                                <li>Open Mist</li>
                                <li>Go to "Settings"</li>
                                <li>Scroll down to "Privacy"</li>
                                <li>On "Who can see your Mist profile?", select "All Mist users" or "Everyone"</li>
                                <li>Done!</li>
                            </ul>
                        </div>
                    </div>
                </details>

                <?php if (isset($_PROFILE)): ?><a href="/app/">Go back to Mist</a><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="container">
                <br><br>
                <h2>Welcome to the Mist public profile browser!</h2>
                <p>If you see this page after following a link to a Mist profile, something must have gone wrong. Try clicking on the link again or contact the person who has provided the link for more information. If you are interested in joining Mist, you can <a href="mailto:raindrops@equestria.dev" target="_blank">get in touch with us</a>, and we will see what we can do for you.</p>
                <?php if (isset($_PROFILE)): ?>
                    <p>Since you are logged in, you might want to start by <a href="/profile/?/<?= $_PROFILE["id"] ?>">viewing your own profile</a>. You can ask other people for their profile URL to view what is on their profile (depending on what they have decided to show); or you can <a href="/app/">open Mist</a> and change settings for your profile.</p>
                    <a href="/app/">Go back to Mist</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <br><br>
    <?php endif; ?>
</body>

</html>