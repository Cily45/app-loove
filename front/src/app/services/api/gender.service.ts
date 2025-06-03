import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {environment} from '../../env';

export interface Gender {
  id: number;
  name: string;
}

@Injectable({
  providedIn: 'root'
})
export class GenderService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getAll(): Observable<Gender[]> {
    return this.http.get<Gender[]>(`${this.apiUrl}/genders`)
  }
}
