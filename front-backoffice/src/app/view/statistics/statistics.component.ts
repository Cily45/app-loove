import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from '@angular/router';
import {MatExpansionModule} from '@angular/material/expansion';
import {StatisticsService, Stats} from '../../services/api/statistics.service';

@Component({
  selector: 'app-statistics',
  imports: [
    MatExpansionModule,
    RouterLink
  ],
  templateUrl: './statistics.component.html',
  styleUrl: './statistics.component.css'
})
export class StatisticsComponent implements OnInit{
  statistics = signal<Stats>({
    users: 0,
    messagesSend: 0,
    messagesSendToday: 0,
    reports: 0,
    reportUnsolved: 0,
    matchs: 0,
    subcrib: 0
  })


constructor(public statisticsService : StatisticsService) {
}
  ngOnInit() :void {
    this.statisticsService.get().subscribe(list =>{
       this.statistics.set(list)
      }
    )
  }
}
