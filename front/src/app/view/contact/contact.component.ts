import {Component} from '@angular/core';
import {MatButtonModule} from '@angular/material/button';
import {FormsModule, ReactiveFormsModule, FormGroup, FormControl, Validators} from '@angular/forms'
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatInputModule} from '@angular/material/input'
import {MatIconModule} from '@angular/material/icon'
import {RouterLink, RouterLinkActive} from '@angular/router'
import {NgIf} from '@angular/common';
import {MailService} from '../../services/api/mail.service';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-contact',
  imports: [
    MatButtonModule,
    FormsModule,
    MatFormFieldModule, MatInputModule, FormsModule, ReactiveFormsModule, MatIconModule, RouterLink, RouterLinkActive, NgIf
  ],
  templateUrl: './contact.component.html',
  styleUrl: './contact.component.scss'
})
export class ContactComponent {
  formGroup = new FormGroup({
    firstname: new FormControl('', [Validators.required]),
    lastname: new FormControl('', [Validators.required]),
    email: new FormControl('', [Validators.required, Validators.pattern(/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i)
    ]),
    select: new FormControl('', [Validators.required]),
    textarea: new FormControl('', [Validators.required]),
  })

  constructor(private mailService: MailService, private toastService: ToastService) {
  }

  onSubmit() {
    if (this.formGroup.valid) {
      this.mailService.sendContact(this.formGroup.value).subscribe(res => {
        if (res) {
          this.toastService.showSuccess('Demande envoyée au support');
        } else {
          this.toastService.showError('Erreur lors de l\'envoi de la demande');
        }
      })
    }
  }
}
