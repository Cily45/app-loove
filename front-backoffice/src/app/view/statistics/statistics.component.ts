import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from '@angular/router';
import {MatExpansionModule} from '@angular/material/expansion';
import {StatisticsService, Stats} from '../../services/api/statistics.service';
import {firstValueFrom} from 'rxjs';
import {Chart, registerables} from 'chart.js';

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
    subcrib: 0,
    inflow: 0,
    yearInflow: []
  })

  constructor(public statisticsService: StatisticsService) {
  }

  async ngOnInit() {
    this.statistics.set(await firstValueFrom(this.statisticsService.get()))

    Chart.register(...registerables);
    const inflow = document.getElementById('inflow') as HTMLCanvasElement | null;

    if (inflow) {
      new Chart(inflow, {
        type: 'line',
        data: {
          labels: this.statistics().yearInflow.map(m => m.mois),
          datasets: [{
            label: 'Revenu mensuel',
            data: this.statistics().yearInflow.map(m => m.revenu_total),
            borderWidth: 1,
            fill: false,
            borderColor: 'rgb(106, 58, 113)',
            tension: 0.1
          }]
        }
      })

    }
    const report = document.getElementById('report') as HTMLCanvasElement | null;

    if (report) {
      new Chart(report, {
        type: 'doughnut',
        data: {
          labels: [
            "Signalement traité",
            "Signalements non traités"
          ]
          ,
          datasets: [{
            label: 'Signalement',
            data: [
              this.statistics().reports - this.statistics().reportUnsolved,
              this.statistics().reportUnsolved
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
        }
      });
    }
}

  protected readonly Math = Math;
}
