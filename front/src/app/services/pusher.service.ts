import { Injectable } from '@angular/core';
import Pusher from 'pusher-js';
import {environment} from '../env';

@Injectable({
  providedIn: 'root'
})
export class PusherService {
  private apiUrl = environment.apiUrl;
  private pusher: Pusher;

  constructor() {
    const token = localStorage.getItem('authToken')

    this.pusher = new Pusher(environment.key, {
      cluster: 'eu',
      authEndpoint: `${this.apiUrl}/auth-pusher`,
      auth: {
        headers: {
          'X-Access-Token': `Bearer ${token}`
        }
      }
    });
  }

  subscribeMessageChannel(channelName: string, eventName: string, callback: (data: any) => void) {
    const channel = this.pusher.subscribe(channelName);
    channel.bind(eventName, callback);
  }

  subscribeMessageNotification(channelName: string, eventName: string, callback: (data: any) => void) {
    const channel = this.pusher.subscribe(channelName);
    channel.bind(eventName, callback);
  }

    subscribeMatchNotification(channelName: string, eventName: string, callback: (data: any) => void) {
    const channel = this.pusher.subscribe(channelName);
    channel.bind(eventName, callback);
  }

}
