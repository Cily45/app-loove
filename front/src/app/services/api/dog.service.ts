import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

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
    return this.https.get<Dog[]>(`${this.apiUrl}/dog/${id}`)
  }
}
