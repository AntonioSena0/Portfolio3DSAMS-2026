package com.example.escape_room_app.ui.screens

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.EmojiEvents
import androidx.compose.material3.AlertDialog
import androidx.compose.material3.DropdownMenuItem
import androidx.compose.material3.ElevatedCard
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.ExposedDropdownMenuBox
import androidx.compose.material3.ExposedDropdownMenuDefaults
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.SnackbarHost
import androidx.compose.material3.SnackbarHostState
import androidx.compose.material3.Switch
import androidx.compose.material3.Text
import androidx.compose.material3.TextButton
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableIntStateOf
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.unit.dp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.example.escape_room_app.data.model.Score
import com.example.escape_room_app.ui.components.CardActionsRow
import com.example.escape_room_app.ui.components.ConfirmDeleteDialog
import com.example.escape_room_app.ui.components.EmptyState
import com.example.escape_room_app.ui.components.ScreenSubtitle
import com.example.escape_room_app.ui.viewmodel.ScoreViewModel
import com.example.escape_room_app.ui.viewmodel.StationViewModel
import com.example.escape_room_app.ui.viewmodel.TeamViewModel

@Composable
fun ScoresScreen(
    scoreVm: ScoreViewModel = viewModel(),
    teamVm: TeamViewModel = viewModel(),
    stationVm: StationViewModel = viewModel(),
    addTick: Int = 0
) {
    val state by scoreVm.state.collectAsState()
    val teams by teamVm.state.collectAsState()
    val stations by stationVm.state.collectAsState()
    val msg by scoreVm.msg.collectAsState()
    val snack = remember { SnackbarHostState() }
    var showDialog by remember { mutableStateOf(false) }
    var editing: Score? by remember { mutableStateOf(null) }
    var toDelete: Score? by remember { mutableStateOf(null) }
    var lastTick by remember { mutableIntStateOf(addTick) }

    LaunchedEffect(msg) { msg?.let { snack.showSnackbar(it); scoreVm.clearMsg() } }
    LaunchedEffect(addTick) {
        if (addTick != lastTick) {
            lastTick = addTick
            editing = null
            showDialog = true
        }
    }

    val ranking = remember(state.items) {
        state.items.groupBy { it.equipeNome.ifBlank { it.equipeId } }
            .map { (nome, list) -> nome to list.sumOf { it.pontos } }
            .sortedByDescending { it.second }
    }

    Box(Modifier.fillMaxSize()) {
        Column(Modifier.fillMaxSize()) {
            ScreenSubtitle("Quadro de lideres do caso.", state.items.size)
            if (ranking.isNotEmpty()) {
                ElevatedCard(Modifier.fillMaxWidth().padding(16.dp)) {
                    Column(Modifier.padding(16.dp), verticalArrangement = Arrangement.spacedBy(4.dp)) {
                        Text("RANKING", style = MaterialTheme.typography.labelLarge, fontWeight = FontWeight.Bold)
                        ranking.take(5).forEachIndexed { i, (nome, pts) ->
                            Text("${i + 1}. $nome - $pts pts", style = MaterialTheme.typography.bodyMedium)
                        }
                    }
                }
            }
            if (state.items.isEmpty() && !state.loading && ranking.isEmpty()) {
                EmptyState(Icons.Default.EmojiEvents, "Sem pontuacao", "Lance a primeira pontuacao.", "Nova pontuacao") {
                    editing = null; showDialog = true
                }
            } else {
                LazyColumn(Modifier.fillMaxSize()) {
                    items(state.items, key = { it.id }) { s ->
                        ElevatedCard(Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 6.dp)) {
                            Column(Modifier.padding(16.dp)) {
                                Row(Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.SpaceBetween) {
                                    Text(
                                        s.equipeNome.ifBlank { "Equipe" },
                                        style = MaterialTheme.typography.titleMedium,
                                        fontWeight = FontWeight.SemiBold,
                                        modifier = Modifier.weight(1f)
                                    )
                                    Text(
                                        "${s.pontos} pts",
                                        style = MaterialTheme.typography.titleMedium,
                                        fontWeight = FontWeight.Bold,
                                        color = MaterialTheme.colorScheme.primary
                                    )
                                }
                                Text(
                                    s.estacaoNome.ifBlank { "Estacao" },
                                    style = MaterialTheme.typography.bodyMedium,
                                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                                    modifier = Modifier.padding(top = 4.dp)
                                )
                                Text(
                                    "${s.tempoResolucaoMin} min - ${s.pistasUsadas} pistas - ${if (s.concluida) "Concluida" else "Pendente"}",
                                    style = MaterialTheme.typography.labelMedium,
                                    modifier = Modifier.padding(top = 2.dp)
                                )
                                CardActionsRow({ editing = s; showDialog = true }, { toDelete = s })
                            }
                        }
                    }
                }
            }
        }
        SnackbarHost(snack, Modifier.align(Alignment.BottomCenter))
    }

    if (showDialog) {
        ScoreDialog(
            initial = editing,
            teamMap = teams.items.associate { it.id to it.nome },
            stationMap = stations.items.associate { it.id to it.nome },
            onDismiss = { showDialog = false },
            onSave = { s -> if (s.id.isBlank()) scoreVm.add(s) else scoreVm.update(s); showDialog = false }
        )
    }
    toDelete?.let { s -> ConfirmDeleteDialog("${s.equipeNome} @ ${s.estacaoNome}", { toDelete = null }, { scoreVm.delete(s.id); toDelete = null }) }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
