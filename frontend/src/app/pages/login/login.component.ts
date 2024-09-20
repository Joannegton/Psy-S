import { Component } from '@angular/core';
import {
  ReactiveFormsModule,
  FormBuilder,
  FormGroup,
  Validators,
} from '@angular/forms';

import { InputComponent } from '../../components/input/input.component';
import { ButtonComponent } from "../../components/button/button.component";

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [InputComponent, ReactiveFormsModule, ButtonComponent],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
})
export class LoginComponent {
  formulario: FormGroup;

  constructor(private formBuilder: FormBuilder) {
    // Injeção de dependência do FormBuilder
    this.formulario = this.formBuilder.group({
      usuario: [''],
      terapeuta: [''],
      email: ['', [Validators.required, Validators.email]],
      senha: ['', Validators.required],
    });
  }

  atualizarFormulario() {
    this.formulario.setValue({
      usuario: '',
      terapeuta: '',
      email: '',
      senha: '',
    });
  }

  // Função para enviar o formulário
  async login() {
    let usuario = this.formulario.get('usuario')!.value;
    let terapeuta = this.formulario.get('terapeuta')!.value;
    let email = this.formulario.get('email')!.value;
    let senha = this.formulario.get('senha')!.value;

    if ((usuario === '' && terapeuta === '') || email === '' || senha === '') {
      alert('Preencha todos os campos');
      return;
    }

    let baseUrl = 'http://localhost:8000';
    let url = usuario
      ? `${baseUrl}/api/v1/users/login`
      : `${baseUrl}/api/v1/profissionais/login`;

    try {
      let response = await fetch(
        url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          email: email,
          senha: senha,
        }),
      });

      if (response.status === 200) {
        let data = await response.json();
        //salvar os dados
        localStorage.setItem('usuario', data.usuario);
        localStorage.setItem('terapeuta', data.terapeuta);
        //redirecionar para a página de dashboard
        //window.location.href = '/dashboard';
      } else {
        alert('Usuario ou senha incorretos');
      }
    } catch (error) {
      console.error('Erro na requisição:', error);
    }
  }

  
}
