import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

export interface Price {
  price: number;
  quantity: number;
}
@Injectable({
  providedIn: 'root'
})
export class PriceService {
  private apiUrl = environment.apiUrl;
  constructor(private http: HttpClient) {}

  getAll(): Observable<Price[]> {
    return this.http.get<Price[]>(`${this.apiUrl}/prices`)
  }
}
