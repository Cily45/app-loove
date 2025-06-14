import { Component, effect, input, signal} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {Profil, UserService} from '../../services/api/user.service';
import {getAge} from '../helper';
import {environment} from '../../env';

@Component({
  selector: 'app-profil',
    imports: [
        FormsModule,
        ReactiveFormsModule
    ],
  templateUrl: './profil.component.html',
  styleUrl: './profil.component.css'
})
export class ProfilComponent{
id = input<number>(0)
  profil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: 'Utilisateur supprimé',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 0,
    is_banned: false,
    gender: '',
  })

   constructor(private userService : UserService) {
    effect(() => {
      if (this.id() !== 0) {
        this.userService.userProfil(this.id()).subscribe(profil => {
          if(profil) {
            this.profil.update(u => profil)
          }
        })
      }
    })
  }

  protected readonly getAge = getAge;
  protected readonly environment = environment;
}
