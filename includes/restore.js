console.log("Reading archive...");
const archive = require('../assets/content/archive.json');
const fs = require('fs');
const path = require('path');

let i = 1;

for (let id of Object.keys(archive)) {
    console.log("Restoring " + id + " to " + archive[id] + " (" + i + "/" + Object.keys(archive).length + ")");
    if (!fs.existsSync(path.dirname(archive[id]))) fs.mkdirSync(path.dirname(archive[id]), { recursive: true });
    fs.renameSync("../assets/content/" + id + ".flac", archive[id]);
    i++;
}

console.log("Cleaning up...");
for (let file of fs.readdirSync("../assets/content")) {
    if (!file.startsWith(".") && file !== "_") {
        fs.rmSync("../assets/content/" + file, { recursive: true });
    }
}

console.log("Done. Run the process.js script again to reprocess the data.");