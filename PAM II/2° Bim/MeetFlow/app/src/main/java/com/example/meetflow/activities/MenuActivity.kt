package com.example.meetflow.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.R
import com.example.meetflow.database.DatabaseHelper

class MenuActivity : AppCompatActivity() {

    private lateinit var btnCadastrarUsuario: Button

    private lateinit var btnListarUsuarios: Button

    private lateinit var btnCadastrarReuniao: Button

    private lateinit var btnListarReunioes: Button

    private lateinit var txtTotalUsuarios:
            TextView

    private lateinit var txtTotalReunioes:
            TextView

    private lateinit var db:
            DatabaseHelper

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(R.layout.activity_menu)

        inicializarComponentes()

        configurarClicks()

        db = DatabaseHelper(this)

        carregarEstatisticas()

    }

    override fun onResume() {

        super.onResume()

        carregarEstatisticas()
    }

    private fun inicializarComponentes() {

        btnCadastrarUsuario =
            findViewById(R.id.btnCadastrarUsuario)

        btnListarUsuarios =
            findViewById(R.id.btnListarUsuarios)

        btnCadastrarReuniao =
            findViewById(R.id.btnCadastrarReuniao)

        btnListarReunioes =
            findViewById(R.id.btnListarReunioes)

        txtTotalUsuarios =
            findViewById(R.id.txtTotalUsuarios)

        txtTotalReunioes =
            findViewById(R.id.txtTotalReunioes)

    }

    private fun configurarClicks() {

        btnCadastrarUsuario.setOnClickListener {

            abrirCadastroUsuario()
        }

        btnListarUsuarios.setOnClickListener {

            abrirListaUsuarios()
        }

        btnCadastrarReuniao.setOnClickListener {

            abrirCadastroReuniao()

        }

        btnListarReunioes.setOnClickListener {

            abrirListaReunioes()

        }

    }

    private fun carregarEstatisticas() {

        val totalUsuarios =
            db.contarUsuarios()

        val totalReunioes =
            db.contarReunioes()

        txtTotalUsuarios.text =
            totalUsuarios.toString()

        txtTotalReunioes.text =
            totalReunioes.toString()
    }

    private fun abrirCadastroUsuario() {

        val intent = Intent(
            this,
            CadastrarUsuarioActivity::class.java
        )

        startActivity(intent)
    }

    private fun abrirListaUsuarios() {

        val intent = Intent(
            this,
            ListaUsuariosActivity::class.java
        )

        startActivity(intent)
    }

    private fun abrirCadastroReuniao() {

        val intent = Intent(
            this,
            CadastrarReuniaoActivity::class.java
        )

        startActivity(intent)
    }

    private fun abrirListaReunioes() {

        val intent = Intent(
            this,
            ListaReunioesActivity::class.java
        )

        startActivity(intent)
    }

}