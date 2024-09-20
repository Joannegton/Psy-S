package com.joannegton.psyterapeuta.ui.components

import android.util.Log
import androidx.compose.foundation.Image
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.size
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.MoreVert
import androidx.compose.material3.DropdownMenu
import androidx.compose.material3.DropdownMenuItem
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import com.joannegton.psyterapeuta.Interation
import com.joannegton.psyterapeuta.R
import com.joannegton.psyterapeuta.ui.views.ChatScreen

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun TopBar() {
    var expanded by remember { mutableStateOf(false) }

    val id_usuario = 9
    val id_terapeuta = "psy172551805015296"

    TopAppBar(
        title = {
            Row(
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically,
                modifier = Modifier.fillMaxWidth()
            ) {
                Row(verticalAlignment = Alignment.CenterVertically) {
                    Image(
                        painter = painterResource(
                            id = R.drawable.psy_pequeno
                        ),
                        contentDescription = "psy icon",
                        modifier = Modifier.size(70.dp)
                    )
                    Text(
                        text = "Psy: Seu Terapeuta Virtual",
                        fontSize = 20.sp,
                        color = Color.White,
                        fontWeight = FontWeight.Bold
                    )
                }

                Row {
                    IconButton(onClick = { expanded = true }) {
                        Icon(
                            imageVector = Icons.Default.MoreVert,
                            contentDescription = "Menu"
                        )
                    }
                    DropdownMenu(
                        expanded = expanded,
                        onDismissRequest = { expanded = false }
                    ) {
                        DropdownMenuItem(
                            text = { Text("Limpar conversa") },
                            onClick = {
                                expanded = false
                                Interation.excludeMessages(id_usuario, id_terapeuta) { response ->
                                    if (!response.startsWith("Erro")) {
                                        ChatScreen.clearMessages()
                                    } else {
                                        Log.e("TAG", "Erro ao excluir mensagens: $response")
                                    }
                                }
                            }
                        )
                        DropdownMenuItem(
                            text = { Text("Editar Perfil") },
                            onClick = {
                                expanded = false
                            }
                        )
                    }
                }

            }

        },
        colors = TopAppBarDefaults.topAppBarColors(containerColor = MaterialTheme.colorScheme.primary)
    )
}