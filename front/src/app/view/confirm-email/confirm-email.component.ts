import {Component, OnInit} from '@angular/core';
import {UserService} from '../../services/api/user.service';
import {ActivatedRoute, RouterLink} from '@angular/router';
import { MatButton} from '@angular/material/button';
import {log} from '@angular-devkit/build-angular/src/builders/ssr-dev-server';

@Component({
  selector: 'app-confirm-email',
  imports: [
    RouterLink, MatButton,
  ],
  templateUrl: './confirm-email.component.html',
  styleUrl: './confirm-email.component.css'
})
export class ConfirmEmailComponent implements OnInit{

  constructor(private userService : UserService, private route: ActivatedRoute) {
  }

  ngOnInit() {
    const token :string = <string>this.route.snapshot.paramMap.get('token')
    console.log(token)
    this.userService.updateVerify(token).subscribe(res =>
    {
      console.log(res)
    })
  }
}
