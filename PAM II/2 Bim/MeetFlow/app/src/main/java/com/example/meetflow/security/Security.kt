package com.example.meetflow.security

import android.util.Base64
import java.security.MessageDigest
import java.security.NoSuchAlgorithmException
import java.security.SecureRandom
import java.security.spec.InvalidKeySpecException
import java.security.spec.KeySpec
import java.util.Arrays
import javax.crypto.SecretKeyFactory
import javax.crypto.spec.PBEKeySpec

class Security(private val iterationCount: Int = 10000,
               private val keyLength: Int = 256) {

    fun hashPassword(password: String): String {
        val salt = generateSalt()
        val hash = generateHash(password.toCharArray(), salt)
        return "PBKDF2WithHmacSHA256:$iterationCount:${Base64.encodeToString(salt, Base64.NO_WRAP)}:${Base64.encodeToString(hash, Base64.NO_WRAP)}"
    }

    fun verifyPassword(password: String, storedHash: String): Boolean {
        val parts = storedHash.split(":")
        if (parts.size != 4) {
            throw IllegalArgumentException("Invalid hash format")
        }

        val algorithm = parts[0]
        val iterations = parts[1].toInt()
        val salt = Base64.decode(parts[2], Base64.NO_WRAP)
        val expectedHash = Base64.decode(parts[3], Base64.NO_WRAP)

        val computedHash = generateHash(password.toCharArray(), salt, iterations, keyLength)
        return MessageDigest.isEqual(expectedHash, computedHash)
    }

    private fun generateSalt(): ByteArray {
        val salt = ByteArray(16)
        val random = SecureRandom()
        random.nextBytes(salt)
        return salt
    }

    private fun generateHash(password: CharArray, salt: ByteArray): ByteArray {
        return generateHash(password, salt, iterationCount, keyLength)
    }

    private fun generateHash(password: CharArray, salt: ByteArray, iterationCount: Int, keyLength: Int): ByteArray {
        val spec = PBEKeySpec(password, salt, iterationCount, keyLength)
        return SecretKeyFactory.getInstance("PBKDF2WithHmacSHA256")
            .generateSecret(spec)
            .encoded
    }
}