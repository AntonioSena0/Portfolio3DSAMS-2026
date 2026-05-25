package com.example.meetflow.activities

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.TextView
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.meetflow.R
import com.example.meetflow.adapters.UsuarioAdapter
import com.example.meetflow.database.DatabaseHelper
import com.example.meetflow.model.User
import com.google.android.material.appbar.MaterialToolbar
import com.google.android.material.floatingactionbutton.FloatingActionButton
import com.google.android.material.snackbar.Snackbar

class ListaUsuariosActivity : AppCompatActivity() {

    private lateinit var recyclerUsuarios: RecyclerView

    private lateinit var adapter: UsuarioAdapter

    private lateinit var toolbar:
            MaterialToolbar

    private lateinit var db: DatabaseHelper

    private lateinit var fabAdicionar:
            FloatingActionButton

    private lateinit var txtEmpty:
            TextView

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(R.layout.activity_lista_usuarios)

        inicializarComponentes()

        configurarRecyclerView()

        db = DatabaseHelper(this)

        setSupportActionBar(toolbar)

        supportActionBar?.title =
            "MeetFlow"

        supportActionBar?.setDisplayHomeAsUpEnabled(
            true
        )

        fabAdicionar.setOnClickListener {
            abrirCadastroUsuario()
        }

    }

    override fun onResume() {

        super.onResume()

        carregarUsuarios()
    }

    override fun onSupportNavigateUp(): Boolean {

        finish()

        return true
    }

    private fun inicializarComponentes() {

        recyclerUsuarios =
            findViewById(R.id.recyclerUsuarios)

        toolbar =
            findViewById(R.id.toolbar)

        fabAdicionar =
            findViewById(R.id.fabAdicionar)

        txtEmpty =
            findViewById(R.id.txtEmpty)

    }

    private fun abrirCadastroUsuario() {

        val intent = Intent(
            this,
            CadastrarUsuarioActivity::class.java
        )

        startActivity(intent)
    }

    private fun configurarRecyclerView() {

        recyclerUsuarios.layoutManager =
            LinearLayoutManager(this)
    }

    private fun carregarUsuarios() {

        val listaUsuarios = db.listarUsuarios()

        if (listaUsuarios.isEmpty()) {
            txtEmpty.visibility = View.VISIBLE
        } else {
            txtEmpty.visibility = View.GONE
        }

        adapter = UsuarioAdapter(

            listaUsuarios,

            onItemClick = { usuario ->

                abrirEdicaoUsuario(usuario.id)
            },

            onDeleteClick = { usuario ->

                mostrarDialogoExclusao(usuario)
            }
        )

        recyclerUsuarios.adapter = adapter
    }

    private fun abrirEdicaoUsuario(id: Int) {

        val intent = Intent(
            this,
            CadastrarUsuarioActivity::class.java
        )

        intent.putExtra("usuario_id", id)

        startActivity(intent)
    }

    private fun mostrarDialogoExclusao(usuario: User) {

        AlertDialog.Builder(this)

            .setTitle("Excluir Usuário")

            .setMessage(
                "Deseja excluir ${usuario.nome}?"
            )

            .setPositiveButton("Sim") { _, _ ->

                excluirUsuario(usuario.id)

            }

            .setNegativeButton("Não", null)

            .show()
    }

    private fun excluirUsuario(id: Int) {

        val sucesso = db.deletarUsuario(id)

        if (sucesso) {
            carregarUsuarios()
            Snackbar.make(
                findViewById(android.R.id.content),
                "Usuário excluído com sucesso",
                Snackbar.LENGTH_SHORT
            ).show()
        }
    }

}