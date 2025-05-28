import { Injectable } from '@angular/core';
import { loadScript, PayPalNamespace } from '@paypal/paypal-js';
import {environment} from '../env';

@Injectable({
  providedIn: 'root'
})
export class PaypalService {
  paypal!: PayPalNamespace | null;
  private id = environment.paypalId;


  async loadPaypal(): Promise<PayPalNamespace | null> {
    if (!this.paypal) {
      this.paypal = await loadScript({
        clientId: this.id,
        currency: 'EUR'
      });
    }
    return this.paypal;
  }
}
