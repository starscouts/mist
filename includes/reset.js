console.log("Don't use this script.");
return;

const fs = require('fs');
fs.rmSync('../assets/content', { recursive: true });
fs.rmSync('./users', { recursive: true });
fs.symlinkSync('/pool', '../assets/content');
//fs.mkdirSync('./users');
fs.mkdirSync('../assets/content');
fs.mkdirSync('../assets/content/_');
fs.writeFileSync('../assets/content/songs.json', '{}');
fs.writeFileSync('../assets/content/albums.json', '{}');
