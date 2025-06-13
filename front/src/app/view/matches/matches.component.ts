import {Component, OnInit, signal} from '@angular/core';
import {SubscriptionService} from '../../services/api/subscription.service';
import {Profil, UserService} from '../../services/api/user.service';
import {MatchCardComponent} from '../../component/match-card/match-card.component';
import {firstValueFrom, Observable} from 'rxjs';

@Component({
  selector: 'app-matches',
  imports: [
    MatchCardComponent,
  ],
  templateUrl: './matches.component.html',
  styleUrl: './matches.component.scss'
})
export class MatchesComponent implements OnInit {
  isSubscribe = signal<boolean>(false)
  matchs = signal<Profil[]>([])

  constructor(
    private subcriptionService: SubscriptionService,
    private userService : UserService) {
  }

  async ngOnInit() {
      this.isSubscribe.set(await firstValueFrom(this.subcriptionService.isSubscribe()))
      this.matchs.set(await firstValueFrom(this.userService.getMatchsProfil()))
  }
}
