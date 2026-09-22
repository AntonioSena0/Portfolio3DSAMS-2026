package com.example.escape_room_app.data.model

import com.google.firebase.firestore.DocumentId
import com.google.firebase.firestore.ServerTimestamp
import java.util.Date

data class Station(
    @DocumentId val id: String = "",
    val nome: String = "",
    val descricao: String = "",
    val disciplina: String = "",
    val dificuldade: String = "Fácil",
    val tempoLimiteMin: Int = 15,
    val ordem: Int = 1,
    val ativa: Boolean = true,
    @ServerTimestamp val criadoEm: Date? = null
)

data class Hint(
    @DocumentId val id: String = "",
    val estacaoId: String = "",
    val titulo: String = "",
    val texto: String = "",
    val custoPontos: Int = 10,
    val ordem: Int = 1,
    @ServerTimestamp val criadoEm: Date? = null
)

data class Team(
    @DocumentId val id: String = "",
    val nome: String = "",
    val turma: String = "",
    val membros: String = "",
    val lider: String = "",
    @ServerTimestamp val criadoEm: Date? = null
)

data class Score(
    @DocumentId val id: String = "",
    val equipeId: String = "",
    val equipeNome: String = "",
    val estacaoId: String = "",
    val estacaoNome: String = "",
    val pontos: Int = 0,
    val tempoResolucaoMin: Int = 0,
    val pistasUsadas: Int = 0,
    val concluida: Boolean = false,
    @ServerTimestamp val criadoEm: Date? = null
)

object Collections {
    const val STATIONS = "estacoes"
    const val HINTS = "pistas"
    const val TEAMS = "equipes"
    const val SCORES = "pontuacoes"
}

val DISCIPLINAS = listOf("Geral", "Matemática", "História", "Ciências", "Geografia", "Literatura", "Artes", "Inglês")
val DIFICULDADES = listOf("Fácil", "Médio", "Difícil", "Lendário")
