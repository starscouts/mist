<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $_PROFILE; ?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/settings";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Settings</title>
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
        <h2 class="desktop-title" style="margin-top: 10px; margin-bottom: 20px; margin-left: 10px;">Settings</h2>

        <div style="margin-left: 10px;">
            <h5>Preferences</h5>
            <div class="form-check form-switch">
                <input onchange="saveDS();" class="form-check-input" type="checkbox" role="switch" id="data-saving">
                <label class="form-check-label" for="data-saving">
                    Enable data saving
                    <div class="text-muted small">Data saving disables playing lossless and high-resolution audio, as well as music videos. Instead, you will get 256 kbps AAC-encoded audio, which is highly efficient. If you use Bluetooth headphones, the difference should be unnoticeable.</div>
                </label>
            </div>
            <script>
                if (localStorage.getItem("data-saving") === "true") document.getElementById("data-saving").checked = true;
                function saveDS() {
                    localStorage.setItem("data-saving", document.getElementById("data-saving").checked ? "true" : "false");
                    window.parent.location.reload();
                }
            </script>

            <div class="form-check form-switch" style="margin-top: 10px;">
                <input onchange="saveOA();" class="form-check-input" type="checkbox" role="switch" id="noamp">
                <label class="form-check-label" for="noamp">
                    Disable audio amplification
                    <div class="text-muted small">By default, Mist makes your music louder, so it doesn't otherwise seem too quiet. If you have issues with clipping, or this feature does not otherwise play nicely with your device, you might want to turn on this option.</div>
                </label>
            </div>
            <script>
                if (localStorage.getItem("noamp") === "true") document.getElementById("noamp").checked = true;
                function saveOA() {
                    localStorage.setItem("noamp", document.getElementById("noamp").checked ? "true" : "false");
                    window.parent.location.reload();
                }
            </script>

            <div class="form-check form-switch" style="margin-top: 10px;">
                <input onchange="saveN();" class="form-check-input" type="checkbox" role="switch" id="normalize">
                <label class="form-check-label" for="normalize">
                    Normalize loudness
                    <div class="text-muted small">Normalizing adjusts the volume each song is played at to be the same level for every song. This will avoid you having to change your device's volume between each track, and should typically not be turned off. Powered by ReplayGain.</div>
                </label>
            </div>
            <script>
                if (localStorage.getItem("normalize") === "true") document.getElementById("normalize").checked = true;
                function saveN() {
                    localStorage.setItem("normalize", document.getElementById("normalize").checked ? "true" : "false");
                    window.parent.location.reload();
                }
            </script>

            <div class="form-check form-switch" id="stella" style="display: none;margin-top: 10px;">
                <input onchange="saveST();" class="form-check-input" type="checkbox" role="switch" id="enable-stella">
                <label class="form-check-label" for="enable-stella">
                    Mist Stella
                    <div class="text-muted small">Enjoy your music is a unique way thanks to the Mist Stella spatial audio technology. Stella makes your music feel like it's coming from all around you, giving you a concert-like experience. Note that Stella uses slightly more bandwidth than lossless streaming. <a href="#" onclick="window.parent.location.hash = '#/stella';">See compatible songs.</a></div>
                </label>
            </div>
            <script>
                if (localStorage.getItem("show-stella-settings") === "true") document.getElementById("stella").style.display = "";

                if (localStorage.getItem("enable-stella") === "true") document.getElementById("enable-stella").checked = true;
                function saveST() {
                    localStorage.setItem("enable-stella", document.getElementById("enable-stella").checked ? "true" : "false");
                    localStorage.setItem("show-stella-settings", "true");
                    window.parent.location.reload();
                }
            </script>

            <?php if (str_contains($_SERVER['HTTP_USER_AGENT'], "MistNative/")): ?>
                <div class="form-check form-switch" style="margin-top: 10px;">
                    <input onchange="saveDN();" class="form-check-input" type="checkbox" role="switch" id="desktop-notification">
                    <label class="form-check-label" for="desktop-notification">
                        Display notification when song changes
                        <div class="text-muted small">If this is enabled, a desktop notification will be shown when the song being played changes, containing information about the new song. This requires having notifications enabled in your system settings.</div>
                    </label>
                </div>
                <script>
                    if (localStorage.getItem("desktop-notification") === "true") document.getElementById("desktop-notification").checked = true;
                    function saveDN() {
                        localStorage.setItem("desktop-notification", document.getElementById("desktop-notification").checked ? "true" : "false");
                    }
                </script>
            <?php endif; ?>

            <?php if (str_contains($_SERVER['HTTP_USER_AGENT'], "MistNative/")): ?>
                <div class="form-check form-switch" style="margin-top: 10px;">
                    <input onchange="saveRP();" class="form-check-input" type="checkbox" role="switch" id="rich-presence">
                    <label class="form-check-label" for="desktop-notification">
                        Show the song you are listening to on Discord
                        <div class="text-muted small">Using Discord Rich Presence, Mist can display on Discord the song you are currently listening to. You need to have the Discord desktop app installed and running on your computer for this to work.</div>
                    </label>
                </div>
                <script>
                    if (localStorage.getItem("rich-presence") === "true") document.getElementById("rich-presence").checked = true;
                    function saveRP() {
                        localStorage.setItem("rich-presence", document.getElementById("rich-presence").checked ? "true" : "false");

                        if (localStorage.getItem("rich-presence") === "false") {
                            window.parent.discordRichPresenceData = null;
                        } else {
                            window.parent.discordRichPresenceData = {
                                largeImageKey: "logo",
                                buttons : [
                                    { label: 'View profile', url: 'https://mist.equestria.horse/profile/?/<?= $_PROFILE["id"] ?>' }
                                ]
                            };
                        }
                    }
                </script>
            <?php endif; ?>

            <hr>
            <h5>Privacy</h5>

            <div style="display: grid; grid-template-columns: 1fr max-content; grid-gap: 20px;">
                <div>
                    <label for="privacy-profile">
                        Who can see your Mist profile?
                        <div class="text-muted small">Your Mist profile always shows some information about you that is publicly available on your Equestria.dev account. If you would like people not to know you are using Mist, you can change it here.</div>
                    </label>
                </div>
                <div>
                    <select disabled onchange="savePrivacy();" class="form-select" id="privacy-profile">
                        <option selected value="2">Everyone</option>
                        <option value="1">All Mist users</option>
                        <option value="0">Only me</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr max-content; grid-gap: 20px; margin-top: 10px;">
                <div>
                    <label for="privacy-library">
                        Who can see your library?
                        <div class="text-muted small">Your library can show up on your profile if you wish for it to show up. It will show all the albums and songs you have manually added to your library and not the songs you have only searched for.</div>
                    </label>
                </div>
                <div>
                    <select disabled onchange="savePrivacy();" class="form-select" id="privacy-library">
                        <option value="2">Everyone</option>
                        <option value="1">All Mist users</option>
                        <option selected value="0">Only me</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr max-content; grid-gap: 20px; margin-top: 10px;">
                <div>
                    <label for="privacy-library">
                        Who can see your history and activity?
                        <div class="text-muted small">If this is enabled, other people can see when you last used Mist and which songs you last listened to. Turning this on might reveal personal information, so be careful if you set this to "Everyone".</div>
                    </label>
                </div>
                <div>
                    <select disabled onchange="savePrivacy();" class="form-select" id="privacy-history">
                        <option value="2">Everyone</option>
                        <option value="1">All Mist users</option>
                        <option selected value="0">Only me</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr max-content; grid-gap: 20px; margin-top: 10px;">
                <div>
                    <label for="privacy-favorites">
                        Who can see your favorites?
                        <div class="text-muted small">Other people can see your favorites on your profile to know what songs you like. If you don't turn on the option below, other Mist users will not be able to directly listen to them in the Mist app.</div>
                    </label>
                </div>
                <div>
                    <select disabled onchange="savePrivacy();" class="form-select" id="privacy-favorites">
                        <option value="2">Everyone</option>
                        <option value="1">All Mist users</option>
                        <option selected value="0">Only me</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr max-content; grid-gap: 20px; margin-top: 10px;">
                <div>
                    <label for="privacy-listen">
                        Who can listen to your favorites?
                        <div class="text-muted small">If this is enabled, other Mist users will see your favorites directly in the application, giving them the option to listen to them if they want to. This means you won't have to share anything manually.</div>
                    </label>
                </div>
                <div>
                    <select disabled onchange="savePrivacy();" class="form-select" id="privacy-listen">
                        <option value="1">All Mist users</option>
                        <option selected value="0">Only me</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr max-content; grid-gap: 20px; margin-top: 10px;">
                <div>
                    <label for="privacy-custom">
                        Who can see your profile customizations?
                        <div class="text-muted small">If you customize your Mist profile through the options provided inside the app, you can choose who will see these customizations. This includes your banner, color, description, and all other settings.</div>
                    </label>
                </div>
                <div>
                    <select disabled onchange="savePrivacy();" class="form-select" id="privacy-custom">
                        <option value="2">Everyone</option>
                        <option selected value="1">All Mist users</option>
                        <option value="0">Only me</option>
                    </select>
                </div>
            </div>

            <hr>
            <h5>Profile</h5>
            <p><a target="_blank" href="/profile/?/<?= $_PROFILE["id"] ?>">View your profile</a> · <a target="_blank" href="#" id="profile-url-btn">Copy profile URL</a></p>
            <div class="mb-3">
                <label for="description" class="form-label">Profile description:</label>
                <textarea onchange="saveCustom();" maxlength="500" class="form-control" id="description" rows="3" placeholder="You can enter some information about your musical tastes, your experience working with audio and music, the hardware you use, etc... Markdown is also supported. If the content in your profile is not safe for work, remember to check the corresponding box."></textarea>
            </div>

            <div class="mb-3">
                <label for="banner" class="form-label">Profile banner:</label>
                <input onchange="saveCustom();" maxlength="120" type="text" class="form-control" id="banner" placeholder="Enter an image URL or a Derpibooru URL">
            </div>

            <div class="form-check form-switch">
                <input onchange="saveCustom();" class="form-check-input" type="checkbox" role="switch" id="nsfw">
                <label class="form-check-label" for="nsfw">
                    Mark profile as not safe for work
                    <div class="text-muted small">If your profile contains adult or violent content, check this box. This will show a warning when users open your profile informing them about the content that can be found on it, and giving them the option to not read your profile if needed.</div>
                </label>
            </div>

            <hr>
            <?php if (str_contains($_SERVER['HTTP_USER_AGENT'], "MistNative/")): ?>
            <a onclick="window.parent.MistNative.about();" href="#">About Mist</a>
            <?php else: ?>
            <div class="text-muted">
                <img class="icon" src="/assets/logo-transparent.svg" style="vertical-align: middle; filter: grayscale(1) invert(1); width: 32px; height: 32px;" alt="">
                <span style="vertical-align: middle;">Mist version <?= str_replace("|", " ", file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/version")) ?> (build <?= trim(file_exists($_SERVER['DOCUMENT_ROOT'] . "/build.txt") ? file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/build.txt") : (file_exists("/opt/spotify/build.txt") ? file_get_contents("/opt/spotify/build.txt") : "trunk")) ?>)<span id="copyright-separator-desktop"> · </span><span id="copyright-separator-mobile"><br></span>© <?= date('Y') ?> Equestria.dev</span>
            </div>
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
            <?php endif; global $privacy; global $profile; ?>
            <br><br>
            <script>
                async function saveCustom() {
                    document.getElementById("banner").disabled = true;
                    document.getElementById("description").disabled = true;
                    document.getElementById("nsfw").disabled = true;

                    let customData = {
                        nsfw: document.getElementById("nsfw").checked,
                        description: document.getElementById("description").value.trim().substring(0, 500),
                        url: document.getElementById("banner").value.trim().substring(0, 120)
                    }

                    console.log(customData);

                    let fd = new FormData();
                    fd.append('nsfw', customData.nsfw);
                    fd.append('description', customData.description);
                    fd.append('url', customData.url);

                    await fetch("/api/saveProfile.php", {
                        body: fd,
                        method: "post"
                    });

                    document.getElementById("banner").disabled = false;
                    document.getElementById("description").disabled = false;
                    document.getElementById("nsfw").disabled = false;
                }

                async function savePrivacy() {
                    document.getElementById("privacy-profile").disabled = true;
                    document.getElementById("privacy-library").disabled = true;
                    document.getElementById("privacy-history").disabled = true;
                    document.getElementById("privacy-favorites").disabled = true;
                    document.getElementById("privacy-listen").disabled = true;
                    document.getElementById("privacy-custom").disabled = true;

                    await fetch("/api/savePrivacy.php?profile=" + document.getElementById("privacy-profile").value + "&library=" + document.getElementById("privacy-library").value + "&history=" + document.getElementById("privacy-history").value + "&favorites=" + document.getElementById("privacy-favorites").value + "&listen=" + document.getElementById("privacy-listen").value + "&custom=" + document.getElementById("privacy-custom").value);

                    document.getElementById("privacy-profile").disabled = false;
                    document.getElementById("privacy-library").disabled = false;
                    document.getElementById("privacy-history").disabled = false;
                    document.getElementById("privacy-favorites").disabled = false;
                    document.getElementById("privacy-listen").disabled = false;
                    document.getElementById("privacy-custom").disabled = false;
                }

                function loadSettings(privacy, profile) {
                    window.privacySettings = privacy;
                    window.profileSettings = profile;

                    for (let item of Object.keys(privacy)) {
                        document.getElementById("privacy-" + item).value = privacy[item].toString();
                    }

                    document.getElementById("nsfw").checked = window.profileSettings.nsfw;
                    document.getElementById("description").value = window.profileSettings.description;
                    document.getElementById("banner").value = window.profileSettings.banner_orig ?? window.profileSettings.banner;

                    document.getElementById("privacy-profile").disabled = false;
                    document.getElementById("privacy-library").disabled = false;
                    document.getElementById("privacy-history").disabled = false;
                    document.getElementById("privacy-favorites").disabled = false;
                    document.getElementById("privacy-listen").disabled = false;
                    document.getElementById("privacy-custom").disabled = false;
                }

                document.getElementById("profile-url-btn").onclick = (e) => {
                    e.preventDefault();
                    navigator.clipboard.writeText("https://mist.equestria.horse/profile/?/<?= $_PROFILE["id"] ?>");
                }

                loadSettings(JSON.parse(`<?= json_encode($privacy) ?>`), JSON.parse(`<?= json_encode($profile) ?>`));
            </script>
        </div>
    </div>
</body>
</html>