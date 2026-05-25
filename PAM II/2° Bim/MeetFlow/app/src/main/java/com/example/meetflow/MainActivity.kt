package com.example.meetflow

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.activities.MenuActivity
import com.example.meetflow.database.DatabaseHelper

class MainActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(R.layout.activity_main)

        val btnEntrar = findViewById<Button>(R.id.btnEntrar)

        val db = DatabaseHelper(this)
        db.limparRelacionamentosOrfaos()

        btnEntrar.setOnClickListener {

            val intent = Intent(
                this,
                MenuActivity::class.java
            )

            startActivity(intent)
        }
    }
}