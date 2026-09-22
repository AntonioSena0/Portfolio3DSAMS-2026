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
import androidx.compose.material.icons.filled.Lightbulb
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
import com.example.escape_room_app.data.model.Hint
import com.example.escape_room_app.ui.components.CardActionsRow
import com.example.escape_room_app.ui.components.ConfirmDeleteDialog
import com.example.escape_room_app.ui.components.EmptyState
import com.example.escape_room_app.ui.components.ScreenSubtitle
import com.example.escape_room_app.ui.components.SearchField
import com.example.escape_room_app.ui.viewmodel.HintViewModel
import com.example.escape_room_app.ui.viewmodel.StationViewModel

@Composable
fun HintsScreen(
    hintVm: HintViewModel = viewModel(),
    stationVm: StationViewModel = viewModel(),
    addTick: Int = 0
) {
    val state by hintVm.state.collectAsState()
    val stations by stationVm.state.collectAsState()
    val msg by hintVm.msg.collectAsState()
    val snack = remember { SnackbarHostState() }
    var query by remember { mutableStateOf("") }
    var showDialog by remember { mutableStateOf(false) }
    var editing: Hint? by remember { mutableStateOf(null) }
    var toDelete: Hint? by remember { mutableStateOf(null) }
    var lastTick by remember { mutableIntStateOf(addTick) }

    LaunchedEffect(msg) { msg?.let { snack.showSnackbar(it); hintVm.clearMsg() } }
    LaunchedEffect(addTick) {
        if (addTick != lastTick) {
            lastTick = addTick
            editing = null
            showDialog = true
        }
    }

    val stationName: (String) -> String = { id -> stations.items.find { it.id == id }?.nome ?: "-" }
    val filtered = state.items.filter {
        query.isBlank() || it.titulo.contains(query, true) || it.texto.contains(query, true) ||
                stationName(it.estacaoId).contains(query, true)
    }

    Box(Modifier.fillMaxSize()) {
        Column(Modifier.fillMaxSize()) {
            ScreenSubtitle("Ajudas compradas com pontos.", filtered.size)
            SearchField(query, { query = it }, "Buscar pista ou estacao...")
            if (filtered.isEmpty() && !state.loading) {
                EmptyState(Icons.Default.Lightbulb, "Nenhuma pista", "Registre a primeira pista do roteiro.", "Nova pista") {
                    editing = null; showDialog = true
                }
            } else {
                LazyColumn(Modifier.fillMaxSize()) {
                    items(filtered, key = { it.id }) { h ->
                        ElevatedCard(Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 6.dp)) {
                            Column(Modifier.padding(16.dp)) {
                                Row(Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.SpaceBetween) {
                                    Text(
                                        "#${h.ordem} ${h.titulo}",
                                        style = MaterialTheme.typography.titleMedium,
                                        fontWeight = FontWeight.SemiBold,
                                        modifier = Modifier.weight(1f)
                                    )
                                    Text(
                                        "-${h.custoPontos} pts",
                                        style = MaterialTheme.typography.labelLarge,
                                        color = MaterialTheme.colorScheme.primary
                                    )
                                }
                                Text(
                                    stationName(h.estacaoId),
                                    style = MaterialTheme.typography.labelMedium,
                                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                                    modifier = Modifier.padding(vertical = 4.dp)
                                )
                                Text(h.texto, style = MaterialTheme.typography.bodyMedium)
                                AssistChip(
                                    onClick = {},
                                    label = { Text("Estacao: ${stationName(h.estacaoId)}") },
                                    modifier = Modifier.padding(top = 8.dp)
                                )
                                CardActionsRow({ editing = h; showDialog = true }, { toDelete = h })
                            }
                        }
                    }
                }
            }
        }
        SnackbarHost(snack, Modifier.align(Alignment.BottomCenter))
    }

    if (showDialog) {
        HintDialog(
            initial = editing,
            stationNames = stations.items.associate { it.id to it.nome },
            onDismiss = { showDialog = false },
            onSave = { h -> if (h.id.isBlank()) hintVm.add(h) else hintVm.update(h); showDialog = false }
        )
    }
    toDelete?.let { h -> ConfirmDeleteDialog(h.titulo, { toDelete = null }, { hintVm.delete(h.id); toDelete = null }) }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
private fun HintDialog(
    initial: Hint?,
    stationNames: Map<String, String>,
    onDismiss: () -> Unit,
    onSave: (Hint) -> Unit
) {
    var titulo by remember { mutableStateOf(initial?.titulo ?: "") }
    var texto by remember { mutableStateOf(initial?.texto ?: "") }
    var estacaoId by remember { mutableStateOf(initial?.estacaoId ?: stationNames.keys.firstOrNull().orEmpty()) }
    var custo by remember { mutableStateOf((initial?.custoPontos ?: 10).toString()) }
    var ordem by remember { mutableStateOf((initial?.ordem ?: 1).toString()) }
    var exp by remember { mutableStateOf(false) }

    AlertDialog(
        onDismissRequest = onDismiss,
        title = { Text(if (initial == null) "Nova pista" else "Editar pista") },
        text = {
            Column(Modifier.verticalScroll(rememberScrollState()), verticalArrangement = Arrangement.spacedBy(8.dp)) {
                ExposedDropdownMenuBox(expanded = exp, onExpandedChange = { exp = !exp }) {
                    OutlinedTextField(
                        stationNames[estacaoId] ?: "Selecione a estacao", {}, readOnly = true,
                        label = { Text("Estacao vinculada*") },
                        trailingIcon = { ExposedDropdownMenuDefaults.TrailingIcon(exp) },
                        modifier = Modifier.menuAnchor().fillMaxWidth()
                    )
                    ExposedDropdownMenu(expanded = exp, onDismissRequest = { exp = false }) {
                        if (stationNames.isEmpty()) {
                            DropdownMenuItem(text = { Text("Cadastre uma estacao primeiro") }, onClick = { exp = false })
                        }
                        stationNames.forEach { (id, nome) ->
                            DropdownMenuItem(text = { Text(nome) }, onClick = { estacaoId = id; exp = false })
                        }
                    }
                }
                OutlinedTextField(titulo, { titulo = it }, label = { Text("Titulo da pista*") }, singleLine = true)
                OutlinedTextField(texto, { texto = it }, label = { Text("Texto da pista*") }, minLines = 2)
                Row(horizontalArrangement = Arrangement.spacedBy(8.dp)) {
                    OutlinedTextField(custo, { custo = it.filter(Char::isDigit) }, label = { Text("Custo pts*") },
                        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number), modifier = Modifier.weight(1f))
                    OutlinedTextField(ordem, { ordem = it.filter(Char::isDigit) }, label = { Text("Ordem*") },
                        keyboardOptions = KeyboardOptions(keyboardType = KeyboardType.Number), modifier = Modifier.weight(1f))
                }
            }
        },
        confirmButton = {
            TextButton(onClick = {
                if (titulo.isBlank() || texto.isBlank() || estacaoId.isBlank()) return@TextButton
                onSave(
                    (initial ?: Hint()).copy(
                        titulo = titulo.trim(), texto = texto.trim(),
                        estacaoId = estacaoId, custoPontos = custo.toIntOrNull() ?: 10, ordem = ordem.toIntOrNull() ?: 1
                    )
                )
            }) { Text("Salvar") }
        },
        dismissButton = { TextButton(onClick = onDismiss) { Text("Cancelar") } }
    )
}
