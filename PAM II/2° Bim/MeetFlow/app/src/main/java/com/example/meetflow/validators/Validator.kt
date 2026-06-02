package com.example.meetflow.validators

import android.util.Patterns

object Validator {

    fun isValidEmail(email: String): Boolean {
        return !email.isEmpty() && Patterns.EMAIL_ADDRESS.matcher(email).matches()
    }

    fun isValidPassword(password: String): Boolean {
        return password.length >= 6
    }

    fun isValidName(name: String): Boolean {
        return name.length >= 2
    }

    fun isValidPhone(phone: String): Boolean {
        val digits = phone.replace(Regex("[^0-9]"), "")
        return digits.length >= 8
    }

    fun isValidRequiredField(text: String): Boolean {
        return !text.isEmpty()
    }
}