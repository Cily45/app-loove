import {Component, signal} from '@angular/core'
import {Router, RouterLink} from '@angular/router'
import {FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms'
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatInputModule} from '@angular/material/input'
import {NgIf} from '@angular/common'
import {MatIconModule} from '@angular/material/icon'
import {MatButtonModule} from '@angular/material/button';
import {AuthService} from '../../services/auth/auth.service'


@Component({
  selector: 'app-login',
  imports: [
    RouterLink,
    MatFormFieldModule,
    MatInputModule,
    FormsModule,
    ReactiveFormsModule,
    NgIf,
    MatIconModule,
    MatButtonModule
  ],
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent {
  emailErrorMessage = signal('');

  formGroup: FormGroup = new FormGroup({
      email: new FormControl('', [Validators.required, Validators.email]),
      password: new FormControl('', [Validators.required])
    }

  )
  constructor(
    private authService: AuthService,
    private router: Router
  ) {
  }

  showLoginForm = false
  hide = signal(true)

  updateEmailErrorMessage() {
    // @ts-ignore
    if (this.formGroup.get('email').hasError('required')) {
      this.emailErrorMessage.set('Email valide requis');
    } else { // @ts-ignore
      if (this.formGroup.get('email').hasError('email')) {
            this.emailErrorMessage.set('Email invalides');
          } else {
            this.emailErrorMessage.set('');
          }
    }
  }
  async onSubmit() {
    if (this.formGroup.valid) {
      const credentials = {
        email: this.formGroup.get('email')?.value,
        password: this.formGroup.get('password')?.value
      }

      const res = await this.authService.login(credentials)
      if (res) {
         await this.router.navigate(['/accueil']);
      }else{
        // @ts-ignore
        this.formGroup.get('email').setErrors({'invalid': true})

        // @ts-ignore
        this.formGroup.get('password').setErrors({'invalid': true})

      }

    }
  }
}
