const watch = require('node-watch');
const fs = require('fs');
let lastUpdate = 0;

watch('..', { recursive: true }, function(evt, name) {
    if (name.includes("includes/users") || name.includes("includes/tokens") || name.includes("includes/app.json") || name.includes("assets/content")) return;

    if (new Date().getTime() - lastUpdate > 60000) {
        let buildFile = fs.readFileSync("/opt/spotify/build.txt").toString().trim();
        let build = parseInt(buildFile.replace(/^(.*?)(\d+)$/gm, "$2")) + 1;
        fs.writeFileSync("/opt/spotify/build.txt", buildFile.replace(/^(.*?)(\d+)$/gm, "$1") + build);
        lastUpdate = new Date().getTime();
    }
});
