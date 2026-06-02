package com.example.meetflow.activities

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.meetflow.R
import com.example.meetflow.adapters.SelecionarUsuarioAdapter
import com.example.meetflow.services.ReuniaoService
import com.example.meetflow.database.daos.ReuniaoDao
import com.example.meetflow.database.daos.UserDao
import com.example.meetflow.repositories.ReuniaoRepository
import com.google.android.material.appbar.MaterialToolbar

class CadastrarReuniaoActivity : AppCompatActivity() {

    private lateinit var recyclerParticipantes:
            RecyclerView

    private lateinit var adapter:
            SelecionarUsuarioAdapter

    private lateinit var edtTitulo: EditText

    private lateinit var edtDescricao: EditText

    private lateinit var edtData: EditText

    private lateinit var btnSalvarReuniao: Button

    private lateinit var toolbar:
            MaterialToolbar

    private lateinit var reuniaoDao: ReuniaoDao
private lateinit var usuarioDao: UserDao

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(
            R.layout.activity_cadastrar_reuniao
        )

        inicializarComponentes()

        configurarClicks()

        reuniaoDao = ReuniaoDao(applicationContext)
        usuarioDao = UserDao(applicationContext)
        carregarUsuarios()

        setSupportActionBar(toolbar)

        supportActionBar?.title =
            "MeetFlow"

        supportActionBar?.setDisplayHomeAsUpEnabled(
            true
        )

    }

    private fun inicializarComponentes() {

        edtTitulo =
            findViewById(R.id.edtTitulo)

        edtDescricao =
            findViewById(R.id.edtDescricao)

        edtData =
            findViewById(R.id.edtData)

        recyclerParticipantes =
            findViewById(R.id.recyclerParticipantes)

        btnSalvarReuniao =
            findViewById(R.id.btnSalvarReuniao)

        toolbar =
            findViewById(R.id.toolbar)

    }

    override fun onSupportNavigateUp(): Boolean {

        finish()

        return true
    }

    private fun carregarUsuarios() {

        val listaUsuarios = usuarioDao.listarUsuarios()

        adapter = SelecionarUsuarioAdapter(
            listaUsuarios
        )

        recyclerParticipantes.layoutManager =
            LinearLayoutManager(this)

        recyclerParticipantes.adapter = adapter
    }

    private fun configurarClicks() {

        btnSalvarReuniao.setOnClickListener {

            salvarReuniao()
        }
    }

    private fun salvarReuniao() {

        val titulo =
            edtTitulo.text.toString().trim()

        val descricao =
            edtDescricao.text.toString().trim()

        val data =
            edtData.text.toString().trim()

        if (
            titulo.isEmpty() ||
            descricao.isEmpty() ||
            data.isEmpty()
        ) {

            Toast.makeText(
                this,
                "Preencha todos os campos",
                Toast.LENGTH_SHORT
            ).show()

            return
        }

        val reuniaoId = reuniaoDao.inserirReuniao(
            titulo,
            descricao,
            data
        )

        if (reuniaoId != -1L) {

            val participantesSelecionados =
                adapter.obterUsuariosSelecionados()

            participantesSelecionados.forEach {

                    usuarioId ->

                reuniaoDao.vincularUsuarioReuniao(
                    usuarioId,
                    reuniaoId.toInt()
                )
            }

            Toast.makeText(
                this,
                "Reunião salva com sucesso",
                Toast.LENGTH_SHORT
            ).show()

            finish()

        } else {

            Toast.makeText(
                this,
                "Erro ao salvar reunião",
                Toast.LENGTH_SHORT
            ).show()
        }
    }

}