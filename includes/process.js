const fs = require('fs');
const cp = require('child_process');
const path = require('path');
const uuid = require('crypto').randomUUID;

const substitutes = [
    ["illenium", "ILLENIUM"],
    ["SueCo", "Sueco"],
    ["Illenium", "ILLENIUM"],
    ["Princewhateverer", "PrinceWhateverer"],
    ["Kennyoung", "kennyoung"],
    ["4EverfreeBrony", "4everfreebrony"],
    ["4Everfreebrony", "4everfreebrony"]
];

if (!fs.existsSync("../assets/content/_")) fs.mkdirSync("../assets/content/_");
if (!fs.existsSync("../assets/content/songs.json")) fs.writeFileSync("../assets/content/songs.json", "{}");
if (!fs.existsSync("../assets/content/albums.json")) fs.writeFileSync("../assets/content/albums.json", "{}");
if (!fs.existsSync("../assets/content/archive.json")) fs.writeFileSync("../assets/content/archive.json", "{}");

const songs = require('../assets/content/songs.json');
const albums = require('../assets/content/albums.json');
const archive = require('../assets/content/archive.json');

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

function substitute(text) {
    for (let sub of substitutes) {
        text = text.replaceAll(sub[0], sub[1]);
    }

    return text;
}

function timeToString(time) {
    if (!isNaN(parseInt(time))) {
        time = new Date(time).getTime();
    }

    let periods = ["second", "minute", "hour", "day", "week", "month", "year", "age"];

    let lengths = ["60", "60", "24", "7", "4.35", "12", "100"];

    let now = new Date().getTime();

    let difference = time / 1000;
    let period;

    let j;

    for (j = 0; difference >= lengths[j] && j < lengths.length - 1; j++) {
        difference /= lengths[j];
    }

    difference = Math.round(difference);

    period = periods[j];

    return `${difference} ${period}${difference > 1 ? "s" : ""}`;
}

