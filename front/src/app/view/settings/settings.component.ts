import {Component} from '@angular/core';
import {RouterLink} from '@angular/router';
import {MatButtonModule} from '@angular/material/button';
import {AuthService} from '../../services/auth/auth.service';
import {MatIconModule} from '@angular/material/icon';

@Component({
  selector: 'app-settings',
  imports: [
    RouterLink,
    MatButtonModule,
    MatIconModule
  ],
  templateUrl: './settings.component.html',
  styleUrl: './settings.component.scss'
})
export class SettingsComponent {
  constructor(
    public authService: AuthService,
  ) {
  }

  onLogout() {
    this.authService.logout();
  }
}
