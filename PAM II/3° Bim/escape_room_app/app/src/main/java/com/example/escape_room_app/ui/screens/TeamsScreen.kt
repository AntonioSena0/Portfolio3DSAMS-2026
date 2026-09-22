package com.example.escape_room_app.ui.screens

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Group
import androidx.compose.material3.AlertDialog
import androidx.compose.material3.ElevatedCard
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
import androidx.compose.ui.unit.dp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.example.escape_room_app.data.model.Team
import com.example.escape_room_app.ui.components.CardActionsRow
import com.example.escape_room_app.ui.components.ConfirmDeleteDialog
import com.example.escape_room_app.ui.components.EmptyState
import com.example.escape_room_app.ui.components.ScreenSubtitle
import com.example.escape_room_app.ui.components.SearchField
import com.example.escape_room_app.ui.viewmodel.TeamViewModel

@Composable
fun TeamsScreen(vm: TeamViewModel = viewModel(), addTick: Int = 0) {
    val state by vm.state.collectAsState()
    val msg by vm.msg.collectAsState()
    val snack = remember { SnackbarHostState() }
    var query by remember { mutableStateOf("") }
    var showDialog by remember { mutableStateOf(false) }
    var editing: Team? by remember { mutableStateOf(null) }
    var toDelete: Team? by remember { mutableStateOf(null) }
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
        query.isBlank() || it.nome.contains(query, true) || it.turma.contains(query, true)
    }

    Box(Modifier.fillMaxSize()) {
        Column(Modifier.fillMaxSize()) {
            ScreenSubtitle("Detetives inscritos no desafio.", filtered.size)
            SearchField(query, { query = it }, "Buscar equipe ou turma...")
            if (filtered.isEmpty() && !state.loading) {
                EmptyState(Icons.Default.Group, "Nenhuma equipe", "Inscreva a primeira equipe.", "Nova equipe") {
                    editing = null; showDialog = true
                }
            } else {
                LazyColumn(Modifier.fillMaxSize()) {
                    items(filtered, key = { it.id }) { t ->
                        ElevatedCard(Modifier.fillMaxWidth().padding(horizontal = 16.dp, vertical = 6.dp)) {
                            Column(Modifier.padding(16.dp)) {
                                Text(t.nome, style = MaterialTheme.typography.titleMedium, fontWeight = FontWeight.SemiBold)
                                Text(
                                    "Turma: ${t.turma.ifBlank { "-" }}",
                                    style = MaterialTheme.typography.bodyMedium,
                                    color = MaterialTheme.colorScheme.onSurfaceVariant,
                                    modifier = Modifier.padding(top = 4.dp)
                                )
                                Text(
                                    t.membros.ifBlank { "Sem membros" },
                                    style = MaterialTheme.typography.bodyMedium,
                                    modifier = Modifier.padding(top = 2.dp)
                                )
                                if (t.lider.isNotBlank()) {
                                    Text(
                                        "Lider: ${t.lider}",
                                        style = MaterialTheme.typography.labelLarge,
                                        modifier = Modifier.padding(top = 4.dp)
                                    )
                                }
                                CardActionsRow({ editing = t; showDialog = true }, { toDelete = t })
                            }
                        }
                    }
                }
            }
        }
        SnackbarHost(snack, Modifier.align(Alignment.BottomCenter))
    }

    if (showDialog) {
        TeamDialog(
            initial = editing,
            onDismiss = { showDialog = false },
            onSave = { t -> if (t.id.isBlank()) vm.add(t) else vm.update(t); showDialog = false }
        )
    }
    toDelete?.let { t -> ConfirmDeleteDialog(t.nome, { toDelete = null }, { vm.delete(t.id); toDelete = null }) }
}

@Composable
private fun TeamDialog(initial: Team?, onDismiss: () -> Unit, onSave: (Team) -> Unit) {
    var nome by remember { mutableStateOf(initial?.nome ?: "") }
    var turma by remember { mutableStateOf(initial?.turma ?: "") }
    var membros by remember { mutableStateOf(initial?.membros ?: "") }
    var lider by remember { mutableStateOf(initial?.lider ?: "") }

    AlertDialog(
        onDismissRequest = onDismiss,
        title = { Text(if (initial == null) "Nova equipe" else "Editar equipe") },
        text = {
            Column(Modifier.verticalScroll(rememberScrollState()), verticalArrangement = Arrangement.spacedBy(8.dp)) {
                OutlinedTextField(nome, { nome = it }, label = { Text("Nome da equipe*") }, singleLine = true)
                OutlinedTextField(turma, { turma = it }, label = { Text("Turma / Escola") }, singleLine = true)
                OutlinedTextField(membros, { membros = it }, label = { Text("Membros (separados por virgula)") }, minLines = 2)
                OutlinedTextField(lider, { lider = it }, label = { Text("Lider da equipe") }, singleLine = true)
                Spacer(Modifier.height(2.dp))
            }
        },
        confirmButton = {
            TextButton(onClick = {
                if (nome.isBlank()) return@TextButton
                onSave((initial ?: Team()).copy(nome = nome.trim(), turma = turma.trim(), membros = membros.trim(), lider = lider.trim()))
            }) { Text("Salvar") }
        },
        dismissButton = { TextButton(onClick = onDismiss) { Text("Cancelar") } }
    )
}
