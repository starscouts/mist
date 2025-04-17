<?php header("X-Frame-Options: SAMEORIGIN");
header("Set-Cookie: WAVY_SESSION_TOKEN=; SameSite=None; Path=/; Secure; HttpOnly; Expires=" . date("r", time() + (86400 * 730)));
?>
<script>
    window.parent.location.href = "/app/";
</script>