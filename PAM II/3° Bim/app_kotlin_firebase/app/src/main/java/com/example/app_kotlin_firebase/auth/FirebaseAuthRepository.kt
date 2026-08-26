package com.example.app_kotlin_firebase.auth

import com.google.firebase.auth.FirebaseAuth

class FirebaseAuthRepository private constructor(
    private val firebaseAuth: FirebaseAuth?
) {
    val isConfigured: Boolean
        get() = firebaseAuth != null

    val currentEmail: String?
        get() = firebaseAuth?.currentUser?.email

    fun login(email: String, password: String, onResult: (Result<String>) -> Unit) {
        val authentication = firebaseAuth ?: return onResult(Result.failure(FirebaseUnavailableException()))
        authentication.signInWithEmailAndPassword(email.trim(), password)
            .addOnSuccessListener { result -> onResult(Result.success(result.user?.email.orEmpty())) }
            .addOnFailureListener { exception -> onResult(Result.failure(exception)) }
    }

    fun signup(email: String, password: String, onResult: (Result<String>) -> Unit) {
        val authentication = firebaseAuth ?: return onResult(Result.failure(FirebaseUnavailableException()))
        authentication.createUserWithEmailAndPassword(email.trim(), password)
            .addOnSuccessListener { result -> onResult(Result.success(result.user?.email.orEmpty())) }
            .addOnFailureListener { exception -> onResult(Result.failure(exception)) }
    }

    fun logout() {
        firebaseAuth?.signOut()
    }

    companion object {
        fun create(): FirebaseAuthRepository {
            val authentication = runCatching { FirebaseAuth.getInstance() }.getOrNull()
            return FirebaseAuthRepository(authentication)
        }
    }
}

class FirebaseUnavailableException : IllegalStateException()
