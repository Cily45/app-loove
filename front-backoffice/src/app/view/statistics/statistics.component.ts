import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from '@angular/router';
import {MatExpansionModule} from '@angular/material/expansion';
import {StatisticsService, Stats} from '../../services/api/statistics.service';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-statistics',
  imports: [
    MatExpansionModule,
    RouterLink
  ],
  templateUrl: './statistics.component.html',
  styleUrl: './statistics.component.css'
})
export class StatisticsComponent implements OnInit {
  statistics = signal<Stats>({
    users: 0,
    messagesSend: 0,
    messagesSendToday: 0,
    reports: 0,
    reportUnsolved: 0,
    matchs: 0,
    subcrib: 0
  })


  constructor(public statisticsService: StatisticsService) {
  }

  async ngOnInit() {
    this.statistics.set(await firstValueFrom(this.statisticsService.get()))
  }
}
