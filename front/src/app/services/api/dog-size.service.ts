import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {DogGender} from './dog-gender.service';

export interface DogSize {
  name: string,
  id: number
}
@Injectable({
  providedIn: 'root'
})
export class DogSizeService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getAll(): Observable<DogSize[]> {
    return this.http.get<DogSize[]>(`${this.apiUrl}/dog-sizes`)
  }
}
