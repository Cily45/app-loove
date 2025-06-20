import { Injectable } from '@angular/core';
import { environment } from '../env';
import * as PushNotifications from '@pusher/push-notifications-web';
@Injectable({
  providedIn: 'root'
})
export class PusherBeamsService {
  beamsClient: any;

  constructor() {
    this.beamsClient = new PushNotifications.Client({
      instanceId: environment.beamsId
    });
  }

  async start(id: any ) {
    await this.end()
    try {
      await this.beamsClient
        .start()
        .then(() => this.beamsClient.addDeviceInterest(`${id}`))
        .then(() => this.beamsClient.getDeviceInterests())

    } catch (error) {
      console.error('Erreur démarrage Beams:', error);
    }
  }
  async end() {
    try {
      await this.beamsClient.clearAllState();
    } catch (error) {
      console.error('Erreur démarrage Beams:', error);
    }
  }

}
