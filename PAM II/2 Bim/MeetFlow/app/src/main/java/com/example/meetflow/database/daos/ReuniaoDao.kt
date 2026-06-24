package com.example.meetflow.database.daos

import android.content.ContentValues
import android.content.Context
import android.database.Cursor
import android.database.sqlite.SQLiteDatabase
import com.example.meetflow.database.helpers.DatabaseHelper
import com.example.meetflow.model.Reuniao
import com.example.meetflow.model.User

class ReuniaoDao(private val context: Context) {

    private val databaseHelper = DatabaseHelper(context)

    fun listarReunioes(): List<Reuniao> {
        val listaReunioes = mutableListOf<Reuniao>()
        val db = databaseHelper.readableDatabase
        val query = "SELECT * FROM reuniao"
        val cursor = db.rawQuery(query, null)
        if (cursor.moveToFirst()) {
            do {
                val reuniao = Reuniao(
                    id = cursor.getInt(0),
                    titulo = cursor.getString(1),
                    descricao = cursor.getString(2),
                    data = cursor.getString(3)
                )
                listaReunioes.add(reuniao)
            } while (cursor.moveToNext())
        }
        cursor.close()
        db.close()
        return listaReunioes
    }

    fun buscarReuniaoPorId(id: Int): Reuniao? {
        val db = databaseHelper.readableDatabase
        val query = "SELECT * FROM reuniao WHERE id = ?"
        val cursor = db.rawQuery(query, arrayOf(id.toString()))
        var reuniao: Reuniao? = null
        if (cursor.moveToFirst()) {
            reuniao = Reuniao(
                id = cursor.getInt(0),
                titulo = cursor.getString(1),
                descricao = cursor.getString(2),
                data = cursor.getString(3)
            )
        }
        cursor.close()
        db.close()
        return reuniao
    }

    fun contarReunioes(): Int {
        val db = databaseHelper.readableDatabase
        val query = "SELECT COUNT(*) FROM reuniao"
        val cursor = db.rawQuery(query, null)
        var total = 0
        if (cursor.moveToFirst()) {
            total = cursor.getInt(0)
        }
        cursor.close()
        db.close()
        return total
    }

    fun inserirReuniao(titulo: String, descricao: String, data: String): Long {
        val db = databaseHelper.writableDatabase
        val values = ContentValues().apply {
            put("titulo", titulo)
            put("descricao", descricao)
            put("data", data)
        }
        val resultado = db.insert("reuniao", null, values)
        db.close()
        return resultado
    }

    fun deletarReuniao(id: Int): Boolean {
        deletarRelacionamentosReuniao(id)
        val db = databaseHelper.writableDatabase
        val resultado = db.delete("reuniao", "id = ?", arrayOf(id.toString()))
        db.close()
        return resultado > 0
    }

    private fun deletarRelacionamentosReuniao(reuniaoId: Int) {
        val db = databaseHelper.writableDatabase
        db.delete(
            "usuario_reuniao",
            "reuniao_id = ?",
            arrayOf(reuniaoId.toString())
        )
        db.close()
    }

    fun vincularUsuarioReuniao(usuarioId: Int, reuniaoId: Int) {
        val db = databaseHelper.writableDatabase
        val values = ContentValues().apply {
            put("usuario_id", usuarioId)
            put("reuniao_id", reuniaoId)
        }
        db.insert("usuario_reuniao", null, values)
        db.close()
    }

    fun contarParticipantes(reuniaoId: Int): Int {
        val db = databaseHelper.readableDatabase
        val query = """
            SELECT COUNT(usuario.id)
            FROM usuario usuario
            INNER JOIN usuario_reuniao ur
            ON usuario.id = ur.usuario_id
            WHERE ur.reuniao_id = ?
        """.trimIndent()
        val cursor = db.rawQuery(query, arrayOf(reuniaoId.toString()))
        var quantidade = 0
        if (cursor.moveToFirst()) {
            quantidade = cursor.getInt(0)
        }
        cursor.close()
        db.close()
        return quantidade
    }

    fun buscarParticipantesDaReuniao(reuniaoId: Int): List<User> {
        val listaUsuarios = mutableListOf<User>()
        val db = databaseHelper.readableDatabase
        val query = """
            SELECT usuario.*
            FROM usuario usuario
            INNER JOIN usuario_reuniao ur
            ON usuario.id = ur.usuario_id
            WHERE ur.reuniao_id = ?
        """.trimIndent()
        val cursor = db.rawQuery(query, arrayOf(reuniaoId.toString()))
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

    fun limparRelacionamentosOrfaos() {
        val db = databaseHelper.writableDatabase
        val query = """
            DELETE FROM usuario_reuniao
            WHERE usuario_id NOT IN (
                SELECT id
                FROM usuario
            )
        """.trimIndent()
        db.execSQL(query)
        db.close()
    }
}