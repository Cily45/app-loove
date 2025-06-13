import {Component, OnInit} from '@angular/core';
import {UserService} from '../../services/api/user.service';
import {ActivatedRoute, Router, RouterLink} from '@angular/router';
import {MatButton} from '@angular/material/button';
import {ToastService} from '../../services/toast.service';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-confirm-email',
  imports: [
    RouterLink, MatButton,
  ],
  templateUrl: './confirm-email.component.html',
  styleUrl: './confirm-email.component.css'
})
export class ConfirmEmailComponent implements OnInit {

  constructor(private userService: UserService, private route: ActivatedRoute, private toastService: ToastService, private router: Router) {
  }

  async ngOnInit() {
    const token: string = <string>this.route.snapshot.paramMap.get('token')
    const res = await firstValueFrom(this.userService.updateVerify(token))
    if (res) {
      this.router.navigate(['/connection']);
      this.toastService.showSuccess('E-mail confirmé')
    } else {
      this.toastService.showError('E-mail non confirmé')
    }
  }
}
