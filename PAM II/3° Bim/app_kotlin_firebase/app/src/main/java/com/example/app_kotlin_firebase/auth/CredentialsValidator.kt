package com.example.app_kotlin_firebase.auth

import android.util.Patterns

object CredentialsValidator {
    fun validate(email: String, password: String): String? = when {
        email.isBlank() || password.isBlank() -> "Preencha o e-mail e a senha."
        !Patterns.EMAIL_ADDRESS.matcher(email.trim()).matches() -> "Informe um e-mail válido."
        password.length < 6 -> "A senha deve ter pelo menos 6 caracteres."
        else -> null
    }
}
