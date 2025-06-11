import {Component, inject, OnInit, signal} from '@angular/core'
import {NavigationEnd, Router, RouterOutlet} from '@angular/router'
import {FooterComponent} from './component/template/footer/footer.component'
import {filter} from 'rxjs'
import {MatSidenavModule} from '@angular/material/sidenav'
import {VisibilityService} from './services/visibility/visibility.service'
import {BreakpointObserver} from '@angular/cdk/layout'
import {SideNavComponent} from './component/template/tablet-desktop/side-nav/side-nav.component'
import {MenuMobileComponent} from './component/template/mobile/menu-mobile/menu-mobile.component'
import {AuthService} from './services/auth/auth.service';
import {PusherService} from './services/pusher.service';
import {ToastService} from './services/toast.service';

@Component({
  selector: 'app-root',
  imports: [
    RouterOutlet,
    FooterComponent,
    FooterComponent,
    MatSidenavModule,
    SideNavComponent,
    MenuMobileComponent,
  ],
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss'
})
export class AppComponent implements OnInit {
  title = 'Pawfect match'
  protected readonly visibilityService = inject(VisibilityService)
  isMobile = signal(true)
  isAuth = signal(false)

  constructor(private router: Router,
              private breakpointObserver: BreakpointObserver,
              private authService: AuthService,
              ) {
  }

  ngOnInit() {
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(() => {
        window.scrollTo({top: 0, behavior: 'smooth'})
        this.isAuth.set(this.authService.isAuthenticated())

      })

    this.breakpointObserver.observe(['(min-width: 768px)'])
      .subscribe(result => {
        this.isMobile.set(!result.matches)
      })
  }
}
