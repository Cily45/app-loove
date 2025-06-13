import {Component, signal} from '@angular/core'
import {Router, RouterLink} from '@angular/router'
import {FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms'
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatInputModule} from '@angular/material/input'
import {NgIf} from '@angular/common'
import {MatIconModule} from '@angular/material/icon'
import {MatButtonModule} from '@angular/material/button';
import {AuthService} from '../../services/auth/auth.service'
import {ToastService} from '../../services/toast.service';


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
  formGroup: FormGroup = new FormGroup({
      email: new FormControl('', [Validators.required, Validators.email]),
      password: new FormControl('', [Validators.required])
    }
  )

  constructor(
    private authService: AuthService,
    private router: Router,
  ) {
  }

  showLoginForm = false
  hide = signal(true)


  async onSubmit() {
    if (this.formGroup.valid) {
      const res = await this.authService.login(this.formGroup.value)
      if (res) {
        await this.router.navigate(['/accueil']);
      }
    }
  }
}
