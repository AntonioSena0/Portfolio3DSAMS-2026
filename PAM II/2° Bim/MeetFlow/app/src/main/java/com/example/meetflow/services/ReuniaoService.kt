package com.example.meetflow.services

import com.example.meetflow.model.Reuniao
import com.example.meetflow.model.User
import com.example.meetflow.repositories.ReuniaoRepository

class ReuniaoService(private val reuniaoRepository: ReuniaoRepository) {

    fun listarReunioes(): List<Reuniao> = reuniaoRepository.listarReunioes()

    fun buscarReuniaoPorId(id: Int): Reuniao? = reuniaoRepository.buscarReuniaoPorId(id)

    fun contarReunioes(): Int = reuniaoRepository.contarReunioes()

    fun inserirReuniao(titulo: String, descricao: String, data: String): Long =
        reuniaoRepository.inserirReuniao(titulo, descricao, data)

    fun deletarReuniao(id: Int): Boolean = reuniaoRepository.deletarReuniao(id)

    fun vincularUsuarioReuniao(usuarioId: Int, reuniaoId: Int) =
        reuniaoRepository.vincularUsuarioReuniao(usuarioId, reuniaoId)

    fun contarParticipantes(reuniaoId: Int): Int =
        reuniaoRepository.contarParticipantes(reuniaoId)

    fun buscarParticipantesDaReuniao(reuniaoId: Int): List<User> =
        reuniaoRepository.buscarParticipantesDaReuniao(reuniaoId)

    fun limparRelacionamentosOrfaos() =
        reuniaoRepository.limparRelacionamentosOrfaos()
}