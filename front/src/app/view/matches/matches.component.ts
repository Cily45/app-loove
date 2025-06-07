import {Component, OnInit, signal} from '@angular/core';
import {SubscriptionService} from '../../services/api/subscription.service';
import {Profil, UserService} from '../../services/api/user.service';
import {MatchCardComponent} from '../../component/match-card/match-card.component';

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
  constructor(private subcriptionService: SubscriptionService, private userService : UserService) {
  }

  ngOnInit() {
    this.subcriptionService.isSubscribe().subscribe(res => {
      this.isSubscribe.set(res)
    })
    this.userService.getMatchsProfil().subscribe(list => {
      this.matchs.set(list)
    })
  }
}
