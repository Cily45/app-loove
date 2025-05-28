import {Component, OnInit, signal} from '@angular/core';
import { NgxPayPalModule } from 'ngx-paypal';
import {PaypalButtonComponent} from '../../component/paypal-button/paypal-button.component';
import {Price, PriceService} from '../../services/api/price.service';

@Component({
  selector: 'app-new-subscription',
  imports: [
    NgxPayPalModule,
    PaypalButtonComponent,
    PaypalButtonComponent
  ],
  templateUrl: './new-subscription.component.html',
  styleUrl: './new-subscription.component.css'
})
export class NewSubscriptionComponent implements OnInit{
  prices = signal<Price[]>([])
  price = signal<Price | null>(null)
  constructor(private priceService : PriceService) {
  }
  ngOnInit() {
    this.priceService.getAll().subscribe(list => {
        this.prices.set(list)
      }
    )
  }

  setPrice(price :Price) :void {
    this.price.set(price)
  }

  protected readonly Math = Math;
}
