import { Injectable } from '@angular/core';
import { environment } from '../env';
import * as PushNotifications from '@pusher/push-notifications-web';
@Injectable({
  providedIn: 'root'
})
export class PusherBeamsService {
  beamsClient: any;
  private apiUrl = environment.apiUrl;

  constructor() {
    this.beamsClient = new PushNotifications.Client({
      instanceId: environment.beamsId
    });
  }

  async start() {
    try {
      await this.beamsClient.start();
      console.log('Beams client démarré.');
    } catch (error) {
      console.error('Erreur démarrage Beams:', error);
    }
  }
}