private fun ScoreDialog(
    initial: Score?,
    teamMap: Map<String, String>,
    stationMap: Map<String, String>,
    onDismiss: () -> Unit,
    onSave: (Score) -> Unit
) {
    var equipeId by remember { mutableStateOf(initial?.equipeId ?: teamMap.keys.firstOrNull().orEmpty()) }
    var estacaoId by remember { mutableStateOf(initial?.estacaoId ?: stationMap.keys.firstOrNull().orEmpty()) }
    var pontos by remember { mutableStateOf((initial?.pontos ?: 100).toString()) }
    var tempo by remember { mutableStateOf((initial?.tempoResolucaoMin ?: 10).toString()) }
    var pistas by remember { mutableStateOf((initial?.pistasUsadas ?: 0).toString()) }
    var concluida by remember { mutableStateOf(initial?.concluida ?: true) }
    var teamExp by remember { mutableStateOf(false) }
    var stationExp by remember { mutableStateOf(false) }

    AlertDialog(
        onDismissRequest = onDismiss,
        title = { Text(if (initial == null) "Nova pontuacao" else "Editar pontuacao") },
        text = {
            Column(Modifier.verticalScroll(rememberScrollState()), verticalArrangement = Arrangement.spacedBy(8.dp)) {
                ExposedDropdownMenuBox(expanded = teamExp, onExpandedChange = { teamExp = !teamExp }) {
                    OutlinedTextField(
                        teamMap[equipeId] ?: "Selecione a equipe", {}, readOnly = true,
                        label = { Text("Equipe*") }, trailingIcon = { ExposedDropdownMenuDefaults.TrailingIcon(teamExp) },
                        modifier = Modifier.menuAnchor().fillMaxWidth()
                    )
                    ExposedDropdownMenu(expanded = teamExp, onDismissRequest = { teamExp = false }) {
                        teamMap.forEach { (id, nome) -> DropdownMenuItem(text = { Text(nome) }, onClick = { equipeId = id; teamExp = false }) }
                    }
                }
                ExposedDropdownMenuBox(expanded = stationExp, onExpandedChange = { stationExp = !stationExp }) {
                    OutlinedTextField(
                        stationMap[estacaoId] ?: "Selecione a estacao", {}, readOnly = true,
                        label = { Text("Estacao*") }, trailingIcon = { ExposedDropdownMenuDefaults.TrailingIcon(stationExp) },
                        modifier = Modifier.menuAnchor().fillMaxWidth()
                    )
                    ExposedDropdownMenu(expanded = stationExp, onDismissRequest = { stationExp = false }) {
                        stationMap.forEach { (id, nome) -> DropdownMenuItem(text = { Text(nome) }, onClick = { estacaoId = id; stationExp = false }) }
                    }
                }
                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                    OutlinedTextField(pontos, { pontos = it.filter { c -> c.isDigit() || c == '-' } }, label = { Text("Pontos*") },
                        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number), modifier = Modifier.weight(1f))
                    OutlinedTextField(tempo, { tempo = it.filter(Char::isDigit) }, label = { Text("Min*") },
                        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number), modifier = Modifier.weight(1f))
                    OutlinedTextField(pistas, { pistas = it.filter(Char::isDigit) }, label = { Text("Pistas") },
                        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number), modifier = Modifier.weight(1f))
                }
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Switch(concluida, { concluida = it }); Text("Desafio concluido", modifier = Modifier.padding(start = 8.dp))
                }
            }
        },
        confirmButton = {
            TextButton(onClick = {
                if (equipeId.isBlank() || estacaoId.isBlank()) return@TextButton
                onSave(
                    (initial ?: Score()).copy(
                        equipeId = equipeId, equipeNome = teamMap[equipeId].orEmpty(),
                        estacaoId = estacaoId, estacaoNome = stationMap[estacaoId].orEmpty(),
                        pontos = pontos.toIntOrNull() ?: 0, tempoResolucaoMin = tempo.toIntOrNull() ?: 0,
                        pistasUsadas = pistas.toIntOrNull() ?: 0, concluida = concluida
                    )
                )
            }) { Text("Salvar") }
        },
        dismissButton = { TextButton(onClick = onDismiss) { Text("Cancelar") } }
    )
}
