import {Routes} from '@angular/router';
import {LoginComponent} from './view/login/login.component';
import {RegisterComponent} from './view/register/register.component'
import {InfosComponent} from './view/infos/infos.component'
import {ContactComponent} from './view/contact/contact.component'
import {FaqComponent} from './view/faq/faq.component'
import {AboutComponent} from './view/about/about.component'
import {PasswordLostComponent} from './view/password-lost/password-lost.component'
import {HomeComponent} from './view/home/home.component'
import {ChatComponent} from './view/chat/chat.component'
import {FilterComponent} from './view/filter/filter.component'
import {authGuard} from './services/auth/auth.guard'
import {MessageComponent} from './view/message/message.component';
import {MatchesComponent} from './view/matches/matches.component';
import {SettingsComponent} from './view/settings/settings.component';
import {ProfilComponent} from './view/profil/profil.component';
import {AccountComponent} from './view/account/account.component';
import {NotificationComponent} from './view/notification/notification.component';
import {SubscriptionComponent} from './view/subscription/subscription.component';
import {NewSubscriptionComponent} from './view/new-subscription/new-subscription.component';
import {DogProfilComponent} from './view/dog-profil/dog-profil.component';
import {ConfirmEmailComponent} from './view/confirm-email/confirm-email.component';

export const routes: Routes = [
  {
    title: 'Connection',
    path: 'connection',
    component: LoginComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    title: 'Inscription',
    path: 'inscription',
    component: RegisterComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    title: 'Informations et conditions',
    path: 'informations',
    component: InfosComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    title: 'Contact',
    path: 'contact',
    component: ContactComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    title: 'Faq',
    path: 'faq',
    component: FaqComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    title: 'A propos de nous',
    path: 'a-propos-de-nous',
    component: AboutComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    title: 'Mot de passe oublié',
    path: 'oublie-mot-de-passe',
    component: PasswordLostComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    title: 'Accueil',
    path: 'accueil',
    component: HomeComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Match',
    path: 'match',
    component: MatchesComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Parametre',
    path: 'parametre',
    component: SettingsComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Mon profil',
    path: 'profil',
    component: ProfilComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Mes chiens',
    path: 'chiens',
    component: DogProfilComponent,
    canActivate: [authGuard]
  },
  {
    title: 'Messagerie',
    path: 'messagerie',
    component: ChatComponent,
    canActivate: [authGuard],
  }, {
    title: 'Discussion',
    path: 'messagerie/discussion/:id',
    component: MessageComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Compte',
    path: 'compte',
    component: AccountComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Filtres',
    path: 'filtres',
    component: FilterComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Notifications',
    path: 'notifications',
    component: NotificationComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Abonnement',
    path: 'abonnement',
    component: SubscriptionComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Abonnement',
    path: 'nouvel-abonnement',
    component: NewSubscriptionComponent,
    canActivate: [authGuard],
  },
  {
    title: 'Confirmation de mail',
    path: 'confirmation/:token',
    component: ConfirmEmailComponent,
    data: {
      hideFooter: true,
      hideAsideMenu: true
    },
  },
  {
    path: '',
    redirectTo: 'accueil',
    pathMatch: 'full'
  },
  {
    path: '**',
    redirectTo: 'connection',
    pathMatch: 'full'
  }
]
