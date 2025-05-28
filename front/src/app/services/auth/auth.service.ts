import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {catchError, firstValueFrom, Observable, throwError} from 'rxjs';
import {environment} from '../../env';
import {UserService} from '../api/user.service';
import {DogService} from '../api/dog.service';

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

  constructor(private https: HttpClient, private userService: UserService, private dogService : DogService) {
  }
  async login(credentials: LoginCredentials): Promise<boolean> {
    try {
      const response = await firstValueFrom(
        this.https.post<LoginResponse>(`${this.apiUrl}/login`, credentials)
      );
      if (response.token) {
        localStorage.setItem('authToken', response.token);
        this.userService.userProfil(response.id).subscribe((res) => {
          localStorage.setItem('profil', JSON.stringify(res))
        });
        this.dogService.dogProfil(response.id).subscribe((list) => {
          localStorage.setItem('dogs', JSON.stringify(list))
        })
        return true;
      }
      return false;
    } catch (err) {
      console.error('Erreur de connexion', err);
      return false;
    }
  }

  logout() {
    localStorage.removeItem('authToken')
    localStorage.removeItem('profil')

  }

  getToken(): string | null {
    return localStorage.getItem('authToken')
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem('authToken')
  }

}
