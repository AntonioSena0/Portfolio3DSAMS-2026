package com.example.meetflow.activities

import android.os.Bundle
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.example.meetflow.R
import com.example.meetflow.database.daos.ReuniaoDao
import com.google.android.material.appbar.MaterialToolbar

class DetalhesReuniaoActivity
    : AppCompatActivity() {

    private lateinit var txtTitulo: TextView

    private lateinit var txtDescricao: TextView

    private lateinit var txtData: TextView

    private lateinit var txtParticipantes:
            TextView

    private lateinit var toolbar:
            MaterialToolbar

    private lateinit var db: ReuniaoDao

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)

        setContentView(
            R.layout.activity_detalhes_reuniao
        )

        supportActionBar?.setDisplayHomeAsUpEnabled(
            true
        )

        inicializarComponentes()

        db = ReuniaoDao(applicationContext)

        carregarDados()

        setSupportActionBar(toolbar)

        supportActionBar?.title =
            "MeetFlow"

        supportActionBar?.setDisplayHomeAsUpEnabled(
            true
        )

    }

    override fun onSupportNavigateUp(): Boolean {

        finish()

        return true
    }

    private fun inicializarComponentes() {

        txtTitulo =
            findViewById(R.id.txtTitulo)

        txtDescricao =
            findViewById(R.id.txtDescricao)

        txtData =
            findViewById(R.id.txtData)

        txtParticipantes =
            findViewById(R.id.txtParticipantes)

        toolbar =
            findViewById(R.id.toolbar)

    }

    private fun carregarDados() {

        val reuniaoId =
            intent.getIntExtra(
                "reuniao_id",
                -1
            )

        if (reuniaoId == -1) {

            finish()

            return
        }

        val reuniao =
            db.buscarReuniaoPorId(reuniaoId)

        reuniao?.let {

            txtTitulo.text = it.titulo

            txtDescricao.text = it.descricao

            txtData.text = it.data
        }

        val participantes =
            db.buscarParticipantesDaReuniao(
                reuniaoId
            )

        val participantesTexto =
            StringBuilder()

        participantes.forEach {

            participantesTexto.append(
                "- ${it.nome}, ${it.telefone}\n \n"
            )
        }

        txtParticipantes.text =
            participantesTexto.toString()
    }

}