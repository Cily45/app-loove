import {Routes} from '@angular/router';
import {LoginComponent} from './view/login/login.component';
import {PricesComponent} from './view/prices/prices.component';
import {UsersComponent} from './view/users/users.component';
import {ReportsComponent} from './view/reports/reports.component';
import {StatisticsComponent} from './view/statistics/statistics.component';
import {UserComponent} from './view/user/user.component';
import {authGuard} from './services/auth/auth.guard';
import {ReportComponent} from './view/report/report.component';

export const routes: Routes = [
  {
    title: 'Connection',
    path: 'connection',
    component: LoginComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    }
  },
  {
    title: 'Utilisateur',
    path: 'utilisateur',
    component: UserComponent,
    canActivate: [authGuard]

  },
  {
    title: 'Tarification',
    path: 'tarification',
    component: PricesComponent,
    canActivate: [authGuard]
  },
  {
    title: 'Utilisateurs',
    path: 'utilisateurs',
    component: UsersComponent,
    canActivate: [authGuard]
  },
  {
    title: 'Utilisateur',
    path: 'utilisateur/:id',
    component: UserComponent,
    canActivate: [authGuard]
  },
  {
    title: 'Signalements',
    path: 'signalements',
    component: ReportsComponent,
    canActivate: [authGuard]
  },
  {
    title: 'Signalement',
    path: 'signalement/:id',
    component: ReportComponent,
    canActivate: [authGuard]
  },
  {
    title: 'Statistiques',
    path: 'statistiques',
    component: StatisticsComponent,
    canActivate: [authGuard]
  },
  {
    path: '',
    redirectTo: 'statistiques',
    pathMatch: 'full'
  },
  {
    path: '**',
    redirectTo: 'statistiques',
    pathMatch: 'full'
  }
]
