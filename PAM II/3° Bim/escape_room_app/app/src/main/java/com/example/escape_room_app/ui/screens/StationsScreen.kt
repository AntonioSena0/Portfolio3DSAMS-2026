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
import androidx.compose.material.icons.filled.Map
import androidx.compose.material3.AlertDialog
import androidx.compose.material3.AssistChip
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
import com.example.escape_room_app.data.model.DIFICULDADES
import com.example.escape_room_app.data.model.DISCIPLINAS
import com.example.escape_room_app.data.model.Station
import com.example.escape_room_app.ui.components.CardActionsRow
import com.example.escape_room_app.ui.components.ConfirmDeleteDialog
import com.example.escape_room_app.ui.components.EmptyState
import com.example.escape_room_app.ui.components.ScreenSubtitle
import com.example.escape_room_app.ui.components.SearchField
import com.example.escape_room_app.ui.viewmodel.StationViewModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun StationsScreen(vm: StationViewModel = viewModel(), addTick: Int = 0) {
    val state by vm.state.collectAsState()
    val msg by vm.msg.collectAsState()
    val snack = remember { SnackbarHostState() }
    var query by remember { mutableStateOf("") }
    var showDialog by remember { mutableStateOf(false) }
    var editing: Station? by remember { mutableStateOf(null) }
    var toDelete: Station? by remember { mutableStateOf(null) }
    var lastTick by remember { mutableIntStateOf(addTick) }

    LaunchedEffect(msg) { msg?.let { snack.showSnackbar(it); vm.clearMsg() } }
    LaunchedEffect(addTick) {
        if (addTick != lastTick) {
            lastTick = addTick
            editing = null
            showDialog = true
        }
    }

    val filtered = state.items.filter {
        query.isBlank() || it.nome.contains(query, true) || it.disciplina.contains(query, true)
    }

    Box(Modifier.fillMaxSize()) {
        Column(Modifier.fillMaxSize()) {
            ScreenSubtitle("Roteiro de salas do escape educacional.", filtered.size)
            SearchField(query, { query = it }, "Buscar estacao ou disciplina...")
            if (filtered.isEmpty() && !state.loading) {
                EmptyState(
                    Icons.Default.Map, "Nenhuma estacao",
                    "Cadastre a primeira sala do roteiro.",
                    "Nova estacao"
                ) { editing = null; showDialog = true }
            } else {
                LazyColumn(Modifier.fillMaxSize()) {
                    items(filtered, key = { it.id }) { s ->
                        ElevatedCard(Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 6.dp)) {
                            Column(Modifier.padding(16.dp)) {
                                Row(Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.SpaceBetween) {
                                    Text(
                                        "#${s.ordem} ${s.nome}",
                                        style = MaterialTheme.typography.titleMedium,
                                        fontWeight = FontWeight.SemiBold,
                                        modifier = Modifier.weight(1f)
                                    )
                                    Text(
                                        if (s.ativa) "Ativa" else "Inativa",
                                        style = MaterialTheme.typography.labelMedium,
                                        color = if (s.ativa) MaterialTheme.colorScheme.primary else MaterialTheme.colorScheme.outline
                                    )
                                }
                                Text(
                                    s.descricao,
                                    style = MaterialTheme.typography.bodyMedium,
                                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                                    modifier = Modifier.padding(vertical = 6.dp)
                                )
                                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                                    AssistChip(onClick = {}, label = { Text(s.disciplina.ifBlank { "Geral" }) })
                                    AssistChip(onClick = {}, label = { Text(s.dificuldade) })
                                    AssistChip(onClick = {}, label = { Text("${s.tempoLimiteMin} min") })
                                }
                                CardActionsRow(
                                    onEdit = { editing = s; showDialog = true },
                                    onDelete = { toDelete = s }
                                )
                            }
                        }
                    }
                }
            }
        }
        SnackbarHost(snack, Modifier.align(Alignment.BottomCenter))
    }

    if (showDialog) {
        StationDialog(
            initial = editing,
            onDismiss = { showDialog = false },
            onSave = { s ->
                if (s.id.isBlank()) vm.add(s) else vm.update(s)
                showDialog = false
            }
        )
    }
    toDelete?.let { s ->
        ConfirmDeleteDialog(s.nome, { toDelete = null }, { vm.delete(s.id); toDelete = null })
    }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
