import {Component, OnInit, signal} from '@angular/core';
import {MatIconModule} from '@angular/material/icon';
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatInput, MatInputModule} from '@angular/material/input'
import {MatExpansionModule} from '@angular/material/expansion';
import {FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import {ProfilAdminView, UserService} from '../../services/api/user.service';
import {ActivatedRoute, Router, RouterLink} from '@angular/router';
import {birthDateValidator, matchPassword} from '../../component/validator';
import {firstValueFrom} from 'rxjs';
import {MatButton} from '@angular/material/button';
import {NgIf} from '@angular/common';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-profil',
  imports: [MatIconModule, MatIconModule, MatFormFieldModule, MatInputModule, MatExpansionModule, ReactiveFormsModule, FormsModule, MatButton, MatInput, NgIf, ReactiveFormsModule, RouterLink],
  templateUrl: './user.component.html',
  styleUrl: './user.component.scss'
})
export class UserComponent implements OnInit {
  userProfil = signal<ProfilAdminView>({
    id: 0,
    lastname: '',
    firstname: '',
    birthday: '',
    email: '',
  })

  constructor(
    private userService: UserService, private route: ActivatedRoute, private toastService: ToastService, private router: Router) {
  }

  formGroup = new FormGroup({
    id: new FormControl(this.userProfil().id),
    lastname: new FormControl(this.userProfil().lastname, Validators.required),
    firstname: new FormControl(this.userProfil().firstname, Validators.required),
    birthday: new FormControl(this.userProfil().birthday, [Validators.required, birthDateValidator()]),
    email: new FormControl(this.userProfil().email, [
      Validators.required,
      Validators.pattern(/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i)
    ]),
  })

  async onSubmit() {
    this.formGroup.markAllAsTouched();

    if (this.formGroup.invalid) {
      this.toastService.showError('Veuillez corriger les erreurs dans le formulaire');
      return;
    }

    if (this.formGroup.get('email')?.value !== this.userProfil().email) {
      const emailControl = this.formGroup.get('email')
      const email: string = this.formGroup.get('email')?.value || 'error'
      const isEmailUsed = await firstValueFrom(this.userService.isMailUsed(email))

      if (!emailControl) {
        return
      }
      if (isEmailUsed) {
        emailControl.setErrors({'emailUsed': true})
        return
      }
      emailControl.setErrors(null)
    }
    if (this.userProfil().id === 0) {
      this.userService.createUser(this.formGroup.value).subscribe(res => {
        if (res) {
          this.toastService.showSuccess('Utilisateur créé')
          this.router.navigate(['/utilisateurs'])
        } else {
          this.toastService.showError('Une erreur est survenue')
        }
      })
    } else {
      this.userService.userUpdate(this.formGroup.value).subscribe(res => {
        if (res) {
          this.toastService.showSuccess('Utilisateur à été mis à jour')
          this.router.navigate(['/utilisateurs'])
        } else {
          this.toastService.showError('Une erreur est survenue')
        }
      })
    }
  }

  ngOnInit() {
    let id: number = parseInt(<string>this.route.snapshot.paramMap.get('id'))
    if (id > 0) {
      this.userService.getProfilAdmin(id).subscribe(user => {
          this.userProfil.set(user)
          this.formGroup.patchValue({
            id: user.id,
            lastname: user.lastname,
            firstname: user.firstname,
            birthday: user.birthday,
            email: user.email
          })
        }
      )
    }
  }
}
