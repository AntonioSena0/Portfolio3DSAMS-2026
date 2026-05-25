package com.example.meetflow.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.R
import com.example.meetflow.database.DatabaseHelper
import com.google.android.material.appbar.MaterialToolbar

class CadastrarUsuarioActivity : AppCompatActivity() {

    private var usuarioId: Int = -1

    private lateinit var edtNome: EditText
    private lateinit var edtEmail: EditText
    private lateinit var edtTelefone: EditText
    private lateinit var btnSalvar: Button

    private lateinit var toolbar:
            MaterialToolbar

    private lateinit var db: DatabaseHelper

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(R.layout.activity_cadastrar_usuario)

        inicializarComponentes()

        db = DatabaseHelper(this)
        verificarModoEdicao()

        btnSalvar.setOnClickListener {

            salvarUsuario()
        }

        setSupportActionBar(toolbar)

        supportActionBar?.title =
            "MeetFlow"

        supportActionBar?.setDisplayHomeAsUpEnabled(
            true
        )

    }

    private fun inicializarComponentes() {

        edtNome = findViewById(R.id.edtNome)

        edtEmail = findViewById(R.id.edtEmail)

        edtTelefone = findViewById(R.id.edtTelefone)

        btnSalvar = findViewById(R.id.btnSalvar)

        toolbar =
            findViewById(R.id.toolbar)

    }

    override fun onSupportNavigateUp(): Boolean {

        finish()

        return true
    }

    private fun verificarModoEdicao() {

        usuarioId =
            intent.getIntExtra(
                "usuario_id",
                -1
            )

        if (usuarioId != -1) {

            carregarDadosUsuario()
        }
    }

    private fun carregarDadosUsuario() {

        val usuario =
            db.buscarUsuarioPorId(usuarioId)

        usuario?.let {

            edtNome.setText(it.nome)

            edtEmail.setText(it.email)

            edtTelefone.setText(it.telefone)
        }
    }

    private fun salvarUsuario() {

        val nome = edtNome.text.toString()

        val email = edtEmail.text.toString()

        val telefone = edtTelefone.text.toString()

        if (
            nome.isEmpty() ||
            email.isEmpty() ||
            telefone.isEmpty()
        ) {

            Toast.makeText(
                this,
                "Preencha todos os campos",
                Toast.LENGTH_SHORT
            ).show()

            return
        }

        val sucesso = if (usuarioId == -1) {

            db.inserirUsuario(
                nome,
                email,
                telefone
            )

        } else {

            db.atualizarUsuario(
                usuarioId,
                nome,
                email,
                telefone
            )
        }

        if (sucesso) {


            Toast.makeText(
                this,
                "Usuário salvo com sucesso",
                Toast.LENGTH_SHORT
            ).show()

            val intent = Intent(
                this,
                MenuActivity::class.java
            )

            startActivity(intent)

        } else {

            Toast.makeText(
                this,
                "Erro ao salvar usuário",
                Toast.LENGTH_SHORT
            ).show()
        }
    }

}