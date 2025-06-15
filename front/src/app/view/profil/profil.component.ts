import {Component, OnInit, signal} from '@angular/core';
import {MatIconModule} from '@angular/material/icon';
import {RouterLink} from '@angular/router';
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatInputModule} from '@angular/material/input'
import {MatExpansionModule} from '@angular/material/expansion';
import {MatButton} from '@angular/material/button';
import {FormBuilder, FormGroup, FormArray, ReactiveFormsModule} from '@angular/forms';
import {Profil, UserService} from '../../services/api/user.service';
import {environment} from '../../env';
import {HobbiesService, Hobby} from '../../services/api/hobbies.service';
import {Gender, GenderService} from '../../services/api/gender.service';
import {getDate} from '../../component/helper';
import {ToastService} from '../../services/toast.service';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-profil',
  imports: [
    MatIconModule,
    RouterLink,
    MatIconModule,
    MatFormFieldModule,
    MatInputModule,
    MatExpansionModule,
    MatButton,
    ReactiveFormsModule],
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
    match_code: 2,
    gender: '',
    distance_km: 0,
  })
  profilPhoto = signal<string>('')
  hobbies = signal<Hobby[]>([])
  genders = signal<Gender[]>([])
  selectedPhoto = signal<File | null>(null)

  profilForm: FormGroup;

  constructor(
    private hobbiesService: HobbiesService,
    private genderService: GenderService,
    private fb: FormBuilder,
    private toastService: ToastService,
    private userService: UserService,
  ) {
    this.profilForm = this.fb.group({
      description: [''],
      profil_photo: [''],
      genderPreferences: this.fb.array([]),
      selectedHobbies: this.fb.array([])
    });
  }

  async ngOnInit() {
    this.userProfil.set(JSON.parse(<string>localStorage.getItem('profil')))
    this.profilPhoto.set(environment.apiUrl + "/uploads/photo-user/" + this.userProfil()?.profil_photo)

    this.profilForm.patchValue({
      firstname: this.userProfil().firstname,
      lastname: this.userProfil().lastname,
      birthday: this.userProfil().birthday,
      description: this.userProfil().description,
      profil_photo: this.userProfil().profil_photo
    });

    this.hobbies.set(await firstValueFrom(this.hobbiesService.getAllByUser(this.userProfil().id)))
    this.initializeHobbies()

    this.genders.set(await firstValueFrom(this.genderService.get(this.userProfil().id)))
    this.initializeGenders()
  }

  get genderPreferencesArray() {
    return this.profilForm.get('genderPreferences') as FormArray
  }

  get selectedHobbiesArray() {
    return this.profilForm.get('selectedHobbies') as FormArray
  }

  initializeGenders() {
    const genderArray = this.genderPreferencesArray
    this.genders().forEach(gender => {
      genderArray.push(this.fb.control(gender.selected === 1))
    });
  }

  initializeHobbies() {
    const hobbiesArray = this.selectedHobbiesArray
    this.hobbies().forEach(hobby => {
      hobbiesArray.push(this.fb.control(hobby.selected === 1))
    });
  }

  onGenderChange(index: number, event: Event) {
    const target = event.target as HTMLInputElement;
    this.genderPreferencesArray.at(index).setValue(target.checked)
  }

  onHobbyChange(index: number, event: Event) {
    const target = event.target as HTMLInputElement;
    this.selectedHobbiesArray.at(index).setValue(target.checked)
  }

  updateUserProfil<K extends keyof Profil>(key: K, event: Event) {
    const target = event.target as HTMLInputElement
    if (target) {
      this.userProfil.update(p => ({
        ...p,
        [key]: target.value as Profil[K]
      }))

      this.profilForm.patchValue({
        [key]: target.value
      });
    }
  }

  updatePhoto(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target && target.files && target.files.length > 0) {
      const file = target.files[0]

      const reader = new FileReader()
      reader.onload = (e) => {
        const img = new Image()
        img.onload = () => {
          const canvas = document.createElement('canvas')
          canvas.width = img.width
          canvas.height = img.height
          const ctx = canvas.getContext('2d')
          if (!ctx) return

          ctx.drawImage(img, 0, 0)

          canvas.toBlob((blob) => {
            if (blob) {
              const objectUrl = URL.createObjectURL(blob)
              this.profilPhoto.set(objectUrl)

              const webpFile = new File([blob], file.name.replace(/\.\w+$/, '.webp'), {type: 'image/webp'})

              this.profilForm.patchValue({
                profil_photo: webpFile
              })

              this.selectedPhoto.set(webpFile)
            }
          }, 'image/webp', 0.8)
        }
        if (e.target?.result) {
          img.src = e.target.result as string
        }
      }
      reader.readAsDataURL(file)
    }
  }


  async onSubmit() {
    const count = this.genderPreferencesArray.value.filter((val: boolean) => val).length
    if (this.profilForm.valid && count > 0) {
      if (this.selectedPhoto() !== null) {
        const formData = new FormData();
        // @ts-ignore
        formData.append('photo', this.selectedPhoto());
        this.userService.updatePhoto(formData).subscribe()
      }
      const formData = {
        ...this.profilForm.value,
        selectedGenders: this.genders().filter((_, index) =>
          this.genderPreferencesArray.at(index).value
        ),
        selectedHobbies: this.hobbies().filter((_, index) =>
          this.selectedHobbiesArray.at(index).value
        )
      }
      const res = await firstValueFrom(this.userService.updateUser(formData))
      localStorage.setItem('profil', JSON.stringify(res));
      if (res) {
        this.toastService.showSuccess('Votre profil à été mis à jours')
      } else {
        this.toastService.showSuccess('Echec de la mis à jours de votre profil')
      }
    } else {
      this.toastService.showError('Veuillez renseigner tout les champs')
    }
  }

  protected readonly environment = environment
  protected readonly getDate = getDate;
}
