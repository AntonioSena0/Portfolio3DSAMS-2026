package com.example.meetflow.adapters

import android.app.AlertDialog
import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.meetflow.R
import com.example.meetflow.activities.ListaReunioesActivity
import com.example.meetflow.database.DatabaseHelper
import com.example.meetflow.model.Reuniao
import com.google.android.material.snackbar.Snackbar

class ReuniaoAdapter(
    private val context: Context,
    private val listaReunioes: List<Reuniao>,
    private val onItemClick: (Reuniao) -> Unit,
    private val onDeleteClick: (Reuniao) -> Unit
) : RecyclerView.Adapter<ReuniaoAdapter.ReuniaoViewHolder>() {

    private val db = DatabaseHelper(context)

    inner class ReuniaoViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val txtTitulo: TextView = itemView.findViewById(R.id.txtTitulo)
        val txtDescricao: TextView = itemView.findViewById(R.id.txtDescricao)
        val txtData: TextView = itemView.findViewById(R.id.txtData)
        val txtParticipantes: TextView = itemView.findViewById(R.id.txtParticipantes)
        val btnExcluirReuniao: Button = itemView.findViewById(R.id.btnExcluirReuniao)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ReuniaoViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_reuniao, parent, false)
        return ReuniaoViewHolder(view)
    }

    override fun onBindViewHolder(holder: ReuniaoViewHolder, position: Int) {
        val reuniao = listaReunioes[position]

        holder.itemView.setOnClickListener {
            onItemClick(reuniao)
        }

        holder.txtTitulo.text = reuniao.titulo
        holder.txtDescricao.text = reuniao.descricao
        holder.txtData.text = reuniao.data

        holder.btnExcluirReuniao.setOnClickListener {
            onDeleteClick(reuniao)
        }

        val quantidadeParticipantes = db.contarParticipantes(reuniao.id)
        holder.txtParticipantes.text = "Participantes: $quantidadeParticipantes"
    }

    override fun getItemCount(): Int = listaReunioes.size
}