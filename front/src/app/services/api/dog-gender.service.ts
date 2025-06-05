import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

export interface DogGender {
  name: string,
  id: number
}
@Injectable({
  providedIn: 'root'
})


export class DogGenderService {

  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getAll(): Observable<DogGender[]> {
    return this.http.get<DogGender[]>(`${this.apiUrl}/dog-genders`)
  }
}
