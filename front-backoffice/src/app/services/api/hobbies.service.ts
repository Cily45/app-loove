import {Injectable} from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

export interface Hobby {
  id: string,
  name: string,
  icon: string,
  selected: number,
}

@Injectable({
  providedIn: 'root'
})
export class HobbiesService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {
  }

  getAll(): Observable<Hobby[]> {
    return this.http.get<Hobby[]>(`${this.apiUrl}/hobbies`)
  }

  getAllByUser(): Observable<Hobby[]> {
    return this.http.get<Hobby[]>(`${this.apiUrl}/user-hobbies`)
  }
}
