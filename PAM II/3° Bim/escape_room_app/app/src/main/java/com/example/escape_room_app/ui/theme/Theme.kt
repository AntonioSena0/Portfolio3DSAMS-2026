package com.example.escape_room_app.ui.theme

import android.app.Activity
import androidx.compose.foundation.isSystemInDarkTheme
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.darkColorScheme
import androidx.compose.material3.lightColorScheme
import androidx.compose.runtime.Composable
import androidx.compose.runtime.SideEffect
import androidx.compose.ui.graphics.toArgb
import androidx.compose.ui.platform.LocalView
import androidx.core.view.WindowCompat

private val DarkMystery = darkColorScheme(
    primary = Gold,
    onPrimary = NavyDeep,
    primaryContainer = GoldDark,
    onPrimaryContainer = Parchment,
    secondary = GoldLight,
    onSecondary = NavyDeep,
    tertiary = MossGreen,
    background = NavyDeep,
    onBackground = Parchment,
    surface = NavyCard,
    onSurface = Parchment,
    surfaceVariant = NavyCard,
    onSurfaceVariant = FogGrey,
    error = BloodRed,
    outline = GoldDark
)

private val LightMystery = lightColorScheme(
    primary = GoldDark,
    onPrimary = Parchment,
    primaryContainer = Gold,
    onPrimaryContainer = InkBrown,
    secondary = InkBrown,
    onSecondary = Parchment,
    tertiary = MossGreen,
    background = Parchment,
    onBackground = InkBrown,
    surface = Parchment,
    onSurface = InkBrown,
    surfaceVariant = ParchmentDark,
    onSurfaceVariant = InkBrown,
    error = BloodRed,
    outline = GoldDark
)

@Composable
fun Escape_room_appTheme(
    darkTheme: Boolean = isSystemInDarkTheme(),
    dynamicColor: Boolean = false,
    content: @Composable () -> Unit
) {
    val colorScheme = if (darkTheme) DarkMystery else LightMystery
    val view = LocalView.current
    if (!view.isInEditMode) {
        SideEffect {
            val window = (view.context as Activity).window
            window.statusBarColor = colorScheme.background.toArgb()
            WindowCompat.getInsetsController(window, view).isAppearanceLightStatusBars = !darkTheme
        }
    }
    MaterialTheme(
        colorScheme = colorScheme,
        typography = Typography,
        content = content
    )
}
