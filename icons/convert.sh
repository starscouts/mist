#!/bin/bash

f="maskable"
sips -z 384 384 $f.png --out ${f}_384x384.png
sips -z 256 256 $f.png --out ${f}_256x256.png
sips -z 192 192 $f.png --out ${f}_192x192.png
sips -z 152 152 $f.png --out ${f}_152x152.png
sips -z 144 144 $f.png --out ${f}_144x144.png
sips -z 128 128 $f.png --out ${f}_128x128.png
sips -z 96 96 $f.png --out ${f}_96x96.png
sips -z 72 72 $f.png --out ${f}_72x72.png
sips -z 64 64 $f.png --out ${f}_64x64.png

f="normal"
sips -z 384 384 $f.png --out ${f}_384x384.png
sips -z 256 256 $f.png --out ${f}_256x256.png
sips -z 192 192 $f.png --out ${f}_192x192.png
sips -z 152 152 $f.png --out ${f}_152x152.png
sips -z 144 144 $f.png --out ${f}_144x144.png
sips -z 128 128 $f.png --out ${f}_128x128.png
sips -z 96 96 $f.png --out ${f}_96x96.png
sips -z 72 72 $f.png --out ${f}_72x72.png
sips -z 64 64 $f.png --out ${f}_64x64.png
