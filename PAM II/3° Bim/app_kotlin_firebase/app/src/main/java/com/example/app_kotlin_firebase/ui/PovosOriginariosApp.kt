package com.example.app_kotlin_firebase.ui

import androidx.annotation.DrawableRes
import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.navigationBarsPadding
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.statusBarsPadding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.itemsIndexed
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.text.KeyboardActions
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.outlined.ArrowForward
import androidx.compose.material.icons.outlined.Close
import androidx.compose.material.icons.outlined.Email
import androidx.compose.material.icons.outlined.Lock
import androidx.compose.material.icons.automirrored.outlined.Logout
import androidx.compose.material.icons.outlined.Visibility
import androidx.compose.material.icons.outlined.VisibilityOff
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Scaffold
import androidx.compose.material3.SnackbarHost
import androidx.compose.material3.SnackbarHostState
import androidx.compose.material3.Surface
import androidx.compose.material3.Text
import androidx.compose.material3.TextButton
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.platform.LocalFocusManager
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.text.input.KeyboardType
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.text.input.VisualTransformation
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.example.app_kotlin_firebase.R
import com.example.app_kotlin_firebase.auth.AuthDestination
import com.example.app_kotlin_firebase.auth.AuthState
import com.example.app_kotlin_firebase.auth.AuthViewModel
import com.example.app_kotlin_firebase.ui.theme.Argila
import com.example.app_kotlin_firebase.ui.theme.Areia
import com.example.app_kotlin_firebase.ui.theme.Grafite
import com.example.app_kotlin_firebase.ui.theme.Mata
import com.example.app_kotlin_firebase.ui.theme.Palha
import com.example.app_kotlin_firebase.ui.theme.Urucum

@Composable
fun PovosOriginariosApp(authViewModel: AuthViewModel = viewModel()) {
    val state by authViewModel.state.collectAsState()
    val snackbarHostState = remember { SnackbarHostState() }

    LaunchedEffect(state.errorMessage) {
        state.errorMessage?.let { message ->
            snackbarHostState.showSnackbar(message)
            authViewModel.dismissError()
        }
    }

    Scaffold(
        snackbarHost = { SnackbarHost(snackbarHostState) },
        containerColor = Palha
    ) { contentPadding ->
        Box(
            modifier = Modifier
                .fillMaxSize()
                .padding(contentPadding)
        ) {
            when (state.destination) {
                AuthDestination.LOGIN -> AuthScreen(
                    state = state,
                    isSignup = false,
                    onSubmit = authViewModel::login,
                    onChangeDestination = authViewModel::showSignup
                )

                AuthDestination.SIGNUP -> AuthScreen(
                    state = state,
                    isSignup = true,
                    onSubmit = authViewModel::signup,
                    onChangeDestination = authViewModel::showLogin
                )

                AuthDestination.HOME -> CollectionScreen(
                    email = state.email,
                    onLogout = authViewModel::logout
                )
            }
        }
    }
}

