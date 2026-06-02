package com.example.meetflow.repositories

import com.example.meetflow.database.daos.UserDao
import com.example.meetflow.model.User
import com.example.meetflow.security.Security

class UserRepository(private val userDao: UserDao) {

    private val security = Security()

    fun listarUsuarios(): List<User> = userDao.listarUsuarios()

    fun buscarUsuarioPorId(id: Int): User? = userDao.buscarUsuarioPorId(id)

    fun buscarUsuarioPorEmail(email: String): User? = userDao.buscarUsuarioPorEmail(email)

    fun contarUsuarios(): Int = userDao.contarUsuarios()

    fun inserirUsuario(nome: String, email: String, telefone: String, senha: String): Boolean {
        val senhaHash = security.hashPassword(senha)
        return userDao.inserirUsuario(nome, email, telefone, senhaHash)
    }

    fun atualizarUsuario(id: Int, nome: String, email: String, telefone: String, senha: String): Boolean {
        val senhaHash = security.hashPassword(senha)
        return userDao.atualizarUsuario(id, nome, email, telefone, senhaHash)
    }

    fun deletarUsuario(id: Int): Boolean = userDao.deletarUsuario(id)
}