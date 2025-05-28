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
import {GeolocationService} from '@ng-web-apis/geolocation';
import {ToastService} from '../../services/toast.service';
import {environment} from '../../env';
import {log} from '@angular-devkit/build-angular/src/builders/ssr-dev-server';


@Component({
  selector: 'app-home',
  imports: [
    MatIconModule,
    RouterLink,
    ReportComponent,
  ],
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss',

})
export class HomeComponent implements OnInit {
  profils = signal<Profil[]>([])
  index = 0
  isSubscribe = signal<boolean>(false)


  constructor(private userService: UserService, private matchService: MatchService, private subscriptionService: SubscriptionService,  private toastService : ToastService) {
  }

  ngOnInit(): void {
    this.userService.getAllProfil().subscribe(list => {
        this.profils.set(list)
      }
    )
    this.subscriptionService.isSubscribe().subscribe(res => {
      this.isSubscribe.set(res)
    })

    navigator.geolocation.getCurrentPosition(
      (position) => {
        const longitude = position.coords.longitude
        const latitude = position.coords.latitude
        const location = `POINT(${latitude} ${longitude})`
        this.userService.updateLocation({location : location}).subscribe(res =>{
          if(res){
            this.toastService.showSuccess("Geolocalisation réussi")
          }else{
            this.toastService.showError("Geolocalisation échoué")

          }
        })
      }
    )


  }

  get currentUserId(): number | undefined {
    console.log(this.profils()[this.index]?.id)
    return this.profils()[this.index]?.id;
  }

  leftAnimation(): void {
    if (this.currentUserId !== undefined) {
      this.matchService.match({
        userId1: this.currentUserId,
        is_skiped: false
      }).subscribe();
    }
    this.index++
  }

  rightAnimation(): void {
    if (this.currentUserId !== undefined) {
      this.matchService.match({
        userId1: this.currentUserId,
        is_skiped: true
      }).subscribe();
    }
    this.index++
  }

  protected readonly getAge = getAge;
  protected readonly environment = environment;
}
