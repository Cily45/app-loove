import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {map, Observable} from 'rxjs';

export interface Dog {
  id :number,
  name: string,
  birthday :string,
  photo: string,
  size: number,
  temperament: number,
  gender: number,
}
@Injectable({
  providedIn: 'root'
})
export class DogService {
  private apiUrl = environment.apiUrl;

  constructor (private https: HttpClient) {
  }

  dogProfil(id: number): Observable<Dog[]> {
    return this.https.get<Dog[]>(`${this.apiUrl}/dogs/${id}`)
  }

  addDogs(form: any){
    return this.https.put<any>(`${this.apiUrl}/add-dogs/`, form).pipe(map(response => response))
  }
}
