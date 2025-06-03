import {Component, OnInit, signal} from '@angular/core';
import {MatIconModule} from '@angular/material/icon';
import {RouterLink} from '@angular/router';
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatInputModule} from '@angular/material/input'
import {MatExpansionModule} from '@angular/material/expansion';
import {MatButton} from '@angular/material/button';
import {ReactiveFormsModule} from '@angular/forms';
import {Profil} from '../../services/api/user.service';
import {environment} from '../../env';
import {HobbiesService, Hobby} from '../../services/api/hobbies.service';
import {Gender, GenderService} from '../../services/api/gender.service';
import {getDate} from '../../component/helper';

@Component({
  selector: 'app-profil',
  imports: [MatIconModule, RouterLink, MatIconModule, MatFormFieldModule, MatInputModule, MatExpansionModule, MatButton, ReactiveFormsModule],
  templateUrl: './profil.component.html',
  styleUrl: './profil.component.scss'
})
export class ProfilComponent implements OnInit {
  userProfil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 2
  })
  profilPhoto = signal<string>('')
  hobbies = signal<Hobby[]>([])
genders = signal<Gender[]>([])
  constructor(private hobbiesService : HobbiesService, private genderService : GenderService) {
  }

  ngOnInit() {
    this.userProfil.set(JSON.parse(<string>localStorage.getItem('profil')))
    this.profilPhoto.set(environment.apiUrl + "/uploads/photo-user/" + this.userProfil()?.profil_photo)
    this.hobbiesService.getAllByUser().subscribe(list => {
      this.hobbies.set(list)
    })
    this.genderService.getAll().subscribe(list => {
      console.log(list)
      this.genders.set(list)
    })

  }

  protected readonly environment = environment;

  updateUserProfil<K extends keyof Profil>(key: K, event: Event) {
    const target = event.target as HTMLInputElement;
    if (target) {
      this.userProfil.update(p => ({
        ...p,
        [key]: target.value as Profil[K]
      }));
    }
  }

  updatePhoto(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target) {
      if (target.files && target.files.length > 0) {
        const file = target.files[0]
        const objectUrl = URL.createObjectURL(file);

         this.profilPhoto.set(objectUrl)
        console.log(target.files)
      }
    }
  }

  protected readonly getDate = getDate;
}
