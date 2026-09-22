package com.example.escape_room_app.ui.screens

import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Add
import androidx.compose.material.icons.filled.EmojiEvents
import androidx.compose.material.icons.filled.Group
import androidx.compose.material.icons.filled.Lightbulb
import androidx.compose.material.icons.filled.Map
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.FloatingActionButton
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.NavigationBar
import androidx.compose.material3.NavigationBarItem
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableIntStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.lifecycle.viewmodel.compose.viewModel
import com.example.escape_room_app.ui.viewmodel.HintViewModel
import com.example.escape_room_app.ui.viewmodel.ScoreViewModel
import com.example.escape_room_app.ui.viewmodel.StationViewModel
import com.example.escape_room_app.ui.viewmodel.TeamViewModel

private data class Tab(val title: String, val icon: ImageVector)

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun EscapeRoomApp() {
    val stationVm: StationViewModel = viewModel()
    val hintVm: HintViewModel = viewModel()
    val teamVm: TeamViewModel = viewModel()
    val scoreVm: ScoreViewModel = viewModel()

    val tabs = listOf(
        Tab("Estacoes", Icons.Filled.Map),
        Tab("Pistas", Icons.Filled.Lightbulb),
        Tab("Equipes", Icons.Filled.Group),
        Tab("Placar", Icons.Filled.EmojiEvents)
    )
    var selected by remember { mutableIntStateOf(0) }
    var addTick by remember { mutableIntStateOf(0) }

    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("EscapeEDU", fontWeight = FontWeight.SemiBold) },
                colors = TopAppBarDefaults.topAppBarColors(
                    containerColor = MaterialTheme.colorScheme.surface,
                    titleContentColor = MaterialTheme.colorScheme.onSurface
                )
            )
        },
        bottomBar = {
            NavigationBar {
                tabs.forEachIndexed { i, t ->
                    NavigationBarItem(
                        selected = selected == i,
                        onClick = { selected = i },
                        icon = { Icon(t.icon, contentDescription = t.title) },
                        label = { Text(t.title) }
                    )
                }
            }
        },
        floatingActionButton = {
            FloatingActionButton(onClick = { addTick++ }) {
                Icon(Icons.Default.Add, contentDescription = "Adicionar")
            }
        }
    ) { pad ->
        Box(Modifier.fillMaxSize().padding(pad)) {
            when (selected) {
                0 -> StationsScreen(vm = stationVm, addTick = addTick)
                1 -> HintsScreen(hintVm = hintVm, stationVm = stationVm, addTick = addTick)
                2 -> TeamsScreen(vm = teamVm, addTick = addTick)
                3 -> ScoresScreen(scoreVm = scoreVm, teamVm = teamVm, stationVm = stationVm, addTick = addTick)
            }
        }
    }
}
