import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {FormGroup} from '@angular/forms';

export interface Price {
  id: number,
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

  update(prices: FormGroup): Observable<boolean>{
    return this.http.post<boolean>(`${this.apiUrl}/prices-update`, prices)
  }
}
