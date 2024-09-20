import { Component, Input } from '@angular/core';
import {FormControl, ReactiveFormsModule} from '@angular/forms';

@Component({
  selector: 'app-input',
  standalone: true,
  imports: [ReactiveFormsModule],
  templateUrl: './input.component.html',
  styleUrl: './input.component.css'
})
export class InputComponent {
  @Input() descricao:string = 'lsllss'
  @Input() tipo:string = 'text'

  name = new FormControl('') // FormControl é um objeto que ajuda a controlar o valor de um campo de formulário

  updateName() {
    this.name.setValue('Nancy');
  }

}
