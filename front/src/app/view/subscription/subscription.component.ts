import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from '@angular/router';
import {MatButtonModule} from '@angular/material/button';
import {SubscriptionInfo, SubscriptionService} from '../../services/api/subscription.service';
import {getDate} from '../../component/helper';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-subscription',
  imports: [
    RouterLink,
    MatButtonModule
  ],
  templateUrl: './subscription.component.html',
  styleUrl: './subscription.component.css'
})
export class SubscriptionComponent implements OnInit {
  isSubscribe = signal<boolean>(false)
  subscriptionInfo = signal<SubscriptionInfo>({
    begin_date: '',
    end_date: '',
  })

  constructor(private subscriptionService: SubscriptionService) {
  }

  async ngOnInit() {
    const isSub = await firstValueFrom(this.subscriptionService.isSubscribe());
    const info = await firstValueFrom(this.subscriptionService.info());

    this.subscriptionInfo.update(u => info)
    this.isSubscribe.update(u => isSub)
  }

  protected readonly getDate = getDate;
}
