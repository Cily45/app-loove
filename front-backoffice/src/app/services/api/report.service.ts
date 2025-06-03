import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../env';
import {map, Observable} from 'rxjs';

export interface Report{
  id :number,
  reason :string,
  is_solved :boolean,
  date: string,
  complainant_id: number,
  accused_id: number,
}
@Injectable({
  providedIn: 'root'
})
export class ReportService {
  private apiUrl = environment.apiUrl;

  constructor(private https: HttpClient) {}
  getAll(quantity :number, page :number):Observable<Report[]>{
    return this.https.get<Report[]>(`${this.apiUrl}/reports/${quantity}/${page}`)
      .pipe(map(response => response));
  }

  get(id :number):Observable<Report>{
    return this.https.get<Report>(`${this.apiUrl}/report/${id}`)
      .pipe(map(response => response));
  }

}
