import {Component, input, OnChanges, signal, SimpleChanges} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {Profil, UserService} from '../../services/api/user.service';
import {HobbiesService, Hobby} from '../../services/api/hobbies.service';
import {getAge} from '../helper';
import {environment} from '../../env';
import {MatIconModule} from '@angular/material/icon';
import {Gender, GenderService} from '../../services/api/gender.service';
import {Dog, DogService} from '../../services/api/dog.service';
import {firstValueFrom} from 'rxjs';

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
export class ProfilComponent implements OnChanges {
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
  hidden = input<boolean>(true)

  constructor(
    private userService: UserService,
    private hobbiesService: HobbiesService,
    private dogService: DogService,
    private genderService: GenderService) {
  }


  close() {
    document.getElementById(`profil-${this.id()}`)?.classList.add('hidden');
  }

  async ngOnChanges(changes: SimpleChanges) {
    if (!changes['hidden'].currentValue) {
      if (this.id() !== 0) {
        this.profil.set(await firstValueFrom(this.userService.userProfil(this.id())))
        this.hobbies.set(await firstValueFrom(this.hobbiesService.getAllByUser(this.profil().id)))
        this.gendersPreference.set(await firstValueFrom(this.genderService.get(this.id())))
        this.dogs.set(await firstValueFrom(this.dogService.dogProfil(this.id())))
      }
    }
  }


protected readonly getAge = getAge;
protected readonly environment = environment;
  protected readonly Date = Date;
}
