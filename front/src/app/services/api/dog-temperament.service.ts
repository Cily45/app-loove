import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

export interface DogTemperament {
  name: string,
  id: number
  selected: number,
}
@Injectable({
  providedIn: 'root'
})
export class DogTemperamentService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getAll(): Observable<DogTemperament[]> {
    return this.http.get<DogTemperament[]>(`${this.apiUrl}/dog-temperaments`)
  }
}
