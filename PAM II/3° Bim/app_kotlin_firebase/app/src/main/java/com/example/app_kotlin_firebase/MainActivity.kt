package com.example.app_kotlin_firebase

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import com.example.app_kotlin_firebase.ui.PovosOriginariosApp
import com.example.app_kotlin_firebase.ui.theme.PovosOriginariosTheme

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            PovosOriginariosTheme {
                PovosOriginariosApp()
            }
        }
    }
}
