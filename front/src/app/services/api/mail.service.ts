import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {Hobby} from './hobbies.service';

@Injectable({
  providedIn: 'root'
})
export class MailService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {
  }

  getAll(test: any): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/mail-send`, test)
  }
}
