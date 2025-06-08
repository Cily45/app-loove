import { Component } from '@angular/core';
import {Router, RouterLink} from '@angular/router';
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
    private router: Router
  ) {}
  onLogout() {
    this.authService.logout();
    this.router.navigate(['/connection']);
  }
}
