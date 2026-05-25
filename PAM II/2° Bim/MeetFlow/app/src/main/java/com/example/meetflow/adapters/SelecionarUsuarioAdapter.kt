package com.example.meetflow.adapters

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.CheckBox
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.meetflow.R
import com.example.meetflow.model.User

class SelecionarUsuarioAdapter(
    private val listaUsuarios: List<User>
) : RecyclerView.Adapter<
        SelecionarUsuarioAdapter.UsuarioViewHolder>() {

    private val usuariosSelecionados =
        mutableSetOf<Int>()

    class UsuarioViewHolder(itemView: View)
        : RecyclerView.ViewHolder(itemView) {

        val checkUsuario: CheckBox =
            itemView.findViewById(R.id.checkUsuario)

        val txtNome: TextView =
            itemView.findViewById(R.id.txtNome)

        val txtEmail: TextView =
            itemView.findViewById(R.id.txtEmail)
    }

    override fun onCreateViewHolder(
        parent: ViewGroup,
        viewType: Int
    ): UsuarioViewHolder {

        val view = LayoutInflater
            .from(parent.context)
            .inflate(
                R.layout.item_usuario_checkbox,
                parent,
                false
            )

        return UsuarioViewHolder(view)
    }

    override fun onBindViewHolder(
        holder: UsuarioViewHolder,
        position: Int
    ) {

        val usuario = listaUsuarios[position]

        holder.txtNome.text = usuario.nome

        holder.txtEmail.text = usuario.email

        holder.checkUsuario.setOnCheckedChangeListener(
            null
        )

        holder.checkUsuario.isChecked =
            usuariosSelecionados.contains(usuario.id)

        holder.checkUsuario
            .setOnCheckedChangeListener { _, isChecked ->

                if (isChecked) {

                    usuariosSelecionados.add(usuario.id)

                } else {

                    usuariosSelecionados.remove(usuario.id)
                }
            }
    }

    override fun getItemCount(): Int {

        return listaUsuarios.size
    }

    fun obterUsuariosSelecionados(): Set<Int> {

        return usuariosSelecionados
    }
}