package com.example.meetflow.model

data class User(
    val id: Int = 0,
    val nome: String,
    val email: String,
    val telefone: String,
    val senha: String
)