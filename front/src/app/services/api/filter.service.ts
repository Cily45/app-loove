import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class FilterService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getAll(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/get-filter`)
  }

  add(form: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/add-filter`, form)
  }
}
