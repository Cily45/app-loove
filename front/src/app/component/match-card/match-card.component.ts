import {Component, input} from '@angular/core';
import {Profil} from '../../services/api/user.service';
import {getAge} from '../helper';
import {MatIconModule} from '@angular/material/icon';
import {RouterLink} from '@angular/router';
import {NgClass} from '@angular/common';
import {ReportComponent} from '../report/report.component';
import {environment} from '../../env';
import {ProfilComponent} from '../profil/profil.component';

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
  profil  = input<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 2,
    gender: '',
  })

  openProfil(){
    document.getElementById(`profil-${this.profil().id}`)?.classList.remove('hidden');
  }

  openReport(){
    document.getElementById(`report-${this.profil().id}`)?.classList.remove('hidden');
  }
  isSubcribe = input<boolean>()
  protected readonly environment = environment;
  protected readonly document = document;
}
