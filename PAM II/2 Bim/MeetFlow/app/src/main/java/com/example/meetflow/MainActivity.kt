package com.example.meetflow

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.activities.MenuActivity
import com.example.meetflow.database.daos.ReuniaoDao
import com.example.meetflow.database.daos.UserDao
import com.example.meetflow.repositories.ReuniaoRepository
import com.example.meetflow.repositories.UserRepository

class
MainActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(R.layout.activity_main)

        val btnEntrar = findViewById<Button>(R.id.btnEntrar)

        val userRepository = UserRepository(UserDao(this))
        val reuniaoRepository = ReuniaoRepository(ReuniaoDao(this))
        reuniaoRepository.limparRelacionamentosOrfaos()

        btnEntrar.setOnClickListener {

            val intent = Intent(
                this,
                MenuActivity::class.java
            )

            startActivity(intent)
        }
    }
}