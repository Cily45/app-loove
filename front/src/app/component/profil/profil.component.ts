import {Component, effect, input, OnInit, signal} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {Profil, UserService} from '../../services/api/user.service';
import {HobbiesService, Hobby} from '../../services/api/hobbies.service';
import {getAge} from '../helper';
import {environment} from '../../env';
import {MatIconModule} from '@angular/material/icon';
import {Gender, GenderService} from '../../services/api/gender.service';
import {Dog, DogService} from '../../services/api/dog.service';

@Component({
  selector: 'app-profil',
  imports: [
    FormsModule,
    ReactiveFormsModule,
    MatIconModule
  ],
  templateUrl: './profil.component.html',
  styleUrl: './profil.component.css'
})
export class ProfilComponent {
  genre = [
    'Mâle castré',
    'Mâle non castré',
    'Femelle stérilisée',
    'Femelle non stérilisé'
  ]
  size = [
    'Petit',
    'Moyen',
    'Grand',
    'Très grand'
  ]
  temperament = [
    'Très actif',
    'Calme mais joueur',
    'Calme et tranquille',
    'Nerveux / anxieux'
  ]
  id = input<number>(0)
  profil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 0,
    gender: '',
    distance_km: 0
  })
  hobbies = signal<Hobby[]>([])
  gendersPreference = signal<Gender[]>([])
  dogs = signal<Dog[]>([])

  constructor(private userService: UserService, private hobbiesService: HobbiesService, private dogService: DogService, private genderService: GenderService) {
    effect(() => {
      if (this.id() !== 0) {
        this.userService.userProfil(this.id()).subscribe(profil => {
          this.profil.set(profil)
          this.hobbiesService.getAllByUser(this.profil().id).subscribe(list => {
            this.hobbies.set(list)
          })
          this.genderService.get(this.id()).subscribe(genders => {
            this.gendersPreference.set(genders)
          })
          this.dogService.dogProfil(this.id()).subscribe(dogs => {
            this.dogs.set(dogs)
          })
        })
      }
    })
  }

  close() {
    document.getElementById(`profil-${this.id()}`)?.classList.add('hidden');
  }

  protected readonly getAge = getAge;
  protected readonly environment = environment;
}
