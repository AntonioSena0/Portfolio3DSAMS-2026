package com.example.meetflow.database.helpers

import android.content.ContentValues
import android.content.Context
import android.database.sqlite.SQLiteDatabase
import android.database.sqlite.SQLiteOpenHelper
import com.example.meetflow.model.Reuniao
import com.example.meetflow.model.User

class DatabaseHelper(context: Context)
    : SQLiteOpenHelper(context, DATABASE_NAME, null, DATABASE_VERSION) {

    companion object {
        private const val DATABASE_NAME = "meetflow.db"
        private const val DATABASE_VERSION = 3

        private const val TABLE_USUARIO = "usuario"
        private const val COLUMN_USUARIO_ID = "id"
        private const val COLUMN_USUARIO_NOME = "nome"
        private const val COLUMN_USUARIO_EMAIL = "email"
        private const val COLUMN_USUARIO_TELEFONE = "telefone"
        private const val COLUMN_USUARIO_SENHA = "senha"

        private const val TABLE_REUNIAO = "reuniao"
        private const val COLUMN_REUNIAO_ID = "id"
        private const val COLUMN_REUNIAO_TITULO = "titulo"
        private const val COLUMN_REUNIAO_DESCRICAO = "descricao"
        private const val COLUMN_REUNIAO_DATA = "data"

        private const val TABLE_USUARIO_REUNIAO = "usuario_reuniao"
        private const val COLUMN_UR_USUARIO_ID = "usuario_id"
        private const val COLUMN_UR_REUNIAO_ID = "reuniao_id"
    }

    override fun onCreate(db: SQLiteDatabase) {
        val createTableUsuario = """
            CREATE TABLE $TABLE_USUARIO(
                $COLUMN_USUARIO_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                $COLUMN_USUARIO_NOME TEXT,
                $COLUMN_USUARIO_EMAIL TEXT,
                $COLUMN_USUARIO_TELEFONE TEXT,
                $COLUMN_USUARIO_SENHA TEXT
            )
        """.trimIndent()

        val createTableReuniao = """
            CREATE TABLE $TABLE_REUNIAO (
                $COLUMN_REUNIAO_ID INTEGER PRIMARY KEY AUTOINCREMENT,
                $COLUMN_REUNIAO_TITULO TEXT,
                $COLUMN_REUNIAO_DESCRICAO TEXT,
                $COLUMN_REUNIAO_DATA TEXT
            )
        """.trimIndent()

        val createTableUsuarioReuniao = """
            CREATE TABLE $TABLE_USUARIO_REUNIAO (
                $COLUMN_UR_USUARIO_ID INTEGER,
                $COLUMN_UR_REUNIAO_ID INTEGER,
                FOREIGN KEY($COLUMN_UR_USUARIO_ID)
                    REFERENCES $TABLE_USUARIO($COLUMN_USUARIO_ID),
                FOREIGN KEY($COLUMN_UR_REUNIAO_ID)
                    REFERENCES $TABLE_REUNIAO($COLUMN_REUNIAO_ID)
            )
        """.trimIndent()

        db.execSQL(createTableUsuario)
        db.execSQL(createTableReuniao)
        db.execSQL(createTableUsuarioReuniao)
    }

    override fun onUpgrade(
        db: SQLiteDatabase,
        oldVersion: Int,
        newVersion: Int
    ) {
        db.execSQL("DROP TABLE IF EXISTS $TABLE_USUARIO_REUNIAO")
        db.execSQL("DROP TABLE IF EXISTS $TABLE_REUNIAO")
        db.execSQL("DROP TABLE IF EXISTS $TABLE_USUARIO")
        onCreate(db)
    }

    fun listarUsuarios(): List<User> {
        val listaUsuarios = mutableListOf<User>()
        val db = this.readableDatabase
        val query = "SELECT * FROM $TABLE_USUARIO"
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
        val db = this.readableDatabase
        val query = "SELECT * FROM $TABLE_USUARIO WHERE $COLUMN_USUARIO_ID = ?"
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

    fun contarUsuarios(): Int {
        val db = this.readableDatabase
        val query = "SELECT COUNT(*) FROM $TABLE_USUARIO"
        val cursor = db.rawQuery(query, null)
        var total = 0
        if (cursor.moveToFirst()) {
            total = cursor.getInt(0)
        }
        cursor.close()
        db.close()
        return total
    }

    fun inserirUsuario(
        nome: String,
        email: String,
        telefone: String,
        senha: String
    ): Boolean {
        val db = this.writableDatabase
        val values = ContentValues().apply {
            put(COLUMN_USUARIO_NOME, nome)
            put(COLUMN_USUARIO_EMAIL, email)
            put(COLUMN_USUARIO_TELEFONE, telefone)
            put(COLUMN_USUARIO_SENHA, senha)
        }
        val resultado = db.insert(TABLE_USUARIO, null, values)
        db.close()
        return resultado != -1L
    }

    fun atualizarUsuario(
        id: Int,
        nome: String,
        email: String,
        telefone: String,
        senha: String
    ): Boolean {
        val db = this.writableDatabase
        val values = ContentValues().apply {
            put(COLUMN_USUARIO_NOME, nome)
            put(COLUMN_USUARIO_EMAIL, email)
            put(COLUMN_USUARIO_TELEFONE, telefone)
            put(COLUMN_USUARIO_SENHA, senha)
        }
        val resultado = db.update(
            TABLE_USUARIO,
            values,
            "$COLUMN_USUARIO_ID = ?",
            arrayOf(id.toString())
        )
        db.close()
        return resultado > 0
    }

    fun deletarUsuario(id: Int): Boolean {
        val db = this.writableDatabase
        val resultado = db.delete(
            TABLE_USUARIO,
            "$COLUMN_USUARIO_ID = ?",
            arrayOf(id.toString())
        )
        if (resultado > 0) {
            deletarRelacionamentosUsuario(id)
        }
        db.close()
        return resultado > 0
    }

    fun listarReunioes(): List<Reuniao> {
        val listaReunioes = mutableListOf<Reuniao>()
        val db = this.readableDatabase
        val query = "SELECT * FROM $TABLE_REUNIAO"
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
        val db = this.readableDatabase
        val query = "SELECT * FROM $TABLE_REUNIAO WHERE $COLUMN_REUNIAO_ID = ?"
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
        val db = this.readableDatabase
        val query = "SELECT COUNT(*) FROM $TABLE_REUNIAO"
        val cursor = db.rawQuery(query, null)
        var total = 0
        if (cursor.moveToFirst()) {
            total = cursor.getInt(0)
        }
        cursor.close()
        db.close()
        return total
    }

    fun inserirReuniao(
        titulo: String,
        descricao: String,
        data: String
    ): Long {
        val db = this.writableDatabase
        val values = ContentValues().apply {
            put(COLUMN_REUNIAO_TITULO, titulo)
            put(COLUMN_REUNIAO_DESCRICAO, descricao)
            put(COLUMN_REUNIAO_DATA, data)
        }
        val resultado = db.insert(TABLE_REUNIAO, null, values)
        db.close()
        return resultado
    }

    fun deletarReuniao(id: Int): Boolean {
        deletarRelacionamentosReuniao(id)
        val db = this.writableDatabase
        val resultado = db.delete(
            TABLE_REUNIAO,
            "$COLUMN_REUNIAO_ID = ?",
            arrayOf(id.toString())
        )
        db.close()
        return resultado > 0
    }

    fun vincularUsuarioReuniao(
        usuarioId: Int,
        reuniaoId: Int
    ) {
        val db = this.writableDatabase
        val values = ContentValues().apply {
            put(COLUMN_UR_USUARIO_ID, usuarioId)
            put(COLUMN_UR_REUNIAO_ID, reuniaoId)
        }
        db.insert(TABLE_USUARIO_REUNIAO, null, values)
        db.close()
    }

    fun deletarRelacionamentosReuniao(reuniaoId: Int) {
        val db = this.writableDatabase
        db.delete(
            TABLE_USUARIO_REUNIAO,
            "$COLUMN_UR_REUNIAO_ID = ?",
            arrayOf(reuniaoId.toString())
        )
        db.close()
    }

    fun deletarRelacionamentosUsuario(usuarioId: Int) {
        val db = this.writableDatabase
        db.delete(
            TABLE_USUARIO_REUNIAO,
            "$COLUMN_UR_USUARIO_ID = ?",
            arrayOf(usuarioId.toString())
        )
        db.close()
    }

    fun contarParticipantes(reuniaoId: Int): Int {
        val db = this.readableDatabase
        val query = """
            SELECT COUNT(usuario.id)
            FROM $TABLE_USUARIO usuario
            INNER JOIN $TABLE_USUARIO_REUNIAO ur
            ON usuario.$COLUMN_USUARIO_ID = ur.$COLUMN_UR_USUARIO_ID
            WHERE ur.$COLUMN_UR_REUNIAO_ID = ?
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
        val db = this.readableDatabase
        val query = """
            SELECT usuario.*
            FROM $TABLE_USUARIO usuario
            INNER JOIN $TABLE_USUARIO_REUNIAO ur
            ON usuario.$COLUMN_USUARIO_ID = ur.$COLUMN_UR_USUARIO_ID
            WHERE ur.$COLUMN_UR_REUNIAO_ID = ?
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
        val db = this.writableDatabase
        val query = """
            DELETE FROM $TABLE_USUARIO_REUNIAO
            WHERE $COLUMN_UR_USUARIO_ID NOT IN (
                SELECT $COLUMN_USUARIO_ID
                FROM $TABLE_USUARIO
            )
        """.trimIndent()
        db.execSQL(query)
        db.close()
    }
}