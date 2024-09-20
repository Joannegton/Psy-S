package com.joannegton.psyterapeuta

import android.content.Context
import android.content.SharedPreferences
import android.util.Log
import com.google.gson.Gson
import io.ktor.client.HttpClient
import io.ktor.client.call.body
import io.ktor.client.engine.cio.CIO
import io.ktor.client.plugins.contentnegotiation.ContentNegotiation
import io.ktor.client.request.delete
import io.ktor.client.request.get
import io.ktor.client.request.post
import io.ktor.client.request.setBody
import io.ktor.client.statement.HttpResponse
import io.ktor.http.ContentType
import io.ktor.http.contentType
import io.ktor.serialization.gson.gson
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import java.text.SimpleDateFormat
import java.util.Locale

const val URL_BASE = "https://7669-45-179-106-136.ngrok-free.app"
object Interation {
    private const val API_URL = "$URL_BASE/api/v1/interacoes/"

    private val client = HttpClient(CIO) {
        install(ContentNegotiation) {
            gson()
        }
    }

    fun postRequest(
        prompt: String,
        id_usuario: Int,
        id_terapeuta: String,
        callback: (String) -> Unit
    ) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val response: HttpResponse = client.post(API_URL + "send") {
                    contentType(ContentType.Application.Json)
                    setBody(
                        mapOf(
                            "id_usuario" to id_usuario,
                            "id_terapeuta" to id_terapeuta,
                            "mensagem" to prompt,
                            "tipo" to "Usuario"
                        )
                    )
                }

                if (response.status.value in 200..299) {
                    val responseBody = response.body<String>()
                    callback(responseBody)
                } else {
                    callback("Erro: ${response.status.description}")
                }
            } catch (e: Exception) {
                callback("Erro ao processar respostab: ${e.message}")
                Log.e("TAG", "Erro ao processar respostab: ${e.message}")
            }
        }
    }

    fun getMessages(id_usuario: Int, id_terapeuta: String, callback: (List<Message>) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val response: HttpResponse =
                    client.get(API_URL + "list?id_usuario=$id_usuario&id_terapeuta=$id_terapeuta")
                if (response.status.value in 200..299) {
                    val messages: String = response.body()
                    Log.d("TAG", "Mensagens recebidas: $messages")
                    val gson = Gson()
                    val messageList = gson.fromJson(messages, Array<Message>::class.java).toList()
                    callback(messageList)
                } else {
                    callback(emptyList())
                    Log.e("TAG", "Erro: ${response.status.description}")
                }
            } catch (e: Exception) {
                callback(emptyList())
                Log.e("TAG", "Erro ao processar resposta: ${e.message}")
            }
        }
    }

    fun excludeMessages(id_usuario: Int, id_terapeuta: String, callback: (String) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val response: HttpResponse = client.delete(API_URL + "delete") {
                    contentType(ContentType.Application.Json)
                    setBody(
                        mapOf(
                            "id_usuario" to id_usuario,
                            "id_terapeuta" to id_terapeuta
                        )
                    )
                }
                if (response.status.value in 200..299) {
                    val responseBody = response.body<String>()
                    callback(responseBody)
                } else {
                    callback("Erro: ${response.status.description}")
                    Log.e("TAG", "Erro: ${response.status.description}")
                }
            } catch (e: Exception) {
                callback("Erro ao processar resposta: ${e.message}")
                Log.e("TAG", "Erro ao processar resposta: ${e.message}")
            }
        }
    }
}

data class Message(
    val id: String,
    val id_usuario: Int,
    val id_terapeuta: String,
    val data_hora: String,
    val mensagem: String,
    val tipo: String,

    )

object UsuarioService {
    private const val API_URL = "$URL_BASE/api/v1/users/"

    private val client = HttpClient(CIO) {
        install(ContentNegotiation) {
            gson()
        }
    }

    fun createUser(nome: String, email: String, senha: String, callback: (String) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val response: HttpResponse = client.post(API_URL + "create") {
                    contentType(ContentType.Application.Json)
                    setBody(
                        mapOf(
                            "nome" to nome,
                            "email" to email,
                            "senha" to senha
                        )
                    )
                }
                if (response.status.value in 200..299) {
                    val responseBody = response.body<String>()
                    callback(responseBody)
                } else {
                    callback("Erro: ${response.status.description}")
                    Log.e("TAG", "Erro: ${response.status.description}")
                }
            } catch (e: Exception) {
                callback("Erro ao processar resposta: ${e.message}")
                Log.e("TAG", "Erro ao processar resposta: ${e.message}")
            }
        }
    }

    fun login(email: String, senha: String, callback: (String) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val response: HttpResponse = client.post(API_URL + "login") {
                    contentType(ContentType.Application.Json)
                    setBody(
                        mapOf(
                            "email" to email,
                            "senha" to senha
                        )
                    )
                }
                if (response.status.value in 200..299) {
                    val responseBody = response.body<String>()
                    withContext(Dispatchers.Main) {
                        callback(responseBody)
                    }
                } else {
                    withContext(Dispatchers.Main) {
                        callback("Erro: ${response.status.description}")
                    }
                    Log.e("TAG", "Erro: ${response.status.description}")
                }
            } catch (e: Exception) {
                withContext(Dispatchers.Main) {
                    callback("Erro ao processar resposta: ${e.message}")
                }
                Log.e("TAG", "Erro ao processar resposta: ${e.message}")
            }
        }
    }}

data class Usuario(
    val id_usuario: Int,
    val nome: String,
    val email: String,
    val senha: String,
    val id_terapeuta: String,
    val data_nascimento: String,
    val sexo: String
    )

fun formatarHoraParaBrasileiro(dataHora: String): String {
    val formatoOriginal = SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault())
    val formatoBrasileiro = SimpleDateFormat("dd/MM HH:mm", Locale("pt", "BR"))
    val data = formatoOriginal.parse(dataHora)
    return formatoBrasileiro.format(data)
}

fun saveUserData(context: Context, idUsuario: Int, idTerapeuta: String) {
    val sharedPreferences: SharedPreferences = context.getSharedPreferences("user_prefs", Context.MODE_PRIVATE)
    val editor = sharedPreferences.edit()
    editor.putInt("id_usuario", idUsuario)
    editor.putString("id_terapeuta", idTerapeuta)
    editor.apply()
}

fun getUserData(context: Context): Pair<Int, String> {
    val sharedPreferences: SharedPreferences = context.getSharedPreferences("user_prefs", Context.MODE_PRIVATE)
    val idUsuario = sharedPreferences.getInt("id_usuario", -1)
    val idTerapeuta = sharedPreferences.getString("id_terapeuta", "Psy0")
    return Pair(idUsuario, idTerapeuta ?: "")
}