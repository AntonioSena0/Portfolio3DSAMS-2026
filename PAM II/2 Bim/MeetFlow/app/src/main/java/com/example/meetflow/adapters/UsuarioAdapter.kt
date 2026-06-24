package com.example.meetflow.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.meetflow.R
import com.example.meetflow.model.User
import com.google.android.material.snackbar.Snackbar

class UsuarioAdapter(
    private val listaUsuarios: List<User>,
    private val onItemClick: (User) -> Unit,
    private val onDeleteClick: (User) -> Unit
) : RecyclerView.Adapter<UsuarioAdapter.UsuarioViewHolder>() {

    class UsuarioViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val txtNome: TextView = itemView.findViewById(R.id.txtNome)
        val txtEmail: TextView = itemView.findViewById(R.id.txtEmail)
        val txtTelefone: TextView = itemView.findViewById(R.id.txtTelefone)
        val btnExcluir: Button = itemView.findViewById(R.id.btnExcluir)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): UsuarioViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_usuario, parent, false)
        return UsuarioViewHolder(view)
    }

    override fun onBindViewHolder(holder: UsuarioViewHolder, position: Int) {
        val usuario = listaUsuarios[position]

        holder.txtNome.text = usuario.nome
        holder.txtEmail.text = usuario.email
        holder.txtTelefone.text = usuario.telefone

        holder.itemView.setOnClickListener {
            onItemClick(usuario)
        }

        holder.btnExcluir.setOnClickListener {
            onDeleteClick(usuario)
        }
    }

    override fun getItemCount(): Int = listaUsuarios.size
}