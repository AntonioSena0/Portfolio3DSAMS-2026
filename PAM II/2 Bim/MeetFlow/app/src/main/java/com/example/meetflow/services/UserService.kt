package com.example.meetflow.services

import com.example.meetflow.model.User
import com.example.meetflow.repositories.UserRepository

class UserService(private val userRepository: UserRepository) {

    fun listarUsuarios(): List<User> = userRepository.listarUsuarios()

    fun buscarUsuarioPorId(id: Int): User? = userRepository.buscarUsuarioPorId(id)

    fun contarUsuarios(): Int = userRepository.contarUsuarios()

    fun inserirUsuario(nome: String, email: String, telefone: String, senha: String): Boolean {
        return userRepository.inserirUsuario(nome, email, telefone, senha)
    }

    fun atualizarUsuario(id: Int, nome: String, email: String, telefone: String, senha: String): Boolean {
        return userRepository.atualizarUsuario(id, nome, email, telefone, senha)
    }

    fun deletarUsuario(id: Int): Boolean = userRepository.deletarUsuario(id)
}