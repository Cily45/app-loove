import { Injectable } from '@angular/core';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';

export interface Stats {
  users: number
  messagesSend: number
  messagesSendToday: number
  reports: number
  reportUnsolved: number
  matchs: number
  subcrib: number
  inflow: number
  yearInflow: MonthlyInflow[]
}

export interface MonthlyInflow {
  mois: string;
  revenu_total: number;
}
@Injectable({
  providedIn: 'root'
})
export class StatisticsService {

  private apiUrl = environment.apiUrl

  constructor(private https: HttpClient) {}

  get() :Observable<Stats> {
    return this.https.get<Stats>(`${this.apiUrl}/statistics`)
  }
}
