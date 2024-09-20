package com.joannegton.psyterapeuta.ui.views

import android.util.Log
import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.ImeAction
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavHostController
import com.joannegton.psyterapeuta.R
import com.joannegton.psyterapeuta.UsuarioService
import com.joannegton.psyterapeuta.saveUserData
import com.joannegton.psyterapeuta.ui.components.EntradaTexto
import org.json.JSONObject

@Composable
fun LoginScreen(navHostController: NavHostController) {

    var email by remember { mutableStateOf("") }
    var senha by remember { mutableStateOf("") }
    val context = LocalContext.current

    Column(
        verticalArrangement = Arrangement.Center,
        horizontalAlignment = Alignment.CenterHorizontally,
        modifier = Modifier
            .fillMaxSize()
            .background(MaterialTheme.colorScheme.background)
            .padding(16.dp)
    ) {

        Image(painter = painterResource(id = R.drawable.psy_pequeno), contentDescription = "Psy")
        Spacer(modifier = Modifier.height(10.dp))
        Text(
            text = "Realize seu Login",
            fontSize = 30.sp,
            fontWeight = FontWeight.Bold,
            color = MaterialTheme.colorScheme.primary
        )
        Spacer(modifier = Modifier.height(15.dp))

        EntradaTexto(
            texto = email,
            onValueChange = { email = it },
            label = "Email",
        )
        Spacer(modifier = Modifier.height(10.dp))
        EntradaTexto(
            texto = senha,
            onValueChange = { senha = it },
            label = "Senha",
            imeAction = ImeAction.Done,
            isSenha = true
        )
        Spacer(modifier = Modifier.height(20.dp))

        Button(
            onClick = {
                UsuarioService.login(email, senha) {
                    if (it.startsWith("Erro")) {
                        return@login
                    }
                    val jsonResponse = JSONObject(it)
                    val idUsuario = jsonResponse.getInt("id_usuario")
                    val idTerapeuta = jsonResponse.getString("id_terapeuta")

                    // Save the user data
                    saveUserData(context, idUsuario, idTerapeuta)

                    navHostController.navigate("chat") {
                        popUpTo("login") {
                            inclusive = true
                        }
                    }
                }
            },
            content = { Text(text = "Entrar", fontSize = 23.sp, fontWeight = FontWeight.Bold) },
            modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 10.dp)
                .height(50.dp),
            colors = ButtonDefaults.buttonColors(
                containerColor = MaterialTheme.colorScheme.primary,
                contentColor = Color.White
            )
        )

        Spacer(modifier = Modifier.height(30.dp))

        Row {
            Text(text = "Não tem uma conta?", color = Color.Black, fontSize = 18.sp)
            Text(
                text = "Cadastre-se",
                color = MaterialTheme.colorScheme.primary,
                fontSize = 18.sp,
                modifier = Modifier
                    .padding(start = 10.dp)
                    .clickable {
                        navHostController.navigate("cadastro")
                    }
            )
        }
        Spacer(modifier = Modifier.height(70.dp))

    }

}

@Preview
@Composable
private fun View() {
    LoginScreen(navHostController = NavHostController(LocalContext.current))
}