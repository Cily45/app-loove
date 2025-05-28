import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from '@angular/router';
import {MatButtonModule} from '@angular/material/button';
import {SubscriptionInfo, SubscriptionService} from '../../services/api/subscription.service';
import {getDate} from '../../component/helper';

@Component({
  selector: 'app-subscription',
  imports: [
    RouterLink,
    MatButtonModule
  ],
  templateUrl: './subscription.component.html',
  styleUrl: './subscription.component.css'
})
export class SubscriptionComponent implements OnInit{
  isSubscribe = signal<boolean>(false)
  subscriptionInfo = signal<SubscriptionInfo>({
    begin_date: '',
    end_date: '',
  })
  constructor(private subscriptionService : SubscriptionService) {
  }
  ngOnInit() {
    this.subscriptionService.info().subscribe(res => {
      this.subscriptionInfo.set(res)
    })
    this.subscriptionService.isSubscribe().subscribe(res =>{
      this.isSubscribe.set(res)
    })
  }

  protected readonly getDate = getDate;
}
