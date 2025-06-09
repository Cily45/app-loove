import {Component, signal} from '@angular/core';
import {Router, RouterLink, RouterLinkActive} from '@angular/router'
import {AuthService} from '../../../../services/auth/auth.service';
import {MatButtonModule} from '@angular/material/button';
import {MatIconModule} from '@angular/material/icon';


@Component({
  selector: 'app-side-nav',
  imports: [
    RouterLink,
    RouterLinkActive,
    MatButtonModule,
    MatIconModule
  ],
  templateUrl: './side-nav.component.html',
  styleUrl: './side-nav.component.scss'
})
export class SideNavComponent {
isSettingsOpen = signal<boolean>(false)
  constructor(
    public authService: AuthService
  ) {}

  onLogout() {
    this.authService.logout();
  }


}
