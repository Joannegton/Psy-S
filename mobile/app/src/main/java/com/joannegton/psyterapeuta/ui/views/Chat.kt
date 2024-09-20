package com.joannegton.psyterapeuta.ui.views

import android.util.Log
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.imePadding
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.lazy.rememberLazyListState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateListOf
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.graphicsLayer
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import com.joannegton.psyterapeuta.Interation
import com.joannegton.psyterapeuta.Message
import com.joannegton.psyterapeuta.R
import com.joannegton.psyterapeuta.formatarHoraParaBrasileiro
import com.joannegton.psyterapeuta.getUserData
import com.joannegton.psyterapeuta.ui.components.EnvioTexto
import com.joannegton.psyterapeuta.ui.components.SaidaTexto
import com.joannegton.psyterapeuta.ui.components.TopBar

@Composable
fun ChatScreen(modifier: Modifier = Modifier) {
    val messages = remember { ChatScreen.messages }
    var isLoading by remember { mutableStateOf(false) }
    val listState = rememberLazyListState()

    val (id_usuario, id_terapeuta) = getUserData(LocalContext.current)

    LaunchedEffect(Unit) {
        isLoading = true
        Interation.getMessages(id_usuario, id_terapeuta) {
            messages.addAll(it)
            isLoading = false
        }
    }

    LaunchedEffect(messages.size) {
        listState.animateScrollToItem(messages.size)
    }

    Scaffold(
        modifier = Modifier.fillMaxSize().imePadding(), //ajusta o teclado quando aparece na tela
        topBar = { TopBar() },
        containerColor = MaterialTheme.colorScheme.background
    ) { innerPadding ->
        Box(
            modifier = Modifier
                .fillMaxSize().padding(innerPadding),
            contentAlignment = Alignment.Center
        ) {
            Image(
                painter = painterResource(id = R.drawable.psy_grande),
                contentDescription = "Background Image",
                modifier = Modifier
                    .size(500.dp)
                    .graphicsLayer(alpha = 0.5f), // Define a opacidade
            )


            Column(
                verticalArrangement = Arrangement.Top,
                modifier = Modifier
                    .padding(8.dp)
                    .fillMaxWidth()
            ) {
                LazyColumn(modifier = modifier.weight(1f), state = listState) {
                    items(messages) { message ->
                        val paddingModifier =
                            if (message.tipo == "Usuario") Modifier.padding(start = 50.dp) else Modifier.padding(
                                end = 50.dp
                            )

                        Column(
                            horizontalAlignment = if (message.tipo == "Usuario") Alignment.Start else Alignment.End,
                            modifier = Modifier
                                .clip(RoundedCornerShape(20.dp))
                                .then(paddingModifier)
                        ) {
                            val data_hora = try {
                                formatarHoraParaBrasileiro(message.data_hora)
                            } catch (e: Exception) {
                                Log.e("ChatScreen", "Error formatting date", e)
                                message.data_hora // fallback to original date
                            }
                            SaidaTexto(
                                mensagem = message.mensagem,
                                horario = data_hora,
                                isEnviadaUsuario = message.tipo == "Usuario"
                            )
                        }
                    }
                }

                EnvioTexto(onMessageSend = { message ->
                    messages.add(
                        Message(
                            id = "temp", // Id Temporario
                            id_usuario = id_usuario,
                            id_terapeuta = id_terapeuta,
                            data_hora = "now", // hora temporaria
                            mensagem = message,
                            tipo = "Usuario"
                        )
                    )

                    Interation.postRequest(message, id_usuario, id_terapeuta) { response ->
                        if (!response.startsWith("Erro")) {
                            // Re-fetch messages from the database
                            Interation.getMessages(id_usuario, id_terapeuta) {
                                messages.clear()
                                messages.addAll(it)
                            }
                        } else {
                            // Handle error (e.g., show a toast or log the error)
                        }
                    }
                })
            }
        }
    }

}

object ChatScreen {
    val messages = mutableStateListOf<Message>()

    fun clearMessages() {
        messages.clear()
    }
}

@Preview
@Composable
private fun View() {
    ChatScreen()
}