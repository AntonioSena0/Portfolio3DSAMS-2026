package com.example.logcatapp

import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TextField
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.layout.ContentScale
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.example.logcatapp.ui.theme.LogCatAppTheme
import org.w3c.dom.Text

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            LogCatAppTheme {
                Scaffold(modifier = Modifier.fillMaxSize()) { innerPadding ->
                    App()
                }
            }
        }
    }
}

@Composable
fun App() {

    var nome by remember { mutableStateOf("") }
    var filme by remember { mutableStateOf("") }

    Column(
        modifier = Modifier
            .fillMaxSize()
            .fillMaxWidth()
            .fillMaxHeight(),
        verticalArrangement = Arrangement.Center,
        horizontalAlignment = Alignment.CenterHorizontally
    ) {

        Text(
            text = "Seja Bem Vindo",
            fontSize = 40.sp,
            fontWeight = FontWeight.Bold
        )

        val image = painterResource(R.drawable.logo)

        Image(
            painter = image,
            contentDescription = "Logo",
            contentScale = ContentScale.Crop,
            modifier = Modifier
                .width(200.dp)
                .height(200.dp),
            alpha = 0.5f
        )

        Spacer(Modifier.padding(10.dp))

        TextField(
            value = nome,
            onValueChange = {novoNome ->
                nome = novoNome
            },
            label = {Text("Insira o seu nome")}
        )

        Spacer(Modifier.padding(10.dp))

        TextField(
            value = filme,
            onValueChange = {novoFilme ->
                filme = novoFilme
            },
            label = {Text("Insira o nome do filme")}
        )

        Spacer(Modifier.padding(20.dp))

        val modifier = Modifier
            .padding(10.dp)
            .width(200.dp)
            .height(40.dp)

        Text(
            text = "Esse filme é?",
            fontSize = 20.sp,
            fontWeight = FontWeight.Bold
        )

        Button(
            onClick = {
                Log.i("MainActivity", "$nome, avaliou o filme, $filme, como ótimo")
            },
            enabled = nome.isNotBlank() && filme.isNotBlank(),
            colors = ButtonDefaults.buttonColors(
                containerColor = Color(0xFF2E7D32),
                contentColor = Color.White
            ),
            modifier = modifier
        ) {
            Text("Ótimo")
        }

        Button(
            onClick = {
                Log.i("MainActivity", "$nome, avaliou o filme, $filme, como bom")
            },
            enabled = nome.isNotBlank() && filme.isNotBlank(),
            colors = ButtonDefaults.buttonColors(
                containerColor = Color(0xFF1976D2),
                contentColor = Color.White
            ),
            modifier = modifier
        ) {
            Text("Bom")
        }

        Button(
            onClick = {
                Log.w("MainActivity", "$nome, avaliou o filme, $filme, como razoável")
            },
            enabled = nome.isNotBlank() && filme.isNotBlank(),
            colors = ButtonDefaults.buttonColors(
                containerColor = Color(0xFFF57C00),
                contentColor = Color.White
            ),
            modifier = modifier
        ) {
            Text("Razoável")
        }

        Button(
            onClick = {
                Log.e("MainActivity", "$nome, avaliou o filme, $filme, como ruim")
            },
            enabled = nome.isNotBlank() && filme.isNotBlank(),
            colors = ButtonDefaults.buttonColors(
                containerColor = Color(0xFFC62828),
                contentColor = Color.White
            ),
            modifier = modifier
        ) {
            Text("Ruim")
        }

    }

}

@Preview(showBackground = true)
@Composable
fun GreetingPreview() {
    LogCatAppTheme {
        App()
    }
}