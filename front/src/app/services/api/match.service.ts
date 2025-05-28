import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class MatchService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  match(match: { userId1: number; is_skiped: boolean }){
    return this.http.post<string[]>(`${this.apiUrl}/match`, match)
  }

}

