import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { ChatComponent } from "./pages/chat/chat.component";
import { LoginComponent } from "./pages/login/login.component";
import { InputComponent } from "./components/input/input.component";
import { ButtonComponent } from "./components/button/button.component";

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, ChatComponent, LoginComponent, InputComponent, ButtonComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'psy-terapeuta-virtual';
}
