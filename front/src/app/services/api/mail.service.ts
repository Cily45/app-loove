import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class MailService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {
  }

  sendReset(email: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/reset-password`, email)
  }
}
