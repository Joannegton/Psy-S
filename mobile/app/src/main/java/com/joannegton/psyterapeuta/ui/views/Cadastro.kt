package com.joannegton.psyterapeuta.ui.views

import androidx.compose.foundation.Image
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
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
import com.joannegton.psyterapeuta.ui.components.EntradaTexto
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

@Composable
fun CadastroScreen(navHostController: NavHostController) {
    Column (
        modifier = Modifier
            .fillMaxSize()
            .background(MaterialTheme.colorScheme.background)
            .padding(16.dp),
        horizontalAlignment = Alignment.CenterHorizontally,
        verticalArrangement = Arrangement.Center
    ){
        var nome by remember { mutableStateOf("") }
        var email by remember { mutableStateOf("") }
        var senha by remember { mutableStateOf("") }
        var confirmarSenha by remember { mutableStateOf("") }

        Image(painter = painterResource(id = R.drawable.psy_pequeno), contentDescription = "Psy")
        Text(
            text = "Realize seu Cadastro",
            fontSize = 30.sp,
            fontWeight = FontWeight.Bold,
            color = MaterialTheme.colorScheme.primary
                    )
        Spacer(modifier = Modifier.height(15.dp))

        EntradaTexto(
            texto = nome,
            onValueChange = { nome = it },
            label = "Nome"
        )
        EntradaTexto(
            texto = email,
            onValueChange = { email = it },
            label = "Email"
        )
        EntradaTexto(
            texto = senha,
            onValueChange = { senha = it },
            label = "Senha",
            isSenha = true
        )
        EntradaTexto(
            texto = confirmarSenha,
            onValueChange = { confirmarSenha = it },
            label = "Confirmar Senha",
            imeAction = ImeAction.Done,
            isSenha = true
        )
        Spacer(modifier = Modifier.height(20.dp))

        Button(
            onClick = {
                if(senha != confirmarSenha){
                    return@Button
                }
                UsuarioService.createUser(nome, email, senha){
                    if(it.startsWith("Erro")){
                        return@createUser
                    }
                    CoroutineScope(Dispatchers.Main).launch {
                        navHostController.navigate("login")
                    }                }

            },
            content = { Text(text = "Cadastrar", fontSize = 23.sp, fontWeight = FontWeight.Bold) },
            modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 10.dp)
                .height(50.dp),
            colors = ButtonDefaults.buttonColors(
                containerColor = MaterialTheme.colorScheme.primary,
                contentColor = Color.White
            )
        )
        Spacer(modifier = Modifier.height(70.dp))

    }
}

@Preview
@Composable
private fun View() {
    CadastroScreen(navHostController = NavHostController(LocalContext.current))
}