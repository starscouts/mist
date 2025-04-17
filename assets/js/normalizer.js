window.currentNormalizationContext = null;
window.currentNormalizationContext2 = null;
window.currentNormalizationContext3 = null;

function normalizeAudio(ab, gainBoost, stella) {
    ab = ab.slice(0);

    return new Promise((res) => {
        let currentNormalizationProfile = currentNormalizationContext.createGain();

        if (localStorage.getItem("noamp") !== "true" && !stella) {
            currentNormalizationProfile.gain.value = (1.0 + gainBoost) / 10.0 + 0.5;
        } else {
            currentNormalizationProfile.gain.value = (1.0 + gainBoost) / 10.0;
        }

        if (localStorage.getItem("normalize") === "false") res(currentNormalizationProfile);

        currentNormalizationContext.decodeAudioData(ab).then(function(decodedData) {
            let decodedBuffer = decodedData.getChannelData(0);
            let sliceLen = Math.floor(decodedData.sampleRate * 0.05);
            let averages = [];
            let sum = 0.0;

            for (let i = 0; i < decodedBuffer.length; i++) {
                sum += decodedBuffer[i] ** 2;
                if (i % sliceLen === 0) {
                    sum = Math.sqrt(sum / sliceLen);
                    averages.push(sum);
                    sum = 0;
                }
            }

            averages.sort(function(a, b) { return a - b; });
            let a = averages[Math.floor(averages.length * 0.95)];

            let gain = (1.0 + gainBoost) / a;
            gain = gain / 10.0;

            console.log("Calculated gain:", gain);

            if (localStorage.getItem("noamp") !== "true" && !stella) {
                currentNormalizationProfile.gain.value = gain + 0.5;
            } else {
                currentNormalizationProfile.gain.value = gain;
            }

            res(currentNormalizationProfile);
        });
    });
}