@Composable
private fun AuthScreen(
    state: AuthState,
    isSignup: Boolean,
    onSubmit: (String, String) -> Unit,
    onChangeDestination: () -> Unit
) {
    var email by remember(isSignup) { mutableStateOf("") }
    var password by remember(isSignup) { mutableStateOf("") }
    var passwordVisible by remember { mutableStateOf(false) }
    val focusManager = LocalFocusManager.current
    val action = {
        focusManager.clearFocus()
        onSubmit(email, password)
    }

    LazyColumn(
        modifier = Modifier
            .fillMaxSize()
            .background(Palha)
            .statusBarsPadding()
            .navigationBarsPadding(),
        verticalArrangement = Arrangement.SpaceBetween
    ) {
        item {
            Column(modifier = Modifier.padding(horizontal = 24.dp, vertical = 28.dp)) {
                BrandMark()
                Spacer(modifier = Modifier.height(52.dp))
                Text(
                    text = if (isSignup) "Crie seu acesso" else "Entre no acervo",
                    style = MaterialTheme.typography.displaySmall,
                    color = Grafite
                )
                Spacer(modifier = Modifier.height(10.dp))
                Text(
                    text = if (isSignup) {
                        "Cadastre-se para explorar memórias, técnicas e objetos de povos originários."
                    } else {
                        "Um acervo visual dedicado às culturas materiais indígenas do Brasil."
                    },
                    style = MaterialTheme.typography.bodyLarge,
                    color = Grafite.copy(alpha = 0.72f)
                )
                Spacer(modifier = Modifier.height(36.dp))
                OutlinedTextField(
                    value = email,
                    onValueChange = { email = it },
                    modifier = Modifier.fillMaxWidth(),
                    label = { Text("E-mail") },
                    leadingIcon = { Icon(Icons.Outlined.Email, contentDescription = null) },
                    singleLine = true,
                    keyboardOptions = KeyboardOptions(
                        keyboardType = KeyboardType.Email,
                        imeAction = ImeAction.Next
                    )
                )
                Spacer(modifier = Modifier.height(14.dp))
                OutlinedTextField(
                    value = password,
                    onValueChange = { password = it },
                    modifier = Modifier.fillMaxWidth(),
                    label = { Text("Senha") },
                    leadingIcon = { Icon(Icons.Outlined.Lock, contentDescription = null) },
                    trailingIcon = {
                        IconButton(onClick = { passwordVisible = !passwordVisible }) {
                            Icon(
                                imageVector = if (passwordVisible) Icons.Outlined.VisibilityOff else Icons.Outlined.Visibility,
                                contentDescription = if (passwordVisible) "Ocultar senha" else "Mostrar senha"
                            )
                        }
                    },
                    singleLine = true,
                    visualTransformation = if (passwordVisible) VisualTransformation.None else PasswordVisualTransformation(),
                    keyboardOptions = KeyboardOptions(
                        keyboardType = KeyboardType.Password,
                        imeAction = ImeAction.Done
                    ),
                    keyboardActions = KeyboardActions(onDone = { action() })
                )
                Spacer(modifier = Modifier.height(22.dp))
                Button(
                    onClick = action,
                    modifier = Modifier
                        .fillMaxWidth()
                        .height(56.dp),
                    enabled = !state.isLoading,
                    shape = MaterialTheme.shapes.small,
                    colors = ButtonDefaults.buttonColors(containerColor = Urucum)
                ) {
                    if (state.isLoading) {
                        CircularProgressIndicator(
                            modifier = Modifier.size(22.dp),
                            color = Color.White,
                            strokeWidth = 2.dp
                        )
                    } else {
                        Text(if (isSignup) "CRIAR CONTA" else "ENTRAR", fontWeight = FontWeight.Bold)
                        Spacer(modifier = Modifier.width(12.dp))
                        Icon(Icons.AutoMirrored.Outlined.ArrowForward, contentDescription = null)
                    }
                }
                if (!state.firebaseConfigured) {
                    Spacer(modifier = Modifier.height(16.dp))
                    FirebaseNotice()
                }
            }
        }
        item {
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .background(Argila)
                    .padding(horizontal = 20.dp, vertical = 18.dp),
                horizontalArrangement = Arrangement.Center,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text(
                    text = if (isSignup) "Já possui uma conta?" else "Primeira visita?",
                    color = Palha
                )
                TextButton(onClick = onChangeDestination) {
                    Text(
                        text = if (isSignup) "Entrar" else "Cadastre-se",
                        color = Areia,
                        fontWeight = FontWeight.Bold
                    )
                }
            }
        }
    }
}

@Composable
private fun BrandMark() {
    Row(verticalAlignment = Alignment.CenterVertically) {
        Box(
            modifier = Modifier
                .size(44.dp)
                .background(Urucum),
            contentAlignment = Alignment.Center
        ) {
            Text("P.O", color = Color.White, fontWeight = FontWeight.Black, fontSize = 13.sp)
        }
        Spacer(modifier = Modifier.width(12.dp))
        Column {
            Text("POVOS", fontWeight = FontWeight.Black, letterSpacing = 2.sp, color = Grafite)
            Text("ORIGINÁRIOS", fontSize = 11.sp, letterSpacing = 1.4.sp, color = Grafite.copy(alpha = 0.7f))
        }
    }
}

@Composable
private fun FirebaseNotice() {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .background(Areia)
            .padding(14.dp),
        verticalAlignment = Alignment.Top
    ) {
        Icon(Icons.Outlined.Close, contentDescription = null, tint = Urucum)
        Spacer(modifier = Modifier.width(10.dp))
        Text(
            "Firebase ainda não configurado. Siga o tutorial do README e adicione app/google-services.json.",
            style = MaterialTheme.typography.bodySmall,
            color = Grafite
        )
    }
}

