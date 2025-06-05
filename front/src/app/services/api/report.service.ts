import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../env';
import {map} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ReportService {
  private apiUrl = environment.apiUrl;

  constructor(private https: HttpClient) {}

  createReport(report :any) {
    return this.https.put<any>(`${this.apiUrl}/report`, report)
      .pipe(map(response => response));
  }

}
