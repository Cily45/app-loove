import {Component, input} from '@angular/core';
import {Profil} from '../../services/api/user.service';
import {getAge} from '../helper';
import {MatIconModule} from '@angular/material/icon';
import {RouterLink} from '@angular/router';
import {NgClass} from '@angular/common';
import {ReportComponent} from '../report/report.component';
import {environment} from '../../env';

@Component({
  selector: 'app-match-card',
  imports: [
    MatIconModule,
    RouterLink,
    NgClass,
    ReportComponent
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
    match_code: 2
  })
  isSubcribe = input<boolean>()
  protected readonly getAge = getAge;
  protected readonly environment = environment;
}
