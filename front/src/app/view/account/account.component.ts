import {Component, OnInit, signal} from '@angular/core';
import {MatExpansionModule} from '@angular/material/expansion';
import {FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import {MatInputModule} from '@angular/material/input'
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatButton} from '@angular/material/button';
import {MatIconModule} from '@angular/material/icon'
import {matchPassword} from '../../component/validator';
import {Profil, UserService} from '../../services/api/user.service';
import {MailService} from '../../services/api/mail.service';
import {ToastService} from '../../services/toast.service';
import {log} from '@angular-devkit/build-angular/src/builders/ssr-dev-server';
import {MatSnackBar} from '@angular/material/snack-bar';


@Component({
  selector: 'app-account',
  imports: [MatExpansionModule, FormsModule, MatInputModule, ReactiveFormsModule, MatFormFieldModule, MatButton, MatIconModule],
  templateUrl: './account.component.html',
  styleUrl: './account.component.scss'
})
export class AccountComponent implements OnInit{
  hideOldPassword = signal(true)
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
  constructor(private userService : UserService,private toastService : ToastService) {
  }

  ngOnInit() {
    this.userProfil.set(JSON.parse(<string>localStorage.getItem('profil')))
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
    oldPassword: new FormControl('',Validators.required),
    password: new FormControl('', [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/)
    ]),
    passwordConfirm: new FormControl('', Validators.required)
  }, {validators: matchPassword()})

  deleteFormGroup = new FormGroup({
    input: new FormControl('', [Validators.required, Validators.pattern("Supprimer")])
  })

  delete() {
    if(this.deleteFormGroup.valid && confirm("La suppression de votre compte est irréversible. Êtes-vous sûr de vouloir supprimer votre compte ?")){
      this.userService.deleteUser(this.userProfil().id)
    }
  }

  send(){
    this.toastService.showInfo('Vous avez un nouveau message de')
  }
}
