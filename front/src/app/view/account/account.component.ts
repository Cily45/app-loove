import {Component, OnInit, signal} from '@angular/core';
import {MatExpansionModule} from '@angular/material/expansion';
import {FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import {MatInputModule} from '@angular/material/input'
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatButton} from '@angular/material/button';
import {MatIconModule} from '@angular/material/icon'
import {matchPassword} from '../../component/validator';
import {Profil, UserService} from '../../services/api/user.service';
import {ToastService} from '../../services/toast.service';
import {NgIf} from '@angular/common';
import {AuthService} from '../../services/auth/auth.service';


@Component({
  selector: 'app-account',
  imports: [MatExpansionModule, FormsModule, MatInputModule, ReactiveFormsModule, MatFormFieldModule, MatButton, MatIconModule, NgIf],
  templateUrl: './account.component.html',
  styleUrl: './account.component.scss'
})
export class AccountComponent implements OnInit {
  hidePassword = signal(true)
  hideConfirm = signal(true)
  userProfil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 2,
    gender: '',
    distance_km: 0,
  })
  userMail = signal<string>('')

  constructor(private userService: UserService, private toastService: ToastService, private authService: AuthService) {
  }

  ngOnInit() {
    this.userProfil.set(JSON.parse(<string>localStorage.getItem('profil')))
    this.userMail.set(<string>localStorage.getItem('email'))

  }

  emailFormGroup = new FormGroup({
      email: new FormControl('',
        [
          Validators.required,
          Validators.pattern(/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i)]
      ),
    }
  )

  passwordFormGroup = new FormGroup({
    password: new FormControl('', [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/)
    ]),
    passwordConfirm: new FormControl('', Validators.required)
  }, {validators: matchPassword()})

  deleteFormGroup = new FormGroup({
    input: new FormControl('', [Validators.required, Validators.pattern("Supprimer")])
  })

  onSubmitDelete() {
    if (this.deleteFormGroup.valid && confirm("La suppression de votre compte est irréversible. Êtes-vous sûr de vouloir supprimer votre compte ?")) {
      this.userService.deleteUser(this.userProfil().id).subscribe(res => {
        if (res) {
          this.authService.logout()
          this.toastService.showSuccess('Votre compte a bien été supprimé.')
        }
      })
    }
  }

  onSubmitEmail() {
    if (this.emailFormGroup.valid) {
      this.userService.isMailUsed(this.emailFormGroup.get('email')?.value ?? '').subscribe(res => {
        if (res) {
          this.emailFormGroup.get('email')?.setErrors({'emailUsed': true})
        } else {
          this.userService.update(this.emailFormGroup.value).subscribe(res => {
            if (res) {
              this.userMail.set(this.emailFormGroup.get('email')?.value ?? '')
              localStorage.setItem('email', this.emailFormGroup.get('email')?.value ?? '');
              this.toastService.showSuccess('Adresse e-mail mis à jour')
            } else {
              this.toastService.showError('Erreur lors de la mise à jour de l\'adresse mail')
            }
          })
        }
      })
    }
  }

  onSubmitPassword() {
    if (this.passwordFormGroup.valid) {
      this.userService.update(this.passwordFormGroup.value).subscribe(res => {
        if (res) {
          this.toastService.showSuccess('Adresse e-mail mis à jour')
        } else {
          this.toastService.showError('Erreur lors de la mise à jour de l\'adresse mail')
        }
      })
    }
  }
}
