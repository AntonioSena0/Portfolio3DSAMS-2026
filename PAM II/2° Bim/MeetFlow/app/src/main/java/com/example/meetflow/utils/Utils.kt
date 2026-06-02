package com.example.meetflow.utils

object Utils {
    fun isNullOrEmpty(str: String?): Boolean {
        return str == null || str.isEmpty()
    }

    fun isNotNullOrEmpty(str: String?): Boolean {
        return !isNullOrEmpty(str)
    }

    fun capitalize(str: String): String {
        if (str.isEmpty()) return str
        return str.substring(0, 1).uppercase() + str.substring(1)
    }
}