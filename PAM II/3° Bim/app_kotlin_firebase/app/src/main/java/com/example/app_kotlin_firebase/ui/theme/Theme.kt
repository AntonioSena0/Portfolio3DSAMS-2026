package com.example.app_kotlin_firebase.ui.theme

import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Shapes
import androidx.compose.material3.darkColorScheme
import androidx.compose.material3.lightColorScheme
import androidx.compose.runtime.Composable
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.unit.dp

private val LightColorScheme = lightColorScheme(
    primary = Urucum,
    onPrimary = Color.White,
    secondary = Mata,
    onSecondary = Color.White,
    background = Palha,
    onBackground = Grafite,
    surface = Palha,
    onSurface = Grafite,
    surfaceVariant = Areia,
    onSurfaceVariant = Grafite,
    outline = Grafite.copy(alpha = 0.45f),
    error = Color(0xFF9D2823)
)

private val DarkColorScheme = darkColorScheme(
    primary = Color(0xFFF27A57),
    onPrimary = Noite,
    secondary = Color(0xFF90B89D),
    onSecondary = Noite,
    background = Noite,
    onBackground = Palha,
    surface = Noite,
    onSurface = Palha,
    surfaceVariant = Argila,
    onSurfaceVariant = Palha
)

private val AppShapes = Shapes(
    extraSmall = RoundedCornerShape(0.dp),
    small = RoundedCornerShape(2.dp),
    medium = RoundedCornerShape(4.dp),
    large = RoundedCornerShape(6.dp),
    extraLarge = RoundedCornerShape(8.dp)
)

@Composable
fun PovosOriginariosTheme(
    darkTheme: Boolean = false,
    content: @Composable () -> Unit
) {
    MaterialTheme(
        colorScheme = if (darkTheme) DarkColorScheme else LightColorScheme,
        typography = Typography,
        shapes = AppShapes,
        content = content
    )
}
