const songs = require('../assets/content/songs.json');
const cp = require('child_process');
const zlib = require('zlib');
const fs = require("fs");
const crypto = require("crypto");

if (!process.argv[2]) {
    console.log("Error: Please pass the ID of a song to encode in Stella as a parameter.");
    return;
}

if (!songs[process.argv[2]]) {
    console.log("Error: No song with ID " + process.argv[2] + " could be found.");
    return;
}

let song = songs[process.argv[2]];
console.log("Song: " + song['artist'] + " - " + song['title']);

console.log("Preparing to split stems...");
cp.execSync("ssh zephyrheights rm -rf /root/StellaTemp", { stdio: "inherit" });
cp.execSync("ssh zephyrheights mkdir -p /root/StellaTemp", { stdio: "inherit" });
cp.execSync("scp /opt/mist/flac/" + process.argv[2] + ".flac zephyrheights:/root/StellaTemp/source.flac", { stdio: "inherit" });

console.log("Downsampling to 16bit 44.1kHz...");
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/source.flac -ar 44100 -c:a pcm_s16le /root/StellaTemp/source.wav", { stdio: "inherit" });

console.log("Splitting into 5 stems...");
cp.execSync("ssh zephyrheights spleeter separate -p spleeter:5stems -b 512k -o /root/StellaTemp/stems -f {instrument}.{codec} /root/StellaTemp/source.flac");
cp.execSync("ssh zephyrheights mv /root/StellaTemp/stems/* /root/StellaTemp/");
cp.execSync("ssh zephyrheights rmdir /root/StellaTemp/stems");

console.log("Applying spatial effects to stems...");
cp.execSync("ssh zephyrheights sox -S /root/StellaTemp/source.wav /root/StellaTemp/hpf.wav sinc 11k -t 1", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/drums.wav -af \"bass=5\" /root/StellaTemp/drums_pre.wav", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/other.wav -af apulsator=hz=0.015 -af aecho=1.0:0.7:20:0.5 /root/StellaTemp/other_pre.wav", { stdio: "inherit" });
cp.execSync("ssh zephyrheights sox /root/StellaTemp/hpf.wav /root/StellaTemp/hpf_out.wav reverb 30 25 75", { stdio: "inherit" });
cp.execSync("ssh zephyrheights sox /root/StellaTemp/bass.wav /root/StellaTemp/bass_out.wav reverb 30 25 75", { stdio: "inherit" });
cp.execSync("ssh zephyrheights sox /root/StellaTemp/drums_pre.wav /root/StellaTemp/drums_out.wav reverb 30 25 75", { stdio: "inherit" });
cp.execSync("ssh zephyrheights sox /root/StellaTemp/other_pre.wav /root/StellaTemp/other_out.wav reverb 30 25 75", { stdio: "inherit" });
cp.execSync("ssh zephyrheights sox /root/StellaTemp/piano.wav /root/StellaTemp/piano_out.wav reverb 30 25 75", { stdio: "inherit" });
cp.execSync("ssh zephyrheights sox /root/StellaTemp/vocals.wav /root/StellaTemp/vocals_out.wav reverb 30 25 75", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/vocals_out.wav /root/StellaTemp/vocals.flac", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/piano_out.wav /root/StellaTemp/piano.flac", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/other_out.wav /root/StellaTemp/other.flac", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/drums_out.wav /root/StellaTemp/drums.flac", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/bass_out.wav /root/StellaTemp/bass.flac", { stdio: "inherit" });
cp.execSync("ssh zephyrheights ffmpeg -i /root/StellaTemp/hpf_out.wav /root/StellaTemp/hpf.flac", { stdio: "inherit" });

console.log("Exporting...");
cp.execSync("ssh zephyrheights mkdir /root/StellaTemp/out", { stdio: "inherit" });
cp.execSync("ssh zephyrheights mv /root/StellaTemp/vocals.flac /root/StellaTemp/piano.flac /root/StellaTemp/other.flac /root/StellaTemp/drums.flac /root/StellaTemp/bass.flac /root/StellaTemp/hpf.flac /root/StellaTemp/out", { stdio: "inherit" });
cp.execSync("mkdir tmp", { stdio: "inherit" });
cp.execSync("scp zephyrheights:/root/StellaTemp/out/* tmp", { stdio: "inherit" });
cp.execSync("ssh zephyrheights rm -rf /root/StellaTemp", { stdio: "inherit" });

console.log("Preparing Stella file...");

let files = {
    bass: zlib.deflateRawSync(fs.readFileSync("tmp/bass.flac")),
    drums: zlib.deflateRawSync(fs.readFileSync("tmp/drums.flac")),
    hpf: zlib.deflateRawSync(fs.readFileSync("tmp/hpf.flac")),
    other: zlib.deflateRawSync(fs.readFileSync("tmp/other.flac")),
    piano: zlib.deflateRawSync(fs.readFileSync("tmp/piano.flac")),
    vocals: zlib.deflateRawSync(fs.readFileSync("tmp/vocals.flac")),
}

let magic = Buffer.from("00ffff4f00000100", "hex");
let metadata = Buffer.from(zlib.deflateRawSync(JSON.stringify({
    version: "1.0",
    id: crypto.randomBytes(16).toString("base64").replace(/[^a-zA-Z\d]/g, "").toUpperCase().substring(0, 10),
    stems: {
        bass: [512, files.bass.length],
        drums: [512 + files.bass.length, files.drums.length],
        hpf: [512 + files.bass.length + files.drums.length, files.hpf.length],
        other: [512 + files.bass.length + files.drums.length + files.hpf.length, files.other.length],
        piano: [512 + files.bass.length + files.drums.length + files.hpf.length + files.other.length, files.piano.length],
        vocals: [512 + files.bass.length + files.drums.length + files.hpf.length + files.other.length + files.piano.length, files.vocals.length],
    }
})));
let padding = Buffer.from("00".repeat(504 - metadata.length), "hex");

let header = Buffer.concat([magic, metadata, padding]);
if (header.length !== 512) {
    console.log("Error: Invalid header length.");
    return;
}

let file = Buffer.concat([header, files.bass, files.drums, files.hpf, files.other, files.piano, files.vocals]);

console.log("Writing Stella file...");
fs.writeFileSync("tmp/export.stella", file);

console.log("Adding to server...");
fs.copyFileSync("tmp/export.stella", "/opt/mist/jpeg/" + process.argv[2] + ".stella");
if (!fs.existsSync("../assets/content/" + process.argv[2] + ".stella")) fs.symlinkSync("/opt/mist/jpeg/" + process.argv[2] + ".stella", "../assets/content/" + process.argv[2] + ".stella");
fs.rmSync("tmp", { recursive: true });