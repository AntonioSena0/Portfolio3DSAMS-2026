package com.example.meetflow.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.R
import com.example.meetflow.database.daos.ReuniaoDao
import com.example.meetflow.database.daos.UserDao
import com.example.meetflow.repositories.ReuniaoRepository
import com.example.meetflow.repositories.UserRepository
import com.example.meetflow.services.ReuniaoService
import com.example.meetflow.services.UserService
import com.example.meetflow.session.SessionManager

class MenuActivity : AppCompatActivity() {

    private lateinit var btnCadastrarUsuario: Button

    private lateinit var btnListarUsuarios: Button

    private lateinit var btnCadastrarReuniao: Button

    private lateinit var btnListarReunioes: Button

    private lateinit var btnSair: Button

    private lateinit var txtTotalUsuarios:
            TextView

    private lateinit var txtTotalReunioes:
            TextView

    private lateinit var userService: UserService
private lateinit var reuniaoService: ReuniaoService

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(R.layout.activity_menu)

        inicializarComponentes()

        configurarClicks()

        reuniaoService = ReuniaoService(ReuniaoRepository(ReuniaoDao(applicationContext)))
        userService = UserService(UserRepository(UserDao(applicationContext)))

        carregarEstatisticas()

    }

    override fun onResume() {

        super.onResume()

        carregarEstatisticas()
    }

    private fun inicializarComponentes() {

        btnCadastrarReuniao =
            findViewById(R.id.btnCadastrarReuniao)

        btnListarReunioes =
            findViewById(R.id.btnListarReunioes)

        txtTotalUsuarios =
            findViewById(R.id.txtTotalUsuarios)

        txtTotalReunioes =
            findViewById(R.id.txtTotalReunioes)

        btnSair =
            findViewById(R.id.btnSair)

    }

    private fun configurarClicks() {

        btnCadastrarReuniao.setOnClickListener {

            abrirCadastroReuniao()

        }

        btnListarReunioes.setOnClickListener {

            abrirListaReunioes()

        }

        btnSair.setOnClickListener {
            val sessionManager = SessionManager(applicationContext)
            sessionManager.logout()

            val intent = Intent(this, LoginActivity::class.java)
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_NEW_TASK)
            startActivity(intent)
            finish()
        }

    }

    private fun carregarEstatisticas() {

        val totalUsuarios =
            userService.contarUsuarios()

        val totalReunioes =
            reuniaoService.contarReunioes()

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