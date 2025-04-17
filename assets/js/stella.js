class Stella {
    arrayBuffer;
    metadata;
    stems;

    constructor(ab) {
        this.arrayBuffer = ab;
        this.metadata = JSON.parse(new TextDecoder("utf-8").decode(pako.inflateRaw(ab.slice(8, 512))).trim());
        this.stems = {};
        this.urls = {};

        for (let stem of Object.keys(this.metadata.stems)) {
            this.stems[stem] = pako.inflateRaw(ab.slice(this.metadata.stems[stem][0], this.metadata.stems[stem][0] + this.metadata.stems[stem][1]));
            this.urls[stem] = URL.createObjectURL(new Blob([this.stems[stem]], { type: "audio/flac" }));
        }
    }

    destroy() {
        for (let url of Object.values(this.urls)) {
            URL.revokeObjectURL(url);
        }

        this.urls = null;
        this.stems = null;
        this.arrayBuffer = null;
    }

    static async build(url) {
        let buffer = await (await fetch(url)).arrayBuffer();
        return new Stella(buffer);
    }
}