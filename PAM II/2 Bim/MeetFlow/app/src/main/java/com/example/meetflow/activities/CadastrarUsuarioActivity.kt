package com.example.meetflow.activities

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.R
import com.example.meetflow.database.daos.UserDao
import com.example.meetflow.repositories.UserRepository
import com.example.meetflow.validators.Validator
import com.google.android.material.appbar.MaterialToolbar

class CadastrarUsuarioActivity : AppCompatActivity() {

    private var usuarioId: Int = -1

    private lateinit var edtNome: EditText
    private lateinit var edtEmail: EditText
    private lateinit var edtTelefone: EditText
    private lateinit var edtSenha: EditText
    private lateinit var btnSalvar: Button
    private lateinit var btnJaTemConta: Button

    private lateinit var toolbar: MaterialToolbar

    private lateinit var userRepository: UserRepository

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_cadastrar_usuario)
        inicializarComponentes()
        userRepository = UserRepository(UserDao(applicationContext))
        verificarModoEdicao()
        btnSalvar.setOnClickListener { salvarUsuario() }
        setSupportActionBar(toolbar)
        supportActionBar?.title = "MeetFlow"
        supportActionBar?.setDisplayHomeAsUpEnabled(true)
    }

    private fun inicializarComponentes() {
        edtNome = findViewById(R.id.edtNome)
        edtEmail = findViewById(R.id.edtEmail)
        edtTelefone = findViewById(R.id.edtTelefone)
        edtSenha = findViewById(R.id.edtSenha)
        btnSalvar = findViewById(R.id.btnSalvar)
        btnJaTemConta = findViewById(R.id.btnJaTemConta)
        toolbar = findViewById(R.id.toolbar)
        btnJaTemConta.setOnClickListener { finish() }
    }

    override fun onSupportNavigateUp(): Boolean {
        finish()
        return true
    }

    private fun verificarModoEdicao() {
        usuarioId = intent.getIntExtra("usuario_id", -1)
        if (usuarioId != -1) {
            carregarDadosUsuario()
        }
    }

    private fun carregarDadosUsuario() {
        val usuario = userRepository.buscarUsuarioPorId(usuarioId)
        usuario?.let {
            edtNome.setText(it.nome)
            edtEmail.setText(it.email)
            edtTelefone.setText(it.telefone)
        }
    }

    private fun salvarUsuario() {
        val nome = edtNome.text.toString().trim()
        val email = edtEmail.text.toString().trim()
        val telefone = edtTelefone.text.toString().trim()
        val senha = edtSenha.text.toString().trim()

        if (!Validator.isValidRequiredField(nome) ||
            !Validator.isValidRequiredField(email) ||
            !Validator.isValidRequiredField(telefone) ||
            !Validator.isValidRequiredField(senha) ||
            !Validator.isValidEmail(email) ||
            !Validator.isValidPassword(senha) ||
            !Validator.isValidName(nome) ||
            !Validator.isValidPhone(telefone)) {
            Toast.makeText(this, "Preencha todos os campos corretamente", Toast.LENGTH_SHORT).show()
            return
        }

        val sucesso = if (usuarioId == -1) {
            userRepository.inserirUsuario(nome, email, telefone, senha)
        } else {
            userRepository.atualizarUsuario(usuarioId, nome, email, telefone, senha)
        }

        if (sucesso) {
            Toast.makeText(this, "Usuário salvo com sucesso", Toast.LENGTH_SHORT).show()
            val intent = Intent(this, MenuActivity::class.java)
            startActivity(intent)
        } else {
            Toast.makeText(this, "Erro ao salvar usuário", Toast.LENGTH_SHORT).show()
        }
    }
}