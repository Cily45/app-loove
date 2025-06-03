import {Injectable} from '@angular/core'
import {HttpClient} from '@angular/common/http'
import {map, Observable} from 'rxjs'
import {environment} from '../../env';

export interface Profil {
  id: number
  lastname: string
  firstname: string
  profil_photo: string
  description: string
  birthday: string
  match_code: number
  is_banned: boolean
}

export interface ProfilAdminView {
  id: number
  lastname: string
  firstname: string
  birthday: string
  email: string
}

@Injectable({
  providedIn: 'root'
})


export class UserService {
  private apiUrl = environment.apiUrl;

  constructor(private https: HttpClient) {
  }

  isMailUsed(email: string): Observable<boolean> {
    return this.https.post<{ used: boolean }>(`${this.apiUrl}/email`, {email})
      .pipe(map(response => response.used))
  }

  createUser(form: any) {
    return this.https.put<any>(`${this.apiUrl}/create-user`, form)
      .pipe(map(response => response))
  }

  userProfil(id: number): Observable<Profil> {
    return this.https.get<Profil>(`${this.apiUrl}/profil/${id}`)
  }

  allUserProfilMessage(): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/users-messages`)
  }

  getAllProfil(quantity: number, page: number): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/users/${quantity}/${page}`)
  }

  getMatchsProfil(): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/matchs`)
  }

  deleteUser(id: number) {
    return this.https.delete<any>(`${this.apiUrl}/delete/${id}`)
      .pipe(map(response => response))
  }

  getProfilAdmin(id: number): Observable<ProfilAdminView> {
    return this.https.get<ProfilAdminView>(`${this.apiUrl}/profil-admin/${id}`)
  }

  userUpdate(form: any) {
    return this.https.put<any>(`${this.apiUrl}/update-user-admin`, form)
      .pipe(map(response => response))
  }
}
