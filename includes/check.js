const fs = require('fs');
const cp = require('child_process');
const path = require('path');
const crypto = require('crypto');

const songs = require('../assets/content/songs.json');
const albums = require('../assets/content/albums.json');

function scandir(dir) {
    let count = 0;

    function updateScandirDisplay() {
        process.stdout.clearLine(null);
        process.stdout.cursorTo(0);
        process.stdout.write("Scanning... " + count);
    }

    return new Promise((res, rej) => {
        const walk = (dir, done) => {
            let results = [];
            fs.readdir(dir, function(err, list) {
                count++;
                updateScandirDisplay();
                if (err) return done(err);
                let pending = list.length;

                if (!pending) return done(null, results);
                list.forEach(function(file) {
                    count++;
                    updateScandirDisplay();
                    file = path.resolve(dir, file);
                    fs.stat(file, function(err, stat) {
                        if (stat && stat.isDirectory()) {
                            walk(file, function(err, res) {
                                results = results.concat(res);
                                if (!--pending) done(null, results);
                            });
                        } else {
                            results.push(file);
                            if (!--pending) done(null, results);
                        }
                    });
                });
            });
        }

        walk(dir, (err, data) => {
            if (err) {
                rej(err);
            } else {
                res(data);
            }
        })
    });
}

(async () => {
    let list = (await scandir("../assets/content/_")).filter(i => i.endsWith(".flac"));
    process.stdout.write(" files found\n");
    let index = 0;
    console.log("Checking for corrupted files...");

    for (let file of list) {
        process.stdout.cursorTo(0);
        process.stdout.write(index + "/" + list.length);
        let metadata = JSON.parse(cp.execFileSync("ffprobe", ["-v", "quiet", "-print_format", "json", "-show_format", "-show_streams", file]).toString());
        if (!metadata['format']['tags']) {
            process.stdout.clearLine(null);
            process.stdout.cursorTo(0);
            console.log(file + ": is corrupted or has no metadata");
        }
        index++;
    }

    process.stdout.clearLine(null);
    process.stdout.cursorTo(0);
    console.log("Checking for missing album art...");

    index = 0;
    for (let id of Object.keys(songs)) {
        process.stdout.cursorTo(0);
        process.stdout.write(index + "/" + (Object.keys(songs).length + Object.keys(albums).length));
        if (fs.existsSync("../assets/content/" + id + ".jpg")) {
            if (crypto.createHash("md5").update(fs.readFileSync("../assets/content/" + id + ".jpg")).digest("hex") === "fd89a584ae426e72801f2a4c2653ae7a") {
                process.stdout.clearLine(null);
                process.stdout.cursorTo(0);
                console.log(songs[id].title + " (" + id + "): using placeholder album art");
            }
        } else {
            process.stdout.clearLine(null);
            process.stdout.cursorTo(0);
            console.log(songs[id].title + " (" + id + "): no album art file");
        }
        index++;
    }

    for (let id of Object.keys(albums)) {
        process.stdout.cursorTo(0);
        process.stdout.write(index + "/" + (Object.keys(songs).length + Object.keys(albums).length));
        if (fs.existsSync("../assets/content/" + id + ".jpg")) {
            if (crypto.createHash("md5").update(fs.readFileSync("../assets/content/" + id + ".jpg")).digest("hex") === "fd89a584ae426e72801f2a4c2653ae7a") {
                process.stdout.clearLine(null);
                process.stdout.cursorTo(0);
                console.log(albums[id].title + " (" + id + "): using placeholder album art");
            }
        } else {
            process.stdout.clearLine(null);
            process.stdout.cursorTo(0);
            console.log(albums[id].title + " (" + id + "): no album art file");
        }
        index++;
    }

    process.stdout.clearLine(null);
    process.stdout.cursorTo(0);
    console.log("Done.");
})();