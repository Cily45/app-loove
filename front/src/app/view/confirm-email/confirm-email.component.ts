import {Component, OnInit} from '@angular/core';
import {UserService} from '../../services/api/user.service';
import {ActivatedRoute, Router, RouterLink} from '@angular/router';
import { MatButton} from '@angular/material/button';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-confirm-email',
  imports: [
    RouterLink, MatButton,
  ],
  templateUrl: './confirm-email.component.html',
  styleUrl: './confirm-email.component.css'
})
export class ConfirmEmailComponent implements OnInit{

  constructor(private userService : UserService, private route: ActivatedRoute, private toastService : ToastService, private router : Router) {
  }

  ngOnInit() {
    const token :string = <string>this.route.snapshot.paramMap.get('token')
    this.userService.updateVerify(token).subscribe(res =>
    {
      if(res){
        this.router.navigate(['/connection']);
        this.toastService.showSuccess('E-mail confirmé')
      }else{
        this.toastService.showError('E-mail non confirmé')
      }
    })
  }
}
