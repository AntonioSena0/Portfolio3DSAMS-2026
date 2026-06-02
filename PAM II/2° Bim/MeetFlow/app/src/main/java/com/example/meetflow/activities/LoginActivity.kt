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
import com.example.meetflow.security.Security
import com.example.meetflow.session.SessionManager
import com.example.meetflow.validators.Validator

class LoginActivity : AppCompatActivity() {

    private lateinit var edtEmail: EditText
    private lateinit var edtSenha: EditText
    private lateinit var btnEntrar: Button
    private lateinit var btnCadastrar: Button
    private lateinit var userRepository: UserRepository
    private lateinit var security: Security
    private lateinit var sessionManager: SessionManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        userRepository = UserRepository(UserDao(applicationContext))
        security = Security()
        sessionManager = SessionManager(applicationContext)

        edtEmail = findViewById(R.id.edtEmail)
        edtSenha = findViewById(R.id.edtSenha)
        btnEntrar = findViewById(R.id.btnEntrar)
        btnCadastrar = findViewById(R.id.btnCadastrar)

        btnEntrar.setOnClickListener {
            val email = edtEmail.text.toString().trim()
            val senha = edtSenha.text.toString().trim()

            if (!Validator.isValidRequiredField(email) ||
                !Validator.isValidRequiredField(senha) ||
                !Validator.isValidEmail(email) ||
                !Validator.isValidPassword(senha)) {
                Toast.makeText(this, "Preencha todos os campos corretamente", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            val usuario = userRepository.buscarUsuarioPorEmail(email)
            if (usuario != null && security.verifyPassword(senha, usuario.senha)) {
                sessionManager.createLoginSession(usuario.id, usuario.nome, usuario.email)
                val intent = Intent(this, MenuActivity::class.java)
                startActivity(intent)
                finish()
            } else {
                Toast.makeText(this, "Email ou senha incorretos", Toast.LENGTH_SHORT).show()
            }
        }

        btnCadastrar.setOnClickListener {
            val intent = Intent(this, CadastrarUsuarioActivity::class.java)
            startActivity(intent)
        }
    }
}