@OptIn(ExperimentalMaterial3Api::class)
@Composable
private fun CollectionScreen(email: String, onLogout: () -> Unit) {
    val collection = remember {
        listOf(
            CollectionItem(
                title = "Vaso de cerâmica Marajoara",
                people = "Cultura Marajoara",
                period = "400–1400 · Ilha de Marajó, Pará",
                description = "A cerâmica registra grafismos, técnicas de modelagem e modos de vida desenvolvidos por sociedades que ocuparam a região amazônica.",
                imageResource = R.drawable.vaso_marajoara,
                credit = "Foto: Dornicke / Wikimedia Commons · CC BY-SA 4.0"
            ),
            CollectionItem(
                title = "Cestaria Paresi",
                people = "Povo Haliti-Paresi",
                period = "Mato Grosso",
                description = "Tramas de fibras vegetais unem conhecimento ambiental, domínio técnico e formas de expressão transmitidas entre gerações.",
                imageResource = R.drawable.cestaria_paresi,
                credit = "Foto: Daderot / Wikimedia Commons · CC0"
            )
        )
    }

    Scaffold(
        topBar = {
            TopAppBar(
                title = { BrandMark() },
                actions = {
                    IconButton(onClick = onLogout) {
                        Icon(Icons.AutoMirrored.Outlined.Logout, contentDescription = "Sair", tint = Grafite)
                    }
                },
                colors = TopAppBarDefaults.topAppBarColors(containerColor = Palha)
            )
        },
        containerColor = Palha
    ) { contentPadding ->
        LazyColumn(
            modifier = Modifier
                .fillMaxSize()
                .padding(contentPadding)
        ) {
            item {
                Column(modifier = Modifier.padding(horizontal = 24.dp, vertical = 24.dp)) {
                    Text(
                        "ACERVO 01",
                        style = MaterialTheme.typography.labelLarge,
                        color = Urucum,
                        letterSpacing = 2.sp
                    )
                    Spacer(modifier = Modifier.height(12.dp))
                    Text(
                        "Memória viva em objetos",
                        style = MaterialTheme.typography.displaySmall,
                        color = Grafite
                    )
                    Spacer(modifier = Modifier.height(12.dp))
                    Text(
                        "Artefatos não são peças isoladas: carregam relações, territórios, conhecimentos e continuidade cultural.",
                        style = MaterialTheme.typography.bodyLarge,
                        color = Grafite.copy(alpha = 0.72f)
                    )
                    Spacer(modifier = Modifier.height(18.dp))
                    Text(
                        "Sessão autenticada como $email",
                        style = MaterialTheme.typography.bodySmall,
                        color = Mata
                    )
                }
                HorizontalDivider(color = Grafite.copy(alpha = 0.18f))
            }
            itemsIndexed(collection) { index, item ->
                CollectionEntry(index + 1, item)
                HorizontalDivider(color = Grafite.copy(alpha = 0.18f))
            }
            item {
                Text(
                    "Este projeto apresenta uma seleção educativa. A diversidade dos povos indígenas não pode ser reduzida a uma única cultura ou período histórico.",
                    modifier = Modifier.padding(24.dp),
                    style = MaterialTheme.typography.bodySmall,
                    color = Grafite.copy(alpha = 0.64f)
                )
            }
        }
    }
}

@Composable
private fun CollectionEntry(number: Int, item: CollectionItem) {
    Column(modifier = Modifier.fillMaxWidth()) {
        Image(
            painter = painterResource(item.imageResource),
            contentDescription = item.title,
            modifier = Modifier
                .fillMaxWidth()
                .height(280.dp),
            contentScale = ContentScale.Crop
        )
        Column(modifier = Modifier.padding(24.dp)) {
            Row(verticalAlignment = Alignment.CenterVertically) {
                Surface(
                    modifier = Modifier.size(34.dp),
                    shape = CircleShape,
                    color = Mata
                ) {
                    Box(contentAlignment = Alignment.Center) {
                        Text(number.toString().padStart(2, '0'), color = Color.White, fontSize = 11.sp)
                    }
                }
                Spacer(modifier = Modifier.width(12.dp))
                Text(
                    item.people.uppercase(),
                    style = MaterialTheme.typography.labelLarge,
                    letterSpacing = 1.2.sp,
                    color = Urucum,
                    maxLines = 1,
                    overflow = TextOverflow.Ellipsis
                )
            }
            Spacer(modifier = Modifier.height(16.dp))
            Text(item.title, style = MaterialTheme.typography.headlineSmall, color = Grafite)
            Spacer(modifier = Modifier.height(4.dp))
            Text(item.period, style = MaterialTheme.typography.labelLarge, color = Mata)
            Spacer(modifier = Modifier.height(12.dp))
            Text(item.description, style = MaterialTheme.typography.bodyLarge, color = Grafite.copy(alpha = 0.78f))
            Spacer(modifier = Modifier.height(14.dp))
            Text(item.credit, style = MaterialTheme.typography.bodySmall, color = Grafite.copy(alpha = 0.55f))
        }
    }
}

private data class CollectionItem(
    val title: String,
    val people: String,
    val period: String,
    val description: String,
    @param:DrawableRes val imageResource: Int,
    val credit: String
)
