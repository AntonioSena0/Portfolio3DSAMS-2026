package com.example.meetflow.database.daos

import android.content.ContentValues
import android.content.Context
import android.database.sqlite.SQLiteDatabase
import com.example.meetflow.database.helpers.DatabaseHelper
import com.example.meetflow.model.User

class UserDao(private val context: Context) {

    private val databaseHelper = DatabaseHelper(context)

    fun listarUsuarios(): List<User> {
        val listaUsuarios = mutableListOf<User>()
        val db = databaseHelper.readableDatabase
        val query = "SELECT * FROM usuario"
        val cursor = db.rawQuery(query, null)
        if (cursor.moveToFirst()) {
            do {
                val usuario = User(
                    id = cursor.getInt(0),
                    nome = cursor.getString(1),
                    email = cursor.getString(2),
                    telefone = cursor.getString(3),
                    senha = cursor.getString(4)
                )
                listaUsuarios.add(usuario)
            } while (cursor.moveToNext())
        }
        cursor.close()
        db.close()
        return listaUsuarios
    }

    fun buscarUsuarioPorId(id: Int): User? {
        val db = databaseHelper.readableDatabase
        val query = "SELECT * FROM usuario WHERE id = ?"
        val cursor = db.rawQuery(query, arrayOf(id.toString()))
        var usuario: User? = null
        if (cursor.moveToFirst()) {
            usuario = User(
                id = cursor.getInt(0),
                nome = cursor.getString(1),
                email = cursor.getString(2),
                telefone = cursor.getString(3),
                senha = cursor.getString(4)
            )
        }
        cursor.close()
        db.close()
        return usuario
    }

    fun buscarUsuarioPorEmail(email: String): User? {
        val db = databaseHelper.readableDatabase
        val query = "SELECT * FROM usuario WHERE email = ?"
        val cursor = db.rawQuery(query, arrayOf(email))
        var usuario: User? = null
        if (cursor.moveToFirst()) {
            usuario = User(
                id = cursor.getInt(0),
                nome = cursor.getString(1),
                email = cursor.getString(2),
                telefone = cursor.getString(3),
                senha = cursor.getString(4)
            )
        }
        cursor.close()
        db.close()
        return usuario
    }

    fun contarUsuarios(): Int {
        val db = databaseHelper.readableDatabase
        val query = "SELECT COUNT(*) FROM usuario"
        val cursor = db.rawQuery(query, null)
        var total = 0
        if (cursor.moveToFirst()) {
            total = cursor.getInt(0)
        }
        cursor.close()
        db.close()
        return total
    }

    fun inserirUsuario(nome: String, email: String, telefone: String, senha: String): Boolean {
        val db = databaseHelper.writableDatabase
        val values = ContentValues().apply {
            put("nome", nome)
            put("email", email)
            put("telefone", telefone)
            put("senha", senha)
        }
        val resultado = db.insert("usuario", null, values)
        db.close()
        return resultado != -1L
    }

    fun atualizarUsuario(id: Int, nome: String, email: String, telefone: String, senha: String): Boolean {
        val db = databaseHelper.writableDatabase
        val values = ContentValues().apply {
            put("nome", nome)
            put("email", email)
            put("telefone", telefone)
            put("senha", senha)
        }
        val resultado = db.update(
            "usuario",
            values,
            "id = ?",
            arrayOf(id.toString())
        )
        db.close()
        return resultado > 0
    }

    fun deletarUsuario(id: Int): Boolean {
        val db = databaseHelper.writableDatabase
        val resultado = db.delete("usuario", "id = ?", arrayOf(id.toString()))
        if (resultado > 0) {
            deletarRelacionamentosUsuario(id)
        }
        db.close()
        return resultado > 0
    }

    private fun deletarRelacionamentosUsuario(usuarioId: Int) {
        val db = databaseHelper.writableDatabase
        db.delete(
            "usuario_reuniao",
            "usuario_id = ?",
            arrayOf(usuarioId.toString())
        )
        db.close()
    }
}