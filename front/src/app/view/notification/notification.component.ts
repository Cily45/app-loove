import {Component, OnInit} from '@angular/core';
import {FormControl, FormGroup, ReactiveFormsModule} from '@angular/forms';
import {MatButton} from '@angular/material/button';
import {UserService} from '../../services/api/user.service';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-notification',
  imports: [
    MatButton,
    ReactiveFormsModule
  ],
  templateUrl: './notification.component.html',
  styleUrl: './notification.component.css'
})
export class NotificationComponent implements OnInit {
  notificationFormGroup = new FormGroup({
      message_push: new FormControl(true),
      message_email: new FormControl(true),
      match_push: new FormControl(true),
      match_email: new FormControl(true)
    }
  )

  constructor(private userService: UserService, private toastService: ToastService) {
  }

  ngOnInit() {
    const notifications = (JSON.parse(<string>localStorage.getItem('notifications')))
    this.notificationFormGroup.patchValue({
      message_push: !!notifications.message_push,
      message_email: !!notifications.message_email,
      match_push: !!notifications.match_push,
      match_email: !!notifications.match_email
    })
  }

  onSubmit() {
    const notificationData = {
      message_push: this.notificationFormGroup.value.message_push ? 1 : 0,
      message_email: this.notificationFormGroup.value.message_email ? 1 : 0,
      match_push: this.notificationFormGroup.value.match_push ? 1 : 0,
      match_email: this.notificationFormGroup.value.match_email ? 1 : 0
    }
    this.userService.update(notificationData).subscribe(res => {
      if (res) {
        localStorage.setItem('notifications', JSON.stringify(notificationData))
        this.toastService.showSuccess('Notifications mises à jour')
      } else {
        this.toastService.showSuccess('Erreur lors de la mise à jour des notifications')
      }
    })
  }
}
