import { Component } from '@angular/core';
import { ChatInputComponent } from "./chat-input/chat-input.component";
import { ChatOutputComponent } from "./chat-output/chat-output.component";

@Component({
  selector: 'app-chat',
  standalone: true,
  imports: [ChatInputComponent, ChatOutputComponent],
  templateUrl: './chat.component.html',
  styleUrl: './chat.component.css'
})
export class ChatComponent {

}
