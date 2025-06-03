import { Injectable } from '@angular/core'
import { HttpClient } from '@angular/common/http'
import { map, Observable } from 'rxjs'
import {environment} from '../../env';

export interface Profil {
  id: number
  lastname: string
  firstname: string
  profil_photo: string
  description: string
  birthday: string
  match_code: number
}
@Injectable({
  providedIn: 'root'
})


export class UserService {
  private apiUrl = environment.apiUrl;

  constructor (private https: HttpClient) {
  }

  isMailUsed (email: string): Observable<boolean> {
    return this.https.post<{ used: boolean }>(`${this.apiUrl}/email`, { email })
    .pipe(map(response => response.used))
  }

  createUser(form: any) {
    return this.https.put<any>(`${this.apiUrl}/create-user`, form)
      .pipe(map(response => response))
  }

  updateLocation(location :any) {
    return this.https.post<any>(`${this.apiUrl}/update-location`, location)
      .pipe(map(response => response));
  }

  userProfil(id: number): Observable<Profil> {
    return this.https.get<Profil>(`${this.apiUrl}/profil/${id}`)
  }

  allUserProfilMessage(): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/users-messages`)
  }

  getAllProfil(): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/profils`)
  }

  getMatchsProfil(): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/matchs`)

  }

}
