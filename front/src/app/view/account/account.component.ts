import {Component, signal} from '@angular/core';
import {MatExpansionModule} from '@angular/material/expansion';
import {FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import {MatInputModule} from '@angular/material/input'
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatButton} from '@angular/material/button';
import {NgIf} from '@angular/common';
import {MatIconModule} from '@angular/material/icon'
import {matchPassword} from '../../component/validator';


@Component({
  selector: 'app-account',
  imports: [MatExpansionModule, FormsModule, MatInputModule, ReactiveFormsModule, MatFormFieldModule, MatButton, MatIconModule, NgIf],
  templateUrl: './account.component.html',
  styleUrl: './account.component.scss'
})
export class AccountComponent {
  hideOldPassword = signal(true)
  hidePassword = signal(true)
  hideConfirm = signal(true)

  emailFormGroup = new FormGroup({
      email: new FormControl('',
        [
          Validators.required,
          Validators.pattern(/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i)]
      ),
    }
  )

  passwordFormGroup = new FormGroup({
    oldPassword: new FormControl('',Validators.required),
    password: new FormControl('', [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/)
    ]),
    passwordConfirm: new FormControl('', Validators.required)
  }, {validators: matchPassword()})
}
