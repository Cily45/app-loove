import {Injectable, WritableSignal} from '@angular/core'
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
  gender: string
  distance_km: number
}
@Injectable({
  providedIn: 'root'
})


export class UserService {
  private apiUrl = environment.apiUrl;

  constructor (private https: HttpClient) {
  }

  isMailUsed (email: string): Observable<boolean> {
    return this.https.get<boolean>(`${this.apiUrl}/email/${email}`)
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

  deleteUser(id: number) {
    return this.https.delete<any>(`${this.apiUrl}/delete/${id}`)
      .pipe(map(response => response))
  }

  getAllProfil(): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/profils`)
  }

  getMatchsProfil(): Observable<Profil[]> {
    return this.https.get<Profil[]>(`${this.apiUrl}/matchs`)

  }

  updatePhoto(file: FormData): Observable<any>{
    return this.https.post<any>(`${this.apiUrl}/update-photo`, file)
  }

  updateUser(form: any) {
    return this.https.post<any>(`${this.apiUrl}/update-user`, form)
      .pipe(map(response => response))
  }

  updateVerify(token: string){
    return this.https.post<any>(`${this.apiUrl}/update-verify`, {"token": token})
      .pipe(map(response => response))  }

  updatePassword(form: any){
    return this.https.post<any>(`${this.apiUrl}/update-password`, form)
      .pipe(map(response => response))  }
}
