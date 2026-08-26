package com.example.app_kotlin_firebase.auth

enum class AuthDestination {
    LOGIN,
    SIGNUP,
    HOME
}

data class AuthState(
    val destination: AuthDestination = AuthDestination.LOGIN,
    val email: String = "",
    val isLoading: Boolean = false,
    val errorMessage: String? = null,
    val firebaseConfigured: Boolean = true
)
