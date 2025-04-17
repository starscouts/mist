package dev.equestria.mist

import android.Manifest
import android.annotation.SuppressLint
import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.graphics.BitmapFactory
import android.support.v4.media.MediaMetadataCompat
import android.support.v4.media.session.MediaSessionCompat
import android.support.v4.media.session.PlaybackStateCompat
import android.util.Log
import android.view.View
import android.view.Window
import android.webkit.JavascriptInterface
import androidx.activity.ComponentActivity
import androidx.core.app.ActivityCompat
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import androidx.core.view.WindowCompat
import androidx.media.app.NotificationCompat.MediaStyle
import java.io.IOException
import java.net.URL
import android.graphics.Bitmap
import android.webkit.WebView


fun convertPixelsToDp(context: Context, pixels: Float): Float {
    val screenPixelDensity = context.resources.displayMetrics.density
    val dpValue = pixels / screenPixelDensity
    return dpValue
}

@SuppressLint("InternalInsetResource", "DiscouragedApi")
fun getStatusBarHeight(activity: ComponentActivity): Float {
    val resourceId = activity.resources.getIdentifier("status_bar_height", "dimen", "android")
    return if (resourceId > 0) {
        val statusBarHeight = activity.resources.getDimensionPixelSize(resourceId)
        convertPixelsToDp(activity.applicationContext, statusBarHeight.toFloat())
    } else {
        0f
    }
}

@SuppressLint("InternalInsetResource", "DiscouragedApi")
fun getNavigationBarHeight(activity: ComponentActivity): Float {
    val resourceId = activity.resources.getIdentifier("navigation_bar_height", "dimen", "android")
    return if (resourceId > 0) {
        val statusBarHeight = activity.resources.getDimensionPixelSize(resourceId)
        convertPixelsToDp(activity.applicationContext, statusBarHeight.toFloat())
    } else {
        0f
    }
}

class JavaScriptExtensions(originalActivity: MainActivity, private val window: Window, private val view: WebView, private val intent: Intent) {
    private val activity: MainActivity = originalActivity
    private var initialStatusBar: Boolean = WindowCompat.getInsetsController(window, view).isAppearanceLightStatusBars
    private val session: MediaSessionCompat = MediaSessionCompat(activity.applicationContext, "Player")
    private val notificationBuilder: NotificationCompat.Builder = NotificationCompat.Builder(activity.applicationContext, "main")
        .setSmallIcon(R.drawable.ic_stat_player)
        .setVisibility(NotificationCompat.VISIBILITY_PUBLIC)
        .setContentTitle("Mist")
    private var mAlbumArt: Bitmap? = null

    @JavascriptInterface
    fun removeNotification() {
        with (NotificationManagerCompat.from(activity.applicationContext)) {
            cancel(1)
        }
    }

    @JavascriptInterface
    fun removeService() {
        activity.stopService(intent)
    }

    @JavascriptInterface
    fun updateNotificationAlbumArt(albumArt: String) {
        activity.startForegroundService(intent)

        try {
            val url = URL(albumArt)
            val image = BitmapFactory.decodeStream(url.openConnection().getInputStream())
            mAlbumArt = image
        } catch (e: IOException) {
            Log.e("NotificationAlbumArt", "Failed to fetch album art", e)
        }
    }

    @JavascriptInterface
    fun quitApp() {
        activity.finish()
    }

    @JavascriptInterface
    fun setNotificationData(title: String, artist: String, album: String, position: Long, duration: Long, playing: Boolean, buffering: Boolean) {
        val playbackStateBuilder = PlaybackStateCompat.Builder()
        val style = MediaStyle()

        val state = if (buffering) {
            PlaybackStateCompat.STATE_BUFFERING
        } else if (playing) {
            PlaybackStateCompat.STATE_PLAYING
        } else {
            PlaybackStateCompat.STATE_PAUSED
        }
        val playbackSpeed = 1f
        playbackStateBuilder.setState(state, position, playbackSpeed)

        playbackStateBuilder.setActions(PlaybackStateCompat.ACTION_PLAY_PAUSE
                or PlaybackStateCompat.ACTION_SKIP_TO_PREVIOUS
                or PlaybackStateCompat.ACTION_SKIP_TO_NEXT
                or PlaybackStateCompat.ACTION_SEEK_TO
                or PlaybackStateCompat.ACTION_STOP
        )

        val builder = MediaMetadataCompat.Builder()

        builder.putString(MediaMetadataCompat.METADATA_KEY_TITLE, title)
        builder.putString(MediaMetadataCompat.METADATA_KEY_ARTIST, artist)
        builder.putString(MediaMetadataCompat.METADATA_KEY_ALBUM, album)
        builder.putBitmap(MediaMetadataCompat.METADATA_KEY_ALBUM_ART, mAlbumArt)
        builder.putBitmap(MediaMetadataCompat.METADATA_KEY_ART, mAlbumArt)
        builder.putLong(MediaMetadataCompat.METADATA_KEY_DURATION, duration)

        val callback = object: MediaSessionCompat.Callback() {
            override fun onPlay() {
                view.post { view.evaluateJavascript("window.playPause();", null) }
            }

            override fun onPause() {
                view.post { view.evaluateJavascript("window.playPause();", null) }
            }

            override fun onStop() {
                view.post { view.evaluateJavascript("window.stop();", null) }
            }

            override fun onSkipToPrevious() {
                view.post { view.evaluateJavascript("window.previous();", null) }
            }

            override fun onSkipToNext() {
                view.post { view.evaluateJavascript("window.next();", null) }
            }

            override fun onSeekTo(pos: Long) {
                view.post { view.evaluateJavascript("window.seekTo($pos / 1000);", null) }
            }
        }

        session.setCallback(callback)
        session.setMetadata(builder.build())
        session.setPlaybackState(playbackStateBuilder.build())

        style.setMediaSession(session.sessionToken)

        notificationBuilder.setContentTitle(title)
        notificationBuilder.setContentText("$artist - $album")
        notificationBuilder.setStyle(style)

        val notification = notificationBuilder.build()

        with (NotificationManagerCompat.from(activity.applicationContext)) {
            if (ActivityCompat.checkSelfPermission(
                    activity.applicationContext,
                    Manifest.permission.POST_NOTIFICATIONS
                ) != PackageManager.PERMISSION_GRANTED
            ) {
                return
            }
            notify(1, notification)
        }
    }

    @JavascriptInterface
    fun getNavigationBarHeight(): Float {
        return getNavigationBarHeight(activity)
    }

    @JavascriptInterface
    fun getStatusBarHeight(): Float {
        return getStatusBarHeight(activity)
    }

    @JavascriptInterface
    fun setStatusBarTheme(dark: Boolean) {
        if (!initialStatusBar) return
        WindowCompat.getInsetsController(window, view).isAppearanceLightStatusBars = dark
    }
}

