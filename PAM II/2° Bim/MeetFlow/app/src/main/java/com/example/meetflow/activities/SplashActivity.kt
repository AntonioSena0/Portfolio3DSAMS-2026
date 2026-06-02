package com.example.meetflow.activities

import android.content.Intent
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.R
import com.example.meetflow.session.SessionManager

class SplashActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_splash)

        val sessionManager = SessionManager(applicationContext)
        val delay = if (sessionManager.isLoggedIn()) 1500 else 2000

        Handler(Looper.getMainLooper()).postDelayed({
            val intent = if (sessionManager.isLoggedIn()) {
                Intent(this, MenuActivity::class.java)
            } else {
                Intent(this, LoginActivity::class.java)
            }
            startActivity(intent)
            finish()
        }, delay.toLong())
    }
}