(async () => {
    let timePerOp = [];
    let list = (await scandir("../assets/content/_")).filter(i => i.endsWith(".flac"));
    process.stdout.write(" files found, " + list.length + " to process\n");
    let index = 0;

    function updateETA() {
        if (timePerOp.length > 5) {
            process.stdout.write("(" + timeToString((list.length - index) * (timePerOp.reduce((a, b) => a + b) / timePerOp.length)) + " remaining | " + index + "/" + list.length + " processed | " + ((index / list.length) * 100).toFixed(1) + "% complete)");
        } else {
            process.stdout.write("(" + index + "/" + list.length + " processed | " + ((index / list.length) * 100).toFixed(1) + "% complete)");
        }
    }

    for (let file of list) {
        let start = new Date().getTime();

        process.stdout.clearLine(null); process.stdout.cursorTo(0);
        console.log(file);
        updateETA();
        let id = uuid();

        process.stdout.clearLine(null); process.stdout.cursorTo(0);
        console.log("    Gathering metadata...");
        updateETA();
        let metadata = JSON.parse(cp.execFileSync("ffprobe", ["-v", "quiet", "-print_format", "json", "-show_format", "-show_streams", file]).toString());
        if (!metadata['format']['tags']) continue;

        songs[id] = {
            title: substitute(metadata['format']['tags']['TITLE'] ?? metadata['format']['tags']['title'] ?? path.basename(file, ".flac")),
            length: parseInt(metadata['format']['duration']),
            album: substitute(metadata['format']['tags']['ALBUM'] ?? metadata['format']['tags']['album'] ?? "Unknown album"),
            artist: (substitute(metadata['format']['tags']['ARTIST'] ?? metadata['format']['tags']['artist'] ?? "Unknown artist")).replaceAll(";", ", ").replaceAll(" & ", ", ").replaceAll("&", ", ").replaceAll(", and ", ", "),
            albumArtist: (substitute(metadata['format']['tags']['album_artist'] ?? metadata['format']['tags']['ARTIST'] ?? metadata['format']['tags']['artist'] ?? "Unknown artist")).replaceAll(";", ", ").replaceAll("&", ", ").replaceAll(", and ", ", "),
            date: parseInt(((metadata['format']['tags']['DATE'] ?? metadata['format']['tags']['date'] ?? "0").split("-")[0]).substring(0, 4)) ?? 0,
            track: parseInt(metadata['format']['tags']['track']) ?? 0,
            disc: parseInt(metadata['format']['tags']['disc']) ?? 1,
            copyright: metadata['format']['tags']['COPYRIGHT'] ?? metadata['format']['tags']['copyright'] ?? "",
            size: parseInt(metadata['format']['size']),
            bitRate: parseInt(metadata['format']['bit_rate']),
            bitDepth: parseInt(metadata['streams'][0]['bits_per_raw_sample']),
            sampleRate: parseInt(metadata['streams'][0]['sample_rate']),
            hiRes: parseInt(metadata['streams'][0]['sample_rate']) > 44100 || parseInt(metadata['streams'][0]['bits_per_raw_sample']) > 16,
            channels: parseInt(metadata['streams'][0]['channels']),
        }

        process.stdout.clearLine(null); process.stdout.cursorTo(0);
        console.log("    Writing songs list...");
        updateETA();
        fs.writeFileSync("../assets/content/songs.json", JSON.stringify(songs));

        if (fs.existsSync("/opt/mist")) {
            process.stdout.clearLine(null); process.stdout.cursorTo(0);
            console.log("    Encoding AAC version...");
            updateETA();
            cp.execFileSync("ffmpeg", ["-i", file, "-map", "0", "-map", "-0:v?", "-b:a", "256k", "/opt/mist/aac/" + id + ".m4a"], { stdio: "ignore" });

            process.stdout.clearLine(null); process.stdout.cursorTo(0);
            console.log("    Encoding FLAC version...");
            updateETA();
            archive[id] = file;
            fs.writeFileSync("../assets/content/archive.json", JSON.stringify(archive));
            fs.copyFileSync(file, "/opt/mist/flac/" + id + ".flac");

            process.stdout.clearLine(null); process.stdout.cursorTo(0);
            console.log("    Extracting album art...");
            updateETA();

            try {
                cp.execFileSync("ffmpeg", ["-i", file, "-an", "/opt/mist/jpeg/" + id + ".jpg"], { stdio: "ignore" });
            } catch (e) {
                process.stdout.clearLine(null); process.stdout.cursorTo(0);
                console.error(e);
                updateETA();
                fs.copyFileSync("../assets/default.jpg", "/opt/mist/jpeg/" + id + ".jpg");
            }
        } else {
            process.stdout.clearLine(null); process.stdout.cursorTo(0);
            console.log("    Encoding AAC version...");
            updateETA();
            cp.execFileSync("ffmpeg", ["-i", file, "-map", "0", "-map", "-0:v?", "-b:a", "256k", "../assets/content/" + id + ".m4a"], { stdio: "ignore" });

            process.stdout.clearLine(null); process.stdout.cursorTo(0);
            console.log("    Encoding FLAC version...");
            updateETA();
            archive[id] = file;
            fs.writeFileSync("../assets/content/archive.json", JSON.stringify(archive));
            fs.copyFileSync(file, "../assets/content/" + id + ".flac");

            process.stdout.clearLine(null); process.stdout.cursorTo(0);
            console.log("    Extracting album art...");
            updateETA();
            cp.execFileSync("ffmpeg", ["-i", file, "-an", "../assets/content/" + id + ".jpg"], { stdio: "ignore" });
        }

        process.stdout.clearLine(null); process.stdout.cursorTo(0);
        console.log("    Removing original file...");
        updateETA();
        fs.unlinkSync(file);

        process.stdout.clearLine(null); process.stdout.cursorTo(0);
        console.log("    Done: " + id);
        updateETA();

        timePerOp.push(new Date().getTime() - start);
        index++;
    }

    process.stdout.clearLine(null); process.stdout.cursorTo(0);
    console.log("Collecting albums...");

    for (let song of Object.keys(songs)) {
        if (Object.values(albums).filter(i => i.title === songs[song].album && i.artist === songs[song].albumArtist).length > 0) {
            Object.values(albums).filter(i => i.title === songs[song].album)[0].tracks.push(song);
            Object.values(albums).filter(i => i.title === songs[song].album)[0].hiRes = Object.values(albums).filter(i => i.title === songs[song].album)[0].hiRes || songs[song].hiRes;
        } else {
            let albumID = uuid();

            if (fs.existsSync("/opt/mist")) {
                if (fs.existsSync("/opt/mist/jpeg/" + song + ".jpg")) fs.copyFileSync("/opt/mist/jpeg/" + song + ".jpg", "/opt/mist/jpeg/" + albumID + ".jpg");
            } else {
                if (fs.existsSync("../assets/content/" + song + ".jpg")) fs.copyFileSync("../assets/content/" + song + ".jpg", "../assets/content/" + albumID + ".jpg");
            }

            albums[albumID] = {
                title: songs[song].album,
                artist: songs[song].albumArtist,
                date: songs[song].date,
                hiRes: songs[song].hiRes,
                copyright: songs[song].copyright,
                tracks: [song]
            }
        }
    }

    console.log("Cleaning up...");

    for (let songID of Object.keys(songs)) {
        if (fs.existsSync("/opt/mist")) {
            if (!fs.existsSync("/opt/mist/flac/" + songID + ".flac") || !fs.existsSync("/opt/mist/aac/" + songID + ".m4a") || !fs.existsSync("/opt/mist/jpeg/" + songID + ".jpg")) {
                delete songs[songID];
            }
        } else {
            if (!fs.existsSync("../assets/content/" + songID + ".flac") || !fs.existsSync("../assets/content/" + songID + ".m4a") || !fs.existsSync("../assets/content/" + songID + ".jpg")) {
                delete songs[songID];
            }
        }
    }

    console.log("Linking...");
    let idList = [...Object.keys(songs), ...Object.keys(albums)];

    if (fs.existsSync("/opt/mist")) {
        for (let file of fs.readdirSync("/opt/mist/flac")) {
            if (fs.lstatSync("/opt/mist/flac/" + file).isFile()) {
                let id = path.basename(file, path.extname(file));

                if (!idList.includes(id) && !file.endsWith(".json")) {
                    fs.unlinkSync("/opt/mist/flac/" + file);
                    if (fs.existsSync("../assets/content/" + file)) fs.unlinkSync("../assets/content/" + file);
                } else {
                    if (!fs.existsSync("../assets/content/" + file)) fs.symlinkSync("/opt/mist/flac/" + file, "../assets/content/" + file);
                }
            }
        }

        for (let file of fs.readdirSync("/opt/mist/aac")) {
            if (fs.lstatSync("/opt/mist/aac/" + file).isFile()) {
                let id = path.basename(file, path.extname(file));

                if (!idList.includes(id) && !file.endsWith(".json")) {
                    fs.unlinkSync("/opt/mist/aac/" + file);
                    if (fs.existsSync("../assets/content/" + file)) fs.unlinkSync("../assets/content/" + file);
                } else {
                    if (!fs.existsSync("../assets/content/" + file)) fs.symlinkSync("/opt/mist/aac/" + file, "../assets/content/" + file);
                }
            }
        }

        for (let file of fs.readdirSync("/opt/mist/jpeg")) {
            if (fs.lstatSync("/opt/mist/jpeg/" + file).isFile()) {
                let id = path.basename(file, path.extname(file));

                if (!idList.includes(id) && !file.endsWith(".json")) {
                    fs.unlinkSync("/opt/mist/jpeg/" + file);
                    if (fs.existsSync("../assets/content/" + file)) fs.unlinkSync("../assets/content/" + file);
                } else {
                    if (!fs.existsSync("../assets/content/" + file)) fs.symlinkSync("/opt/mist/jpeg/" + file, "../assets/content/" + file);
                }
            }
        }
    } else {
        for (let file of fs.readdirSync("../assets/content")) {
            if (fs.lstatSync("../assets/content/" + file).isFile()) {
                let id = path.basename(file, path.extname(file));

                if (!idList.includes(id) && !file.endsWith(".json")) {
                    fs.unlinkSync("../assets/content/" + file);
                }
            }
        }
    }

    console.log("Adding tracks to albums...");

    for (let albumID of Object.keys(albums)) {
        let album = albums[albumID];
        album["tracks"] = [...new Set(album["tracks"])].filter(i => songs[i]).sort((a, b) => {
            return songs[a]['track'] - songs[b]['track'];
        });

        if (fs.existsSync("/opt/mist")) {
            if (!fs.existsSync("/opt/mist/jpeg/" + albumID + ".jpg")) fs.copyFileSync("../assets/default.jpg", "/opt/mist/jpeg/" + albumID + ".jpg");
        } else {
            if (!fs.existsSync("../assets/content/" + albumID + ".jpg")) fs.copyFileSync("../assets/default.jpg", "../assets/content/" + albumID + ".jpg");
        }
    }

    for (let albumID of Object.keys(albums)) {
        if (albums[albumID]["tracks"].length === 0) {
            delete albums[albumID];
        }
    }

    console.log("Writing metadata...");

    fs.writeFileSync("../assets/content/songs.json", JSON.stringify(songs));
    fs.writeFileSync("../assets/content/albums.json", JSON.stringify(albums));

    console.log("Done!");
})()