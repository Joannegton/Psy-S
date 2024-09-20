package com.joannegton.psyterapeuta.ui.components


import androidx.compose.foundation.background
import androidx.compose.foundation.border
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.text.BasicTextField
import androidx.compose.foundation.text.KeyboardOptions
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Send
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.input.KeyboardCapitalization
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

@Composable
fun EnvioTexto(
    onMessageSend: (String) -> Unit, // Callback para enviar mensagem
) {
    var userMessage by remember { mutableStateOf("") }

    Row( verticalAlignment = Alignment.CenterVertically, modifier = Modifier.padding(3.dp)) {
        BasicTextField(
            value = userMessage,
            onValueChange = { userMessage = it },
            textStyle = TextStyle(fontSize = 18.sp, color = Color.Black),
            keyboardOptions = KeyboardOptions.Companion.Default.copy(
                capitalization = KeyboardCapitalization.Sentences
            ),
            modifier = Modifier
                .weight(1f)
                .clip(RoundedCornerShape(50.dp))
                .border(2.dp, MaterialTheme.colorScheme.primaryContainer, RoundedCornerShape(50.dp))
                .background(Color.LightGray)
                .padding(13.dp)


        )

        Spacer(modifier = Modifier.width(2.dp))

        Button(
            onClick = {
                if (userMessage.isNotBlank()) {
                    onMessageSend(userMessage) // Chama o callback para enviar a mensagem
                    userMessage = ""}
            },
            colors = ButtonDefaults.buttonColors(containerColor = MaterialTheme.colorScheme.primary, contentColor = Color.LightGray),
            modifier = Modifier.clip(CircleShape).border(2.dp, MaterialTheme.colorScheme.primaryContainer, CircleShape)
        ) {
            Icon(
                imageVector = Icons.Default.Send,
                contentDescription = "Enviar",
                modifier = Modifier.padding(2.dp)
            )
        }
    }
}

@Preview
@Composable
private fun View() {
    EnvioTexto(onMessageSend = {})
}