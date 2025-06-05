import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BannedService {

  private apiUrl = environment.apiUrl;

  constructor (private https: HttpClient) {
  }

  add(id: number, time :number): Observable<any> {
    return this.https.put<any>(`${this.apiUrl}/banned-add`, {id, time})
  }

  delete(id: number): Observable<any> {
    return this.https.delete<any>(`${this.apiUrl}/banned-delete/${id}`)
  }
}
