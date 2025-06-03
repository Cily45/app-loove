import {Component, OnInit, signal} from '@angular/core';
import {Dog, DogService} from '../../services/api/dog.service';
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatInputModule} from '@angular/material/input'
import {MatExpansionModule} from '@angular/material/expansion';
import {MatButton} from '@angular/material/button';
import {FormArray, FormBuilder, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import {MatIconModule} from '@angular/material/icon';

@Component({
  selector: 'app-dog-profil',
  imports: [
    MatExpansionModule, MatInputModule, MatFormFieldModule, MatButton, ReactiveFormsModule, MatIconModule
  ],
  templateUrl: './dog-profil.component.html',
  styleUrl: './dog-profil.component.scss'
})
export class DogProfilComponent implements OnInit {
  dogForm: FormGroup
  id = signal<number>(0)

  constructor(private fb: FormBuilder, private dogService : DogService) {
    this.dogForm = this.fb.group({
      dogs: this.fb.array([])
    })
  }

  get dogsFormArray() {
    return this.dogForm.get('dogs') as FormArray
  }

  questions = [
    {
      key: 'gender',
      label: 'Quel genre de chien votre/vos chien(s) tolère-t-il ?',
      options: [
        'Mâle castré',
        'Mâle non castré',
        'Femelle stérilisée',
        'Femelle non stérilisé'
      ]
    },
    {
      key: 'size',
      label: 'Quelle taille de chien votre/vos chien(s) tolère-t-il ?',
      options: [
        'Petit',
        'Moyen',
        'Grand',
        'Très grand'
      ]
    },
    {
      key: 'temperament',
      label: 'Quel(s) tempérament(s) de chien votre/vos chien(s) tolère-t-il ?',
      options: [
        'Très actif',
        'Calme mais joueur',
        'Calme et tranquille',
        'Nerveux / anxieux'
      ]
    }
  ]

  ngOnInit(): void {
    let dogs = JSON.parse(<string>localStorage.getItem('dogs'))
    dogs.forEach((dog: Partial<Dog>) => {
      this.dogsFormArray.push(this.createDogFormGroup(dog))
    });
    this.id.set(JSON.parse(<string>localStorage.getItem('profil')).id)

  }

  addDog() {
    this.dogsFormArray.push(this.createDogFormGroup())
  }

  deleteDog(index: number) {
    this.dogsFormArray.removeAt(index)
  }

  createDogFormGroup(dog?: Partial<Dog>): FormGroup {
    return this.fb.group({
      id: [dog?.id || 0],
      name: [dog?.name || '', Validators.required],
      birthday: [dog?.birthday || '', Validators.required],
      photo: [dog?.photo || ''],
      size: [dog?.size ?? 0],
      temperament: [dog?.temperament ?? 0],
      gender: [dog?.gender ?? 0]
    });
  }

  onSubmit() {
    this.dogService.addDogs(this.dogForm.value).subscribe(() => {
      this.dogService.dogProfil(this.id()).subscribe((list) => {
        localStorage.removeItem('dogs')
        localStorage.setItem('dogs', JSON.stringify(list))
      })
    })
  }
}
