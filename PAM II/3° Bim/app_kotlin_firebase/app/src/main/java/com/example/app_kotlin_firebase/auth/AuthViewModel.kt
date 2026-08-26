package com.example.app_kotlin_firebase.auth

import androidx.lifecycle.ViewModel
import com.google.firebase.FirebaseNetworkException
import com.google.firebase.auth.FirebaseAuthInvalidCredentialsException
import com.google.firebase.auth.FirebaseAuthUserCollisionException
import com.google.firebase.auth.FirebaseAuthWeakPasswordException
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update

class AuthViewModel : ViewModel() {
    private val repository = FirebaseAuthRepository.create()
    private val mutableState = MutableStateFlow(
        AuthState(
            destination = if (repository.currentEmail == null) AuthDestination.LOGIN else AuthDestination.HOME,
            email = repository.currentEmail.orEmpty(),
            firebaseConfigured = repository.isConfigured
        )
    )

    val state: StateFlow<AuthState> = mutableState.asStateFlow()

    fun showLogin() {
        mutableState.update { it.copy(destination = AuthDestination.LOGIN, errorMessage = null) }
    }

    fun showSignup() {
        mutableState.update { it.copy(destination = AuthDestination.SIGNUP, errorMessage = null) }
    }

    fun login(email: String, password: String) {
        authenticate(email, password, repository::login)
    }

    fun signup(email: String, password: String) {
        authenticate(email, password, repository::signup)
    }

    fun logout() {
        repository.logout()
        mutableState.value = AuthState(firebaseConfigured = repository.isConfigured)
    }

    fun dismissError() {
        mutableState.update { it.copy(errorMessage = null) }
    }

    private fun authenticate(
        email: String,
        password: String,
        operation: (String, String, (Result<String>) -> Unit) -> Unit
    ) {
        val validationMessage = CredentialsValidator.validate(email, password)
        if (validationMessage != null) {
            mutableState.update { it.copy(errorMessage = validationMessage) }
            return
        }

        mutableState.update { it.copy(isLoading = true, errorMessage = null) }
        operation(email, password) { result ->
            result.onSuccess { authenticatedEmail ->
                mutableState.update {
                    it.copy(
                        destination = AuthDestination.HOME,
                        email = authenticatedEmail.ifBlank { email.trim() },
                        isLoading = false
                    )
                }
            }.onFailure { exception ->
                mutableState.update {
                    it.copy(isLoading = false, errorMessage = exception.toUserMessage())
                }
            }
        }
    }
}

private fun Throwable.toUserMessage(): String = when (this) {
    is FirebaseUnavailableException -> "Adicione o google-services.json para conectar o Firebase."
    is FirebaseAuthWeakPasswordException -> "Escolha uma senha mais forte, com pelo menos 6 caracteres."
    is FirebaseAuthUserCollisionException -> "Este e-mail já possui cadastro."
    is FirebaseAuthInvalidCredentialsException -> "E-mail ou senha incorretos."
    is FirebaseNetworkException -> "Sem conexão. Verifique a internet e tente novamente."
    else -> localizedMessage ?: "Não foi possível concluir. Tente novamente."
}
