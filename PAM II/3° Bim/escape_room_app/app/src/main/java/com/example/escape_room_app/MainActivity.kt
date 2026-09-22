package com.example.escape_room_app

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import com.example.escape_room_app.ui.screens.EscapeRoomApp
import com.example.escape_room_app.ui.theme.Escape_room_appTheme
import com.google.firebase.firestore.FirebaseFirestore
import com.google.firebase.firestore.FirebaseFirestoreSettings

class MainActivity : ComponentActivity() {  
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        try {
            FirebaseFirestore.getInstance().firestoreSettings =
                FirebaseFirestoreSettings.Builder()
                    .setPersistenceEnabled(true)
                    .build()
        } catch (_: Exception) {
        }
        setContent {
            Escape_room_appTheme {
                EscapeRoomApp()
            }
        }
    }
}
