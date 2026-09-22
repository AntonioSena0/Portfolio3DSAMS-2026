package com.example.escape_room_app.ui.viewmodel

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.escape_room_app.data.model.Hint
import com.example.escape_room_app.data.model.Score
import com.example.escape_room_app.data.model.Station
import com.example.escape_room_app.data.model.Team
import com.example.escape_room_app.data.repo.HintRepo
import com.example.escape_room_app.data.repo.ScoreRepo
import com.example.escape_room_app.data.repo.StationRepo
import com.example.escape_room_app.data.repo.TeamRepo
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.SharingStarted
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.catch
import kotlinx.coroutines.flow.map
import kotlinx.coroutines.flow.stateIn
import kotlinx.coroutines.launch

data class UiState<T>(val items: List<T> = emptyList(), val loading: Boolean = true, val error: String? = null)

abstract class BaseCrudViewModel<T : Any> : ViewModel() {
    protected val _busy = MutableStateFlow(false)
    val busy: StateFlow<Boolean> = _busy.asStateFlow()
    protected val _msg = MutableStateFlow<String?>(null)
    val msg: StateFlow<String?> = _msg.asStateFlow()
    fun clearMsg() { _msg.value = null }

    protected suspend fun <R> runOp(okMsg: String, block: suspend () -> R) {
        _busy.value = true
        try { block(); _msg.value = okMsg }
        catch (e: Exception) { _msg.value = "Erro: ${e.message}" }
        finally { _busy.value = false }
    }
}

class StationViewModel : BaseCrudViewModel<Station>() {
    val state: StateFlow<UiState<Station>> = StationRepo.observe()
        .catch { emit(emptyList<Station>()) }
        .map { list: List<Station> -> UiState(list, loading = false) }
        .stateIn(viewModelScope, SharingStarted.WhileSubscribed(5000), UiState(loading = true))

    fun add(s: Station) = viewModelScope.launch { runOp("Estacao criada") { StationRepo.add(s) } }
    fun update(s: Station) = viewModelScope.launch { runOp("Estacao atualizada") { StationRepo.update(s) } }
    fun delete(id: String) = viewModelScope.launch { runOp("Estacao excluida") { StationRepo.delete(id) } }
}

class HintViewModel : BaseCrudViewModel<Hint>() {
    val state: StateFlow<UiState<Hint>> = HintRepo.observe()
        .catch { emit(emptyList<Hint>()) }
        .map { list: List<Hint> -> UiState(list, loading = false) }
        .stateIn(viewModelScope, SharingStarted.WhileSubscribed(5000), UiState(loading = true))

    fun add(h: Hint) = viewModelScope.launch { runOp("Pista criada") { HintRepo.add(h) } }
    fun update(h: Hint) = viewModelScope.launch { runOp("Pista atualizada") { HintRepo.update(h) } }
    fun delete(id: String) = viewModelScope.launch { runOp("Pista excluida") { HintRepo.delete(id) } }
}

class TeamViewModel : BaseCrudViewModel<Team>() {
    val state: StateFlow<UiState<Team>> = TeamRepo.observe()
        .catch { emit(emptyList<Team>()) }
        .map { list: List<Team> -> UiState(list, loading = false) }
        .stateIn(viewModelScope, SharingStarted.WhileSubscribed(5000), UiState(loading = true))

    fun add(t: Team) = viewModelScope.launch { runOp("Equipe inscrita") { TeamRepo.add(t) } }
    fun update(t: Team) = viewModelScope.launch { runOp("Equipe atualizada") { TeamRepo.update(t) } }
    fun delete(id: String) = viewModelScope.launch { runOp("Equipe excluida") { TeamRepo.delete(id) } }
}

class ScoreViewModel : BaseCrudViewModel<Score>() {
    val state: StateFlow<UiState<Score>> = ScoreRepo.observe()
        .catch { emit(emptyList<Score>()) }
        .map { list: List<Score> -> UiState(list, loading = false) }
        .stateIn(viewModelScope, SharingStarted.WhileSubscribed(5000), UiState(loading = true))

    fun add(s: Score) = viewModelScope.launch { runOp("Pontuacao lancada") { ScoreRepo.add(s) } }
    fun update(s: Score) = viewModelScope.launch { runOp("Pontuacao atualizada") { ScoreRepo.update(s) } }
    fun delete(id: String) = viewModelScope.launch { runOp("Pontuacao excluida") { ScoreRepo.delete(id) } }
}
