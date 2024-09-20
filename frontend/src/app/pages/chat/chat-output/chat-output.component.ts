import { NgClass, NgFor } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Component, inject, OnInit } from '@angular/core';

@Component({
  selector: 'app-chat-output',
  standalone: true,
  imports: [NgFor, NgClass],
  templateUrl: './chat-output.component.html',
  styleUrls: ['./chat-output.component.css']
})
export class ChatOutputComponent implements OnInit {
  private http = inject(HttpClient);
  messages: any[] = [];

  id_usuario: number = 2;
  id_terapeuta: string = "psy172508057914245";

  ngOnInit() {
    this.loadMessages();
  }

  loadMessages() {
    const url = `http://localhost:8000/api/v1/interacoes/list?id_usuario=${this.id_usuario}&id_terapeuta=${this.id_terapeuta}`;
    this.http.get<any[]>(url).subscribe({
      next: (data) => {
        this.messages = data;
      },
      error: (error) => {
        console.log('Erro ao carregar mensagens', error);
      }
    });
  }

  startPolling() {
    setInterval(() => {
      this.loadMessages();
    }, 5000); // Verifica novas mensagens a cada 5 segundos
  }
}