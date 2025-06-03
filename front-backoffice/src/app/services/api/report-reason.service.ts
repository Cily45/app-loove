import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {map, Observable} from 'rxjs';

export interface ReportReason {
  id: number,
  name: string
}
@Injectable({
  providedIn: 'root'
})
export class ReportReasonService {
  private apiUrl = environment.apiUrl;

  constructor(private https: HttpClient) {}

  getAll()  {
    return this.https.get(`${this.apiUrl}/report-reason`)
  }


}
