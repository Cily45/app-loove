import {Injectable} from '@angular/core';
import {map, Observable} from 'rxjs';
import {environment} from '../../env';
import {HttpClient} from '@angular/common/http';

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


  isSubscribe(): Observable<boolean> {
    return this.https.get<{ abonnement: boolean }>(`${this.apiUrl}/is-subscribe`)
      .pipe(map(response => response.abonnement));
  }

  info(): Observable<SubscriptionInfo> {
    return this.https.get<SubscriptionInfo>(`${this.apiUrl}/subscription-info`)
  }

  subscription(time: number | undefined): Observable<boolean> {
    return this.https.post<{
      abonnement: boolean
    }>(`${this.apiUrl}/subscription`, time).pipe(map(response => response.abonnement));
  }
}
