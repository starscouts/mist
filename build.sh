#!/bin/bash
cd desktop
npx electron-packager . Mist --asar --overwrite --platform=darwin --arch=x64 --icon=../assets/logo.icns --out=../build
npx electron-packager . Mist --asar --overwrite --platform=darwin --arch=arm64 --icon=../assets/logo.icns --out=../build
#npx electron-packager . Mist --asar --overwrite --platform=linux --arch=x64 --icon=../assets/logo-display.png --out=../build
#npx electron-packager . Mist --asar --overwrite --platform=linux --arch=arm64 --icon=../assets/logo-display.png --out=../build
npx electron-packager . Mist --asar --overwrite --platform=win32 --arch=x64 --icon=../assets/logo-display.ico --out=../build
#npx electron-packager . Mist --asar --overwrite --platform=win32 --arch=arm64 --icon=../assets/logo-display.ico --out=../build