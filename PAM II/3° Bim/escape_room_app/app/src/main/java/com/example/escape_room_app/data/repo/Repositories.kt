package com.example.escape_room_app.data.repo

import com.example.escape_room_app.data.model.Collections
import com.example.escape_room_app.data.model.Hint
import com.example.escape_room_app.data.model.Score
import com.example.escape_room_app.data.model.Station
import com.example.escape_room_app.data.model.Team
import com.google.firebase.firestore.FirebaseFirestore
import com.google.firebase.firestore.Query
import kotlinx.coroutines.channels.awaitClose
import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.callbackFlow
import kotlinx.coroutines.tasks.await

private val db: FirebaseFirestore
    get() = FirebaseFirestore.getInstance()

private inline fun <reified T : Any> Query.snapshotsAs(flowClass: Class<T>): Flow<List<T>> = callbackFlow {
    val sub = addSnapshotListener { snap, err ->
        if (err != null) { close(err); return@addSnapshotListener }
        val list = snap?.toObjects(flowClass) ?: emptyList()
        trySend(list)
    }
    awaitClose { sub.remove() }
}

object StationRepo {
    private fun col() = db.collection(Collections.STATIONS)

    fun observe(): Flow<List<Station>> =
        col().orderBy("ordem", Query.Direction.ASCENDING).snapshotsAs(Station::class.java)

    suspend fun add(s: Station): String {
        val ref = col().add(s).await()
        return ref.id
    }
    suspend fun update(s: Station) {
        require(s.id.isNotBlank())
        col().document(s.id).set(s).await()
    }
    suspend fun delete(id: String) { col().document(id).delete().await() }
}

object HintRepo {
    private fun col() = db.collection(Collections.HINTS)

    fun observe(): Flow<List<Hint>> =
        col().orderBy("ordem", Query.Direction.ASCENDING).snapshotsAs(Hint::class.java)

    fun observeByStation(estacaoId: String): Flow<List<Hint>> = callbackFlow {
        val sub = col().whereEqualTo("estacaoId", estacaoId)
            .orderBy("ordem").addSnapshotListener { snap, err ->
                if (err != null) { close(err); return@addSnapshotListener }
                trySend(snap?.toObjects(Hint::class.java) ?: emptyList())
            }
        awaitClose { sub.remove() }
    }

    suspend fun add(h: Hint): String = col().add(h).await().id
    suspend fun update(h: Hint) { col().document(h.id).set(h).await() }
    suspend fun delete(id: String) { col().document(id).delete().await() }
}

object TeamRepo {
    private fun col() = db.collection(Collections.TEAMS)

    fun observe(): Flow<List<Team>> =
        col().orderBy("nome", Query.Direction.ASCENDING).snapshotsAs(Team::class.java)

    suspend fun add(t: Team): String = col().add(t).await().id
    suspend fun update(t: Team) { col().document(t.id).set(t).await() }
    suspend fun delete(id: String) { col().document(id).delete().await() }
}

object ScoreRepo {
    private fun col() = db.collection(Collections.SCORES)

    fun observe(): Flow<List<Score>> =
        col().orderBy("pontos", Query.Direction.DESCENDING).snapshotsAs(Score::class.java)

    suspend fun add(s: Score): String = col().add(s).await().id
    suspend fun update(s: Score) { col().document(s.id).set(s).await() }
    suspend fun delete(id: String) { col().document(id).delete().await() }
}
