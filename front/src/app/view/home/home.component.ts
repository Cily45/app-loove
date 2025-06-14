import {
  Component, OnInit, signal
} from '@angular/core';
import {Profil, UserService} from '../../services/api/user.service';
import {MatIconModule} from '@angular/material/icon';
import {MatchService} from '../../services/api/match.service';
import {RouterLink} from '@angular/router';
import {getAge} from '../../component/helper';
import {ReportComponent} from '../../component/report/report.component';
import {SubscriptionService} from '../../services/api/subscription.service';
import {ToastService} from '../../services/toast.service';
import {environment} from '../../env';
import {ProfilComponent} from '../../component/profil/profil.component';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-home',
  imports: [
    MatIconModule,
    RouterLink,
    ReportComponent,
    ProfilComponent,
    ProfilComponent,

  ],
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss',
})
export class HomeComponent implements OnInit {
  profils = signal<Profil[]>([])
  index = 0
  isSubscribe = signal<boolean>(false)
  userProfil = signal<Profil>({
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

  constructor(private userService: UserService, private matchService: MatchService, private subscriptionService: SubscriptionService, private toastService: ToastService) {
  }

  ngOnInit(): void {
    this.reload()
  }

  get currentUserId(): number | undefined {
    return this.profils()[this.index]?.id;
  }

  skiped(): void {
    if (this.currentUserId !== undefined) {
      this.matchService.match({
        userId1: this.currentUserId,
        is_skiped: true
      }).subscribe();
    }
    this.index++
    if (this.index > 9) {
      this.reload()
    }
  }

  matched(): void {
    if (this.currentUserId !== undefined) {
      this.matchService.match({
        userId1: this.currentUserId,
        is_skiped: false
      }).subscribe();
    }
    this.index++
    if (this.index > 9) {
      this.reload()
    }
  }

  async reload() {
    this.index = 0
    this.userProfil.set(JSON.parse(<string>localStorage.getItem('profil')))

    if (this.userProfil().profil_photo !== null) {
      this.profils.set(await firstValueFrom(this.userService.getAllProfil()))
      this.isSubscribe.set(await firstValueFrom(this.subscriptionService.isSubscribe()))
    }
  }

  openProfil() {
    this.isProfilHidden.update(u => false)
    document.getElementById(`profil-${this.profils()[this.index].id}`)?.classList.remove('hidden')
  }

  openReport() {
    this.isProfilHidden.update(u => false)
    document.getElementById(`report-${this.profils()[this.index].id}`)?.classList.remove('hidden')
  }

  getAge = getAge;
  environment = environment;
  Math = Math;
}
