package com.example.meetflow.activities

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.TextView
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.meetflow.R
import com.example.meetflow.adapters.ReuniaoAdapter
import com.example.meetflow.database.DatabaseHelper
import com.example.meetflow.model.Reuniao
import com.example.meetflow.model.User
import com.google.android.material.appbar.MaterialToolbar
import com.google.android.material.floatingactionbutton.FloatingActionButton
import com.google.android.material.snackbar.Snackbar

class ListaReunioesActivity
    : AppCompatActivity() {

    private lateinit var recyclerReunioes:
            RecyclerView

    private lateinit var toolbar:
            MaterialToolbar

    private lateinit var adapter:
            ReuniaoAdapter

    private lateinit var db: DatabaseHelper

    private lateinit var fabAdicionar:
            FloatingActionButton

    private lateinit var txtEmpty:
            TextView

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(
            R.layout.activity_lista_reunioes
        )

        inicializarComponentes()

        db = DatabaseHelper(this)

        carregarReunioes()

        setSupportActionBar(toolbar)

        supportActionBar?.title =
            "MeetFlow"

        supportActionBar?.setDisplayHomeAsUpEnabled(
            true
        )

        fabAdicionar.setOnClickListener {
            abrirCadastroReuniao()
        }

    }

    override fun onResume() {

        super.onResume()

        carregarReunioes()

    }

    override fun onSupportNavigateUp(): Boolean {

        finish()

        return true
    }

    private fun inicializarComponentes() {

        recyclerReunioes =
            findViewById(R.id.recyclerReunioes)

        toolbar =
            findViewById(R.id.toolbar)

        fabAdicionar =
            findViewById(R.id.fabAdicionar)

        txtEmpty =
            findViewById(R.id.txtEmpty)

    }

    private fun abrirCadastroReuniao() {

        val intent = Intent(
            this,
            CadastrarReuniaoActivity::class.java
        )

        startActivity(intent)
    }

    private fun carregarReunioes() {

        val listaReunioes =
            db.listarReunioes()

        if (listaReunioes.isEmpty()) {
            txtEmpty.visibility = View.VISIBLE
        } else {
            txtEmpty.visibility = View.GONE
        }

        adapter = ReuniaoAdapter(
            this,
            listaReunioes,
            onItemClick = { reuniao ->
                abrirDetalhesReuniao(reuniao.id)
            },
            onDeleteClick = { reuniao ->
                mostrarDialogoExclusao(reuniao)
            }
        )

        recyclerReunioes.layoutManager =
            LinearLayoutManager(this)

        recyclerReunioes.adapter = adapter
    }

    private fun abrirDetalhesReuniao(
        reuniaoId: Int
    ) {

        val intent = Intent(
            this,
            DetalhesReuniaoActivity::class.java
        )

        intent.putExtra(
            "reuniao_id",
            reuniaoId
        )

        startActivity(intent)
    }

    private fun mostrarDialogoExclusao(reuniao: Reuniao) {

        AlertDialog.Builder(this)

            .setTitle("Excluir Reunião")

            .setMessage(
                "Deseja excluir ${reuniao.titulo}?"
            )

            .setPositiveButton("Sim") { _, _ ->

                excluirReuniao(reuniao.id)

            }

            .setNegativeButton("Não", null)

            .show()
    }

    private fun excluirReuniao(id: Int) {

        val sucesso = db.deletarReuniao(id)

        if (sucesso) {
            carregarReunioes()
            Snackbar.make(
                findViewById(android.R.id.content),
                "Reunião excluída com sucesso",
                Snackbar.LENGTH_SHORT
            ).show()
        }
    }

}