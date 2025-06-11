import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {firstValueFrom} from 'rxjs';
import {environment} from '../../env';
import {UserService} from '../api/user.service';
import {ToastService} from '../toast.service';
import {Router} from '@angular/router';
import {PusherService} from '../pusher.service';

interface LoginResponse {
  token?: string
  message?: string
  error?: string
  id: number
}

export interface LoginCredentials {
  email: string
  password: string

}

@Injectable({
  providedIn: 'root'
})


export class AuthService {
  private apiUrl = environment.apiUrl;

  constructor(private https: HttpClient,
              private userService: UserService,
              private pusher: PusherService,
              private toastService: ToastService,
              private router: Router) {
  }

  async login(credentials: LoginCredentials): Promise<boolean> {
    try {
      const response = await firstValueFrom(
        this.https.post<LoginResponse>(`${this.apiUrl}/login`, credentials)
      );

      if (response.token) {
        localStorage.setItem('authToken', response.token);
        this.userService.userProfil(response.id).subscribe((res) => {
          localStorage.setItem('profil', JSON.stringify(res));
          localStorage.setItem('email', credentials.email);

        })

        this.userService.getNotifications().subscribe(list => {
          localStorage.setItem('notifications', JSON.stringify(list));
        })
        navigator.geolocation.getCurrentPosition(
          (position) => {
            const longitude = position.coords.longitude
            const latitude = position.coords.latitude
            const location = `POINT(${latitude} ${longitude})`
            this.userService.updateLocation({location: location}).subscribe(res => {
              if (res) {
                this.toastService.showSuccess("Geolocalisation réussi")
                const id = (JSON.parse(<string>localStorage.getItem('profil'))).id
                const channelMessage = 'private-user-message-' + id
                const channelMatch = 'private-user-match-' + id
                this.toastService.showInfo('Vous avez un nouveau message!')
                this.pusher.subscribeMessageNotification(channelMessage, 'new-message', (data: any) => {
                  this.toastService.showInfo('Vous avez un nouveau match!')
                })

                this.pusher.subscribeMatchNotification(channelMatch, 'new-match', (data: any) => {
                  alert('it\'s a match')

                })
              } else {
                this.toastService.showError("Geolocalisation échoué")

              }
            })
          }
        )
        return true;
      }
      return false;
    } catch (err) {
      this.toastService.showError('Erreur de connexion. Veuillez vérifier votre adresse e-mail et, si le problème persiste, contactez le support.');
      return false;
    }
  }

  logout() {
    localStorage.removeItem('authToken')
    localStorage.removeItem('profil')
    localStorage.removeItem('notifications')
    localStorage.removeItem('email')
    this.router.navigate(['/connection']);
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem('authToken')
  }

}
