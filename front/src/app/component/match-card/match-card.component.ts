import {Component, input, signal} from '@angular/core';
import {Profil} from '../../services/api/user.service';
import {MatIconModule} from '@angular/material/icon';
import {RouterLink} from '@angular/router';
import {NgClass} from '@angular/common';
import {ReportComponent} from '../report/report.component';
import {environment} from '../../env';
import {ProfilComponent} from '../profil/profil.component';
import {MatchService} from '../../services/api/match.service';

@Component({
  selector: 'app-match-card',
  imports: [
    MatIconModule,
    RouterLink,
    NgClass,
    ReportComponent,
    ProfilComponent
  ],
  templateUrl: './match-card.component.html',
  styleUrl: './match-card.component.css'
})

export class MatchCardComponent {
  isSkiped = signal<boolean>(false)
  profil  = input<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 2,
    gender: '',
    distance_km: 0,
  })
  isProfilHidden = signal<boolean>(true)
  isReportHidden = signal<boolean>(true)
  constructor(private matchService : MatchService) {
  }

  openProfil(){
    this.isProfilHidden.update(u => false)
    document.getElementById(`profil-${this.profil().id}`)?.classList.remove('hidden');
  }

  skiped(): void {
    if (this.profil().id !== undefined) {
      this.matchService.match({
        userId1: this.profil().id,
        is_skiped: true
      }).subscribe();
      this.isSkiped.update(s => true)
    }
  }

  matched(): void {
    if (this.profil().id !== undefined) {
      this.matchService.match({
        userId1: this.profil().id,
        is_skiped: false
      }).subscribe();
      this.profil().match_code = 0
    }
  }
  openReport(){
    this.isReportHidden.update(u => false)
    document.getElementById(`report-${this.profil().id}`)?.classList.remove('hidden');
  }
  isSubcribe = input<boolean>()
  protected readonly environment = environment;
  protected readonly document = document;
  protected readonly Date = Date;
}
