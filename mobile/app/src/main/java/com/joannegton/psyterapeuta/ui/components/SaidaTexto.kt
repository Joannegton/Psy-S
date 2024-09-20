package com.joannegton.psyterapeuta.ui.components

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

@Composable
fun SaidaTexto(
    mensagem: String,
    horario: String,
    isEnviadaUsuario: Boolean
) {

    val alignment = if (isEnviadaUsuario) Alignment.End else Alignment.Start
    val backgroundColor =
        if (isEnviadaUsuario) MaterialTheme.colorScheme.secondary else MaterialTheme.colorScheme.tertiary

    Column(
        horizontalAlignment = alignment,
        modifier = Modifier
            .fillMaxWidth()
            .padding(vertical = 8.dp)
    ) {
        Text(
            text = mensagem,
            fontSize = 14.sp,
            color = Color.Black,
            modifier = Modifier
                .background(backgroundColor, shape = RoundedCornerShape(16.dp))
                .padding(10.dp)
        )

        Text(
            text = horario,
            fontSize = 10.sp,
            color = Color.Gray,
            textAlign = TextAlign.Right,
        )
    }

}

@Preview
@Composable
private fun View() {
    SaidaTexto(mensagem = "Eai meu amigo", horario = "12:00", isEnviadaUsuario = true)
}