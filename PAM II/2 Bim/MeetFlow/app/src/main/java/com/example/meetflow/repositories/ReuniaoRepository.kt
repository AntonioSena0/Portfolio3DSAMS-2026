package com.example.meetflow.repositories

import com.example.meetflow.database.daos.ReuniaoDao
import com.example.meetflow.model.Reuniao
import com.example.meetflow.model.User

class ReuniaoRepository(private val reuniaoDao: ReuniaoDao) {

    fun listarReunioes(): List<Reuniao> = reuniaoDao.listarReunioes()

    fun buscarReuniaoPorId(id: Int): Reuniao? = reuniaoDao.buscarReuniaoPorId(id)

    fun contarReunioes(): Int = reuniaoDao.contarReunioes()

    fun inserirReuniao(titulo: String, descricao: String, data: String): Long =
        reuniaoDao.inserirReuniao(titulo, descricao, data)

    fun deletarReuniao(id: Int): Boolean = reuniaoDao.deletarReuniao(id)

    fun vincularUsuarioReuniao(usuarioId: Int, reuniaoId: Int) =
        reuniaoDao.vincularUsuarioReuniao(usuarioId, reuniaoId)

    fun contarParticipantes(reuniaoId: Int): Int =
        reuniaoDao.contarParticipantes(reuniaoId)

    fun buscarParticipantesDaReuniao(reuniaoId: Int): List<User> =
        reuniaoDao.buscarParticipantesDaReuniao(reuniaoId)

    fun limparRelacionamentosOrfaos() =
        reuniaoDao.limparRelacionamentosOrfaos()
}