import {Component, signal} from '@angular/core';
import {MatError, MatFormField, MatInput, MatLabel} from '@angular/material/input'
import {MatButton} from '@angular/material/button'
import {
  FormControl,
  FormGroup,
  FormsModule,
  ReactiveFormsModule,
  Validators
} from '@angular/forms'

import {ActivatedRoute, Router, RouterLink} from '@angular/router'
import {matchPassword} from '../../component/validator'
import {MatIcon} from '@angular/material/icon'

import {NgIf} from '@angular/common';
import {UserService} from '../../services/api/user.service';
import {ToastService} from '../../services/toast.service';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-password-reset',
  imports: [
    FormsModule,
    MatButton,
    MatError,
    MatFormField,
    MatIcon,
    MatInput,
    MatLabel,
    NgIf,
    ReactiveFormsModule,
    RouterLink
  ],
  templateUrl: './password-reset.component.html',
  styleUrl: './password-reset.component.css'
})
export class PasswordResetComponent {
  hidePassword = signal(true)
  hideConfirm = signal(true)

  constructor(private userService: UserService, private toastService: ToastService, private route: ActivatedRoute, private router: Router) {
  }

  formGroup = new FormGroup({
    password: new FormControl('', [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/)
    ]),
    passwordConfirm: new FormControl('', Validators.required)
  }, {validators: matchPassword()})

  async onSubmit() {
    if (this.formGroup.valid) {
      const token: string = <string>this.route.snapshot.paramMap.get('token')

      const res = await firstValueFrom(this.userService.updatePassword({
        'token': token,
        'password': this.formGroup.get('password')?.value
      }))
      if (res) {
        this.router.navigate(['/connection']);
        this.toastService.showSuccess('Mot de passe mis à jour')
      } else {
        this.toastService.showError(' Erreur lors de la mise à jour du mot de passe')
      }
    }
  }
}
