document.onkeydown = (e) => {
    if ((e.metaKey || e.ctrlKey) && e.code === "Comma") {
        if (window.parent.currentSong === null) return;
        e.preventDefault();
        window.parent.stop();
        return false;
    }

    if ((e.metaKey || e.ctrlKey) && e.code === "ArrowRight") {
        if (window.parent.currentSong === null) return;
        e.preventDefault();
        window.parent.next();
        return false;
    }

    if ((e.metaKey || e.ctrlKey) && e.code === "ArrowLeft") {
        if (window.parent.currentSong === null) return;
        e.preventDefault();
        window.parent.previous();
        return false;
    }

    if (e.code === "Space" && e.target.tagName !== "INPUT") {
        if (window.parent.currentSong === null) return;
        e.preventDefault();
        window.parent.playPause();
        return false;
    }
}