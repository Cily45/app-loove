import {Component} from '@angular/core';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatInputModule} from '@angular/material/input';
import {MatButtonModule} from '@angular/material/button';
import {RouterLink} from '@angular/router'
import {MatIconModule} from '@angular/material/icon';
import {FormControl, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import {NgIf} from '@angular/common';
import {MailService} from '../../services/api/mail.service';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-password-lost',
  imports: [
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    RouterLink,
    MatIconModule,
    ReactiveFormsModule,
    NgIf,
  ],
  templateUrl: './password-lost.component.html',
  styleUrl: './password-lost.component.scss'
})
export class PasswordLostComponent {

  constructor(private mailService: MailService, private toastService: ToastService) {
  }

  formGroup = new FormGroup({
    email: new FormControl('', [Validators.required, Validators.pattern(/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i)])
  })

  onSubmit() {
    if (this.formGroup.valid) {
      this.mailService.sendReset(this.formGroup.value).subscribe(res => {
          if (res) {
            this.toastService.showSuccess('Mail envoyer')
          }
        }
      )
    }
  }
}
