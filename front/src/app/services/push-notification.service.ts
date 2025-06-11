import { Injectable } from '@angular/core';
import { SwPush } from '@angular/service-worker';

@Injectable({ providedIn: 'root' })
export class PushNotificationService {
  readonly VAPID_PUBLIC_KEY = 'VOTRE_CLÉ_PUBLIQUE_VAPID';

  constructor(private swPush: SwPush) {}

  subscribeToNotifications() {
    if (!this.swPush.isEnabled) {
      console.warn('Service Worker non activé');
      return;
    }

    this.swPush.requestSubscription({
      serverPublicKey: this.VAPID_PUBLIC_KEY
    }).then(subscription => {
      // Envoyer cette souscription au serveur
      console.log('Souscription OK', subscription);
    }).catch(err => console.error('Erreur de souscription :', err));
  }

  listenToMessages() {
    this.swPush.messages.subscribe(message => {
      console.log('Message push reçu', message);
    });
  }
}
