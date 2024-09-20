import { HttpClient } from '@angular/common/http';
import { Component, inject, Output, EventEmitter } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-chat-input',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './chat-input.component.html',
  styleUrls: ['./chat-input.component.css']
})
export class ChatInputComponent {
  message: string = '';
  private http = inject(HttpClient);

  @Output() messageSent = new EventEmitter<void>();

  sendMessage(): void {
    const id_usuario = 2;
    const id_terapeuta = "psy172508057914245";
    const tipo = "Terapeuta";
    const url = `http://localhost:8000/api/v1/interacoes/send`;

    this.http.post(url, {
      id_usuario: id_usuario,
      id_terapeuta: id_terapeuta,
      mensagem: this.message,
      tipo: tipo
    }).subscribe({
      next: response => {
        console.log("Mensagem enviada com sucesso!");
        this.message = '';
        this.messageSent.emit(); // Emitir evento sem parâmetros
      },
      error: error => {
        console.log("Erro ao enviar mensagem!", error);
      }
    });
  }
}