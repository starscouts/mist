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
    <title>modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform" style="background-color: transparent !important;">
    <script src="/assets/js/common.js"></script>
    <div class="modal fade" id="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" id="modal-header">
                    <h4 class="modal-title" id="modal-title">Title</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding: 0;">
                    <iframe id="modal-frame" style="width: 100%; border-bottom-right-radius: 0.5rem; margin-bottom: -6px; border-bottom-left-radius: 0.5rem; height: calc(100vh - 130px);"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        window._modal = new bootstrap.Modal(document.getElementById("modal"));

        document.getElementById("modal").addEventListener("hidden.bs.modal", () => {
            window.parent.document.getElementById("modal").style.display = "none";
        });
    </script>

    <div class="modal fade" id="error" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" id="modal-header">
                    <h4 class="modal-title">An internal error has occurred</h4>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger">
                        <p>If this is the first time you see this, simply restart Mist and try again.</p>
                        <p>If you have been seeing this multiple times, you might be encountering a bug and you should report it to your administrator.</p>
                        <a onclick="window.parent.location.reload();" class="btn btn-danger">Reload</a>

                        <hr>
                        <pre id="error-content"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window._error = new bootstrap.Modal(document.getElementById("error"));

        document.getElementById("error").addEventListener("shown.bs.modal", () => {
            window.parent.document.getElementById("modal").style.display = "";
        });
    </script>
</body>
</html>