import {Injectable} from '@angular/core';
import {map, Observable} from 'rxjs';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';
import {Price} from './price.service';

export interface SubscriptionInfo {
  begin_date: string
  end_date: string
}

@Injectable({
  providedIn: 'root'
})
export class SubscriptionService {
  private apiUrl = environment.apiUrl;

  constructor(private https: HttpClient) {
  }


  isSubscribe(){
    return this.https.get<boolean>(`${this.apiUrl}/is-subscribe`)
  }

  info(): Observable<SubscriptionInfo> {
    return this.https.get<SubscriptionInfo>(`${this.apiUrl}/subscription-info`)
  }

  subscription(price: Price | null): Observable<boolean> {
    return this.https.post<boolean>(`${this.apiUrl}/subscription`, price);
  }

}
