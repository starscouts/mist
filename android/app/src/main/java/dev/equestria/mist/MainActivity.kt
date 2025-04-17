package dev.equestria.mist

import android.Manifest
import android.annotation.SuppressLint
import android.app.NotificationChannel
import android.app.NotificationManager
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.os.Build
import android.os.Bundle
import android.os.Message
import android.util.Log
import android.view.ViewGroup
import android.webkit.WebChromeClient
import android.webkit.WebSettings
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.activity.ComponentActivity
import androidx.activity.addCallback
import androidx.activity.compose.setContent
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.wrapContentHeight
import androidx.compose.foundation.layout.wrapContentWidth
import androidx.compose.material3.AlertDialog
import androidx.compose.material3.AlertDialogDefaults
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.material3.TextButton
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.unit.dp
import androidx.compose.ui.viewinterop.AndroidView
import androidx.core.app.ActivityCompat
import androidx.core.view.WindowCompat
import com.android.volley.Request
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import dev.equestria.mist.ui.theme.MistTheme


class MainActivity : ComponentActivity() {
    private lateinit var intent: Intent
    lateinit var webview: WebView

    @OptIn(ExperimentalMaterial3Api::class)
    fun showError() {
        setContent {
            AlertDialog(
                onDismissRequest = {
                    finish()
                }
            ) {
                Surface(
                    modifier = Modifier
                        .wrapContentWidth()
                        .wrapContentHeight(),
                    shape = MaterialTheme.shapes.large,
                    tonalElevation = AlertDialogDefaults.TonalElevation
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Text(
                            text = "Unable to connect to Mist at this moment. Please try again later.",
                        )
                        Spacer(modifier = Modifier.height(24.dp))
                        TextButton(
                            onClick = {
                                finish()
                            },
                            modifier = Modifier.align(Alignment.End)
                        ) {
                            Text("Quit")
                        }
                    }
                }
            }
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        intent = Intent(this, MediaPlaybackService::class.java)

        super.onCreate(savedInstanceState)
        WindowCompat.setDecorFitsSystemWindows(window, false)

        val callback = onBackPressedDispatcher.addCallback(this) {
            webview.evaluateJavascript("window.back();", null)
        }

        if (ActivityCompat.checkSelfPermission(
                applicationContext,
                Manifest.permission.POST_NOTIFICATIONS
            ) != PackageManager.PERMISSION_GRANTED
        ) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
                ActivityCompat.requestPermissions(
                    this, arrayOf(Manifest.permission.POST_NOTIFICATIONS), 1
                )
            }
        }

        val channel = NotificationChannel("main", "Playback", NotificationManager.IMPORTANCE_LOW).apply {}
        val notificationManager: NotificationManager =
            getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
        notificationManager.createNotificationChannel(channel)

        setContent {
            Box() {
                MistTheme {}
            }
        }

        val volleyQueue = Volley.newRequestQueue(baseContext)

        val jsonObjectRequest = JsonObjectRequest(
            Request.Method.GET, "https://mist.equestria.horse/connectivitycheck.txt", null,

            { response ->
                Log.i("HTTPRequest", response.toString())

                if (response.getString("status") == "OK") {
                    setContent {
                        Box() {
                            MistTheme {
                                WebViewContainer(this@MainActivity, intent)
                            }
                        }
                    }

                    WebView.setWebContentsDebuggingEnabled(true)
                } else {
                    setContent {
                        Box() {
                            MistTheme {}
                        }
                    }
                    showError()
                }
            },

            { error ->
                setContent {
                    Box() {
                        MistTheme {}
                    }
                }
                showError()
                Log.e("HTTPRequest", "Request error: ${error.localizedMessage}")
            })

        volleyQueue.add(jsonObjectRequest)

        Log.d("StatusBarHeight", getStatusBarHeight(this).toString())
        Log.d("NavigationBarHeight", getNavigationBarHeight(this).toString())
    }

    @SuppressLint("SetJavaScriptEnabled")
    @Composable
    fun WebViewContainer(activity: MainActivity, intent: Intent) {
        val mUrl = "https://mist.equestria.horse/app/"

        AndroidView(factory = {
            WebView(it).apply {
                webview = this

                layoutParams = ViewGroup.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.MATCH_PARENT
                )

                clearCache(true)
                clearHistory()
                webViewClient = WebViewClient()

                settings.domStorageEnabled = true
                settings.javaScriptEnabled = true
                settings.safeBrowsingEnabled = false
                settings.mediaPlaybackRequiresUserGesture = false
                settings.userAgentString += " MistAndroid/" + BuildConfig.VERSION_NAME
                settings.cacheMode = WebSettings.LOAD_NO_CACHE

                settings.setSupportMultipleWindows(true)
                webChromeClient = object : WebChromeClient() {
                    override fun onCreateWindow(
                        view: WebView,
                        dialog: Boolean,
                        userGesture: Boolean,
                        resultMsg: Message
                    ): Boolean {
                        val result = view.hitTestResult
                        val data = result.extra
                        val context = view.context
                        val browserIntent = Intent(Intent.ACTION_VIEW, Uri.parse(data))
                        context.startActivity(browserIntent)
                        return false
                    }
                }

                addJavascriptInterface(JavaScriptExtensions(activity, activity.window, this, intent), "MistAndroid")
                loadUrl(mUrl)
            }
        })
    }
}