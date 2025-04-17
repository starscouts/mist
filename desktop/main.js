const { app, BrowserWindow, shell, ipcMain, session, Notification, nativeImage, Menu, nativeTheme } = require('electron');
const path = require('node:path');
const http = require('http');
const {Client} = require("@xhayper/discord-rpc");

global.rpcStatus = null;
global.rpcHandleError = false;

setInterval(async () => {
    if (mainWindow && !mainWindow.isDestroyed() && client && client.isConnected) {
        global.rpcStatus = await mainWindow.webContents.executeJavaScript("window.discordRichPresenceData");

        if (rpcStatus === null) {
            await client.user?.clearActivity();
        } else {
            await client.user?.setActivity(rpcStatus);
        }
    }
}, 5000);

function loadRPC() {
    try {
        const { Client } = require('@xhayper/discord-rpc');

        global.client = new Client({
            clientId: "1167169806127071333"
        });

        client.on("ready", () => {
            global.rpcHandleError = true;
        });

        client.login();
    } catch (e) {
        console.error(e);
        if (global.rpcHandleError) loadRPC();
    }
}

loadRPC();

function updateMenu(mainWindow) {
    let template = [
        {
            label: "File",
            submenu: process.platform === "darwin" ? [
                {
                    role: "close",
                    label: "Close"
                }
            ] : [
                {
                    role: "quit"
                }
            ]
        },
        {
            role: "editMenu"
        },
        {
            label: "View",
            submenu: [
                {
                    role: "reload"
                },
                {
                    role: "forceReload"
                },
                {
                    role: "toggleDevTools"
                },
                {
                    type: "separator"
                },
                {
                    role: "togglefullscreen"
                }
            ]
        },
        {
            label: "Controls",
            submenu: [
                {
                    label: "Play/Pause",
                    click: () => {
                        mainWindow.webContents.executeJavaScript("window.playPause();");
                    }
                },
                {
                    label: "Stop",
                    accelerator: "CmdOrCtrl+.",
                    click: () => {
                        mainWindow.webContents.executeJavaScript("window.stop();");
                    }
                },
                {
                    label: "Next Track",
                    accelerator: "CmdOrCtrl+Right",
                    click: () => {
                        mainWindow.webContents.executeJavaScript("window.next();");
                    }
                },
                {
                    label: "Previous Track",
                    accelerator: "CmdOrCtrl+Left",
                    click: () => {
                        mainWindow.webContents.executeJavaScript("window.previous();");
                    }
                }
            ]
        },
        {
            label: "Account",
            submenu: [
                {
                    label: global.userInfo ? global.userInfo.name : "-",
                    enabled: false
                },
                {
                    label: global.userInfo ? global.userInfo.profile.email.email : "-",
                    enabled: false
                },
                {
                    label: "Account Settings...",
                    click: () => {
                        mainWindow.webContents.executeJavaScript('window.document.getElementById("navigation").contentDocument.getElementById("account").click();');
                    }
                },
                {
                    label: "Sign Out",
                    click: () => {
                        session.defaultSession.cookies.set({
                            url: "https://mist.equestria.horse",
                            name: "WAVY_SESSION_TOKEN",
                            value: "",
                            httpOnly: true,
                            secure: true,
                            expirationDate: 0
                        }).then(() => {
                            mainWindow.close();
                        });
                    }
                }
            ]
        },
        {
            role: "windowMenu"
        },
        {
            label: "Help",
            role: "help",
            submenu: process.platform === "darwin" ? [
                {
                    label: "Source Code",
                    onclick: () => {
                        shell.openExternal("https://source.equestria.dev/equestria.dev/mist");
                    }
                }
            ] : [
                {
                    label: "Source Code",
                    onclick: () => {
                        shell.openExternal("https://source.equestria.dev/equestria.dev/mist");
                    }
                },
                {
                    type: "separator"
                },
                {
                    role: "about"
                }
            ]
        }
    ];

    if (process.platform === "darwin") template.unshift({
        label: "Mist",
        submenu: [
            {
                role: "about",
            },
            {
                type: "separator"
            },
            {
                label: "Settings...",
                accelerator: "CmdOrCtrl+,",
                click: () => {
                    mainWindow.webContents.executeJavaScript("window.openUI('settings');");
                }
            },
            {
                type: "separator"
            },
            {
                role: "services"
            },
            {
                type: "separator"
            },
            {
                role: "hide"
            },
            {
                role: "hideOthers"
            },
            {
                role: "unhide"
            },
            {
                type: "separator"
            },
            {
                role: "quit"
            }
        ],
    });
    let menu = Menu.buildFromTemplate(template);

    mainWindow.setMenu(menu);
    Menu.setApplicationMenu(menu);
}

