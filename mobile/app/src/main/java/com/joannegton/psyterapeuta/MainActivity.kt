package com.joannegton.psyterapeuta

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.runtime.Composable
import androidx.navigation.NavHostController
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import com.joannegton.psyterapeuta.ui.views.CadastroScreen
import com.joannegton.psyterapeuta.ui.views.ChatScreen
import com.joannegton.psyterapeuta.ui.views.LoginScreen
import com.joannegton.psyterapeuta.ui.theme.PsyTerapeutaTheme

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            PsyTerapeutaTheme {
                val navController = rememberNavController()
                NavGraphSetup(navController = navController)


            }
        }
    }
}

@Composable
fun NavGraphSetup(navController: NavHostController) {
    NavHost(navController = navController, startDestination = "login") {
        composable("chat"){ ChatScreen() }
        composable("cadastro"){ CadastroScreen(navController) }
        composable("login"){ LoginScreen(navController) }
    }
}
