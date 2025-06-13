import {Component, AfterViewInit, ElementRef, ViewChild, input} from '@angular/core';
import {loadScript, PayPalNamespace} from '@paypal/paypal-js';
import {environment} from '../../env';
import {Price} from '../../services/api/price.service';
import {SubscriptionService} from '../../services/api/subscription.service';
import {ToastService} from '../../services/toast.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-paypal-button',
  templateUrl: './paypal-button.component.html',
})
export class PaypalButtonComponent implements AfterViewInit {
  @ViewChild('paypalRef', {static: true}) paypalRef!: ElementRef<HTMLDivElement>;
  private id = environment.paypalId
  price = input<Price | null>(null)

  constructor(
    private subscriptionService: SubscriptionService,
    private toastService: ToastService, private router : Router) {
  }

  async ngAfterViewInit() {
    const paypal: PayPalNamespace | null = await loadScript({
      clientId: this.id,
      currency: 'EUR',
    });

    if (paypal?.Buttons && this.price() !== null) {
      await paypal.Buttons({
        createOrder: (data, actions) => {
          return actions.order.create({
            intent: 'CAPTURE',
            purchase_units: [{
              amount: {
                currency_code: 'EUR',
                // @ts-ignore
                value: this.price().price.toString()
              }
            }]
          });
        },
        onApprove: async (data, actions) => {
          const details = await actions.order!.capture()

          if (details.status === "COMPLETED")
            this.subscriptionService.subscription(this.price()?.quantity).subscribe(res => {
              this.router.navigate(['/abonnement'])
              if (res) {
                this.toastService.showSuccess(`Votre compte à était créditer de ${this.price()?.quantity} mois de premium`)
              } else {
                this.toastService.showSuccess(`Votre compte à était créditer de ${this.price()?.quantity} mois de premium`)
              }
            })
        },
        onError: (err) => {
          console.error('Erreur PayPal :', err)
        },
      }).render(this.paypalRef.nativeElement)
    }
  }
}