private fun StationDialog(initial: Station?, onDismiss: () -> Unit, onSave: (Station) -> Unit) {
    var nome by remember { mutableStateOf(initial?.nome ?: "") }
    var descricao by remember { mutableStateOf(initial?.descricao ?: "") }
    var disciplina by remember { mutableStateOf(initial?.disciplina?.ifBlank { "Geral" } ?: "Geral") }
    var dificuldade by remember { mutableStateOf(initial?.dificuldade ?: "Fácil") }
    var tempo by remember { mutableStateOf((initial?.tempoLimiteMin ?: 15).toString()) }
    var ordem by remember { mutableStateOf((initial?.ordem ?: 1).toString()) }
    var ativa by remember { mutableStateOf(initial?.ativa ?: true) }
    var discExp by remember { mutableStateOf(false) }
    var difExp by remember { mutableStateOf(false) }

    AlertDialog(
        onDismissRequest = onDismiss,
        title = { Text(if (initial == null) "Nova estacao" else "Editar estacao") },
        text = {
            Column(Modifier.verticalScroll(rememberScrollState()), verticalArrangement = Arrangement.spacedBy(8.dp)) {
                OutlinedTextField(nome, { nome = it }, label = { Text("Nome da estacao*") }, singleLine = true)
                OutlinedTextField(descricao, { descricao = it }, label = { Text("Enigma / descricao*") }, minLines = 2)
                ExposedDropdownMenuBox(expanded = discExp, onExpandedChange = { discExp = !discExp }) {
                    OutlinedTextField(disciplina, {}, readOnly = true, label = { Text("Disciplina") },
                        trailingIcon = { ExposedDropdownMenuDefaults.TrailingIcon(discExp) },
                        modifier = Modifier.menuAnchor().fillMaxWidth())
                    ExposedDropdownMenu(expanded = discExp, onDismissRequest = { discExp = false }) {
                        DISCIPLINAS.forEach { d ->
                            DropdownMenuItem(text = { Text(d) }, onClick = { disciplina = d; discExp = false })
                        }
                    }
                }
                ExposedDropdownMenuBox(expanded = difExp, onExpandedChange = { difExp = !difExp }) {
                    OutlinedTextField(dificuldade, {}, readOnly = true, label = { Text("Dificuldade") },
                        trailingIcon = { ExposedDropdownMenuDefaults.TrailingIcon(difExp) },
                        modifier = Modifier.menuAnchor().fillMaxWidth())
                    ExposedDropdownMenu(expanded = difExp, onDismissRequest = { difExp = false }) {
                        DIFICULDADES.forEach { d ->
                            DropdownMenuItem(text = { Text(d) }, onClick = { dificuldade = d; difExp = false })
                        }
                    }
                }
                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                    OutlinedTextField(tempo, { tempo = it.filter(Char::isDigit) }, label = { Text("Min*") },
                        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number), modifier = Modifier.weight(1f))
                    OutlinedTextField(ordem, { ordem = it.filter(Char::isDigit) }, label = { Text("Ordem*") },
                        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number), modifier = Modifier.weight(1f))
                }
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Switch(ativa, { ativa = it }); Text("Estacao ativa", modifier = Modifier.padding(start = 8.dp))
                }
            }
        },
        confirmButton = {
            TextButton(onClick = {
                if (nome.isBlank() || descricao.isBlank()) return@TextButton
                onSave(
                    (initial ?: Station()).copy(
                        nome = nome.trim(), descricao = descricao.trim(),
                        disciplina = disciplina, dificuldade = dificuldade,
                        tempoLimiteMin = tempo.toIntOrNull() ?: 15,
                        ordem = ordem.toIntOrNull() ?: 1, ativa = ativa
                    )
                )
            }) { Text("Salvar") }
        },
        dismissButton = { TextButton(onClick = onDismiss) { Text("Cancelar") } }
    )
}
