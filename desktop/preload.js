const { contextBridge, ipcRenderer } = require('electron')

contextBridge.exposeInMainWorld('MistNative', {
    auth: () => ipcRenderer.invoke('auth'),
    version: (v, b) => ipcRenderer.invoke("version", v, b),
    about: () => ipcRenderer.invoke('about'),
    notification: (song, img) => ipcRenderer.invoke('notification', song, img),
    userInfo: (ui) => ipcRenderer.invoke('userInfo', ui)
})