function getCopyrightYear() {
    if (new Date().getFullYear() === 2023) {
        return "2023";
    } else {
        return "2023-" + new Date().getFullYear();
    }
}

app.setAsDefaultProtocolClient("mist");
app.setAboutPanelOptions({
    applicationName: "Mist",
    applicationVersion: "",
    version: "",
    copyright: "Copyright © " + getCopyrightYear() + " Equestria.dev Developers",
    website: "https://mist.equestria.horse/app/"
});

if (app.getName() !== "Electron") {
    const gotTheLock = app.requestSingleInstanceLock();

    if (!gotTheLock) {
        app.quit();
    }
}

let loggedIn = false;
let token = null;

const createWindow = () => {
    global.mainWindow = new BrowserWindow({
        width: 1280,
        height: 720,
        minWidth: 864,
        minHeight: 550,
        frame: process.platform === "darwin",
        titleBarStyle: "hidden",
        titleBarOverlay: {
            color: nativeTheme.shouldUseDarkColors ? "#222222" : "#ffffff",
            symbolColor: nativeTheme.shouldUseDarkColors ? "#ffffff" : "#222222",
            height: 64
        },
        autoHideMenuBar: true,
        trafficLightPosition: {
            x: 20,
            y: 20
        },
        vibrancy: "under-window",
        transparent: process.platform === "darwin",
        title: "Mist",
        type: "textured",
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            scrollBounce: true,
            enableWebSQL: false
        }
    });

    app.on('second-instance', () => {
        if (mainWindow) {
            if (mainWindow.isMinimized()) mainWindow.restore()
            mainWindow.focus()
        }
    });

    updateMenu(mainWindow);

    const server = http.createServer((req, res) => {
        let url = new URL("http://localhost" + req.url);
        if (url.searchParams.get("token")) {
            token = url.searchParams.get("token");
            loggedIn = true;

            res.statusCode = 301;
            res.setHeader('Location', '/');
            res.end();

            session.defaultSession.cookies.set({
                url: "https://mist.equestria.horse",
                name: "WAVY_SESSION_TOKEN",
                value: token,
                httpOnly: true,
                secure: true,
                expirationDate: Math.round((new Date().getTime() + 86400000 * 365) / 1000)
            }).then(() => {
                mainWindow.loadURL("https://mist.equestria.horse/app/");
                mainWindow.focus();
            });
        } else {
            if (loggedIn) {
                res.statusCode = 200;
                res.setHeader('Content-Type', 'text/plain');
                res.end('Log in complete. Please go back to the Mist desktop application to continue.');
                server.close();
            } else {
                res.statusCode = 500;
                res.setHeader('Content-Type', 'text/plain');
                res.end('500. A token was expected but no such token was sent.');
            }
        }
    });

    mainWindow.webContents.on('did-navigate', (event, url) => {
        if (url.includes("/oauth/")) {
            server.listen(12981, "127.0.0.1", () => {
                console.log(`Server running at http://127.0.0.1:12981`);
            });
        }
    });

    mainWindow.webContents.setUserAgent(mainWindow.webContents.getUserAgent() + " MistNative/" + process.platform);
    mainWindow.loadURL("https://mist.equestria.horse/app/");
    mainWindow.webContents.setWindowOpenHandler((details) => {
        shell.openExternal(details.url);
        return { action: "deny" };
    });
}

ipcMain.handle('auth', () => {
    shell.openExternal("https://mist.equestria.horse/oauth/native/");
});

ipcMain.handle('about', () => {
    app.showAboutPanel();
});

ipcMain.handle('userInfo', (e, userInfo) => {
    global.userInfo = JSON.parse(userInfo);
    updateMenu(mainWindow);
});

ipcMain.handle('notification', (e, song, img) => {
    let notification = new Notification({
        title: song.title,
        body: song.artist + " — " + song.album,
        silent: true,
        icon: nativeImage.createFromDataURL(img)
    });

    notification.show();
});

ipcMain.handle('version', (e, v, b) => {
    app.setAboutPanelOptions({
        applicationName: "Mist",
        applicationVersion: v ?? "",
        version: b ?? "",
        copyright: "Copyright © " + getCopyrightYear() + " Equestria.dev Developers, Released under the MIT license",
        website: "https://mist.equestria.horse/app/"
    });
});

app.whenReady().then(() => {
    createWindow();

    app.userAgentFallback = app.userAgentFallback + " MistNative/" + process.platform;

    app.on('activate', () => {
        if (BrowserWindow.getAllWindows().length === 0) createWindow();
    });
});

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') app.quit();
});