let style = document.createElement("style");

if (window.MistAndroid) {
    style.innerHTML =
    `:root {
        --android-navigation-bar: ${window.MistAndroid.getNavigationBarHeight()}px;
        --android-status-bar: ${window.MistAndroid.getStatusBarHeight()}px;
    }`;
} else {
    style.innerHTML =
    `:root {
        --android-navigation-bar: 0px;
        --android-status-bar: 0px;
    }`;
}

document.head.append(style);

if (navigator.userAgent.includes("MistNative/darwin")) {
    if (document.getElementById("native-css")) document.getElementById("native-css").disabled = false;
    if (document.body) document.body.classList.remove("crossplatform");
}