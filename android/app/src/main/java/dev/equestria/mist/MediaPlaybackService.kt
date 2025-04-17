package dev.equestria.mist

import android.Manifest
import android.app.ForegroundServiceStartNotAllowedException
import android.app.Notification
import android.app.Service
import android.content.Intent
import android.content.pm.PackageManager
import android.os.Build
import android.os.IBinder
import android.support.v4.media.MediaMetadataCompat
import android.support.v4.media.session.MediaSessionCompat
import android.support.v4.media.session.PlaybackStateCompat
import androidx.core.app.ActivityCompat
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import androidx.core.app.ServiceCompat

class MediaPlaybackService: Service() {
    private lateinit var mNotification: Notification

    fun setNotification(notification: Notification) {
        mNotification = notification
    }

    override fun onStartCommand(intent: Intent?, flags: Int, startId: Int): Int {
        val session = MediaSessionCompat(applicationContext, "Player")
        val playbackStateBuilder = PlaybackStateCompat.Builder()
        val style = androidx.media.app.NotificationCompat.MediaStyle()

        val notificationBuilder = NotificationCompat.Builder(applicationContext, "main")
            .setSmallIcon(R.drawable.ic_stat_player)
            .setVisibility(NotificationCompat.VISIBILITY_PUBLIC)
            .setContentTitle("Mist")

        playbackStateBuilder.setState(PlaybackStateCompat.STATE_STOPPED, 0L, 1f)

        session.setPlaybackState(playbackStateBuilder.build())
        style.setMediaSession(session.sessionToken)
        notificationBuilder.setStyle(style)

        val notification = notificationBuilder.build()
        startForeground(1, notification)
        return START_STICKY
    }

    override fun onBind(p0: Intent?): IBinder? {
        return null
    }

    override fun onTaskRemoved(rootIntent: Intent?) {
        super.onTaskRemoved(rootIntent)
        with (NotificationManagerCompat.from(applicationContext)) {
            cancel(1)
        }
        android.os.Process.killProcess(android.os.Process.myPid())
        stopSelf()
    }
}