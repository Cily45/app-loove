import {AfterViewInit, Component, effect, input, signal} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {Profil, UserService} from '../../services/api/user.service';
import {HobbiesService} from '../../services/api/hobbies.service';
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
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 0,
    is_banned: false,
    gender: '',
  })

  constructor(private userService : UserService, private hobbiesService : HobbiesService) {
    effect(() => {
      if (this.id() !== 0) {
        this.userService.userProfil(this.id()).subscribe(profil => {
          this.profil.set(profil)
        })
      }
    })
  }

  protected readonly getAge = getAge;
  protected readonly environment = environment;
}
