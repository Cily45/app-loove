import {
  AfterViewChecked,
  AfterViewInit,
  Component,
  ElementRef,
  input,
  OnChanges,
  signal,
  SimpleChanges,
  ViewChild
} from '@angular/core';
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
    const hiddenChange = changes['hidden'];
    const idChange = changes['id'];
    if ((idChange && idChange.currentValue !== idChange.previousValue) || (hiddenChange && !changes['hidden'].currentValue)) {
      if (this.id() !== 0) {
        const profil = await firstValueFrom(this.userService.userProfil(this.id()))
        const hobby = await firstValueFrom(this.hobbiesService.getAllByUser(this.profil().id))
        const gendersPreference = await firstValueFrom(this.genderService.get(this.id()))
        const dogs = await firstValueFrom(this.dogService.dogProfil(this.id()))

        this.profil.update(() => profil)
        this.hobbies.update(() => hobby)
        this.gendersPreference.update(() => gendersPreference)
        this.dogs.update(() => dogs)
      }
    }
  }

protected readonly getAge = getAge;
protected readonly environment = environment;
  protected readonly Date = Date;
}
