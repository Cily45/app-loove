import {Component, OnInit, signal} from '@angular/core';
import {Dog, DogService} from '../../services/api/dog.service';
import {MatFormFieldModule} from '@angular/material/form-field'
import {MatInputModule} from '@angular/material/input'
import {MatExpansionModule} from '@angular/material/expansion';
import {MatButton} from '@angular/material/button';
import {FormArray, FormBuilder, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import {MatIconModule} from '@angular/material/icon';
import {ToastService} from '../../services/toast.service';
import {DogSize, DogSizeService} from '../../services/api/dog-size.service';
import {DogTemperament, DogTemperamentService} from '../../services/api/dog-temperament.service';
import {DogGender, DogGenderService} from '../../services/api/dog-gender.service';
import {RouterLink} from '@angular/router';

@Component({
  selector: 'app-dog-profil',
  imports: [
    MatExpansionModule, MatInputModule, MatFormFieldModule, MatButton, ReactiveFormsModule, MatIconModule, RouterLink
  ],
  templateUrl: './dog-profil.component.html',
  styleUrl: './dog-profil.component.scss'
})
export class DogProfilComponent implements OnInit {
  dogForm: FormGroup
  id = signal<number>(0)
  dogSizes = signal<DogSize[]>([])
  dogGenders = signal<DogGender[]>([])
  dogTemperaments = signal<DogTemperament[]>([])
  constructor(
    private dogSizeService : DogSizeService,
    private dogTemperamentService : DogTemperamentService,
    private dogGenderService : DogGenderService,
    private fb: FormBuilder,
    private dogService : DogService,
    private toastService : ToastService) {
    this.dogForm = this.fb.group({
      dogs: this.fb.array([])
    })
  }

  get dogsFormArray() {
    return this.dogForm.get('dogs') as FormArray
  }

  ngOnInit(): void {
    this.id.set(JSON.parse(<string>localStorage.getItem('profil')).id)
    this.dogSizeService.getAll().subscribe(list => {
      this.dogSizes.set(list)
    })

    this.dogGenderService.getAll().subscribe(list => {
      this.dogGenders.set(list)
    })

    this.dogTemperamentService.getAll().subscribe(list => {
      this.dogTemperaments.set(list)
    })

    this.dogService.dogProfil(this.id()).subscribe((list) => {
      list.forEach((dog: Partial<Dog>) => {
        this.dogsFormArray.push(this.createDogFormGroup(dog))
      });
    })
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
      size: [dog?.size_id || '', Validators.required],
      temperament: [dog?.temperament_id || '', Validators.required],
      gender: [dog?.gender_id || '', Validators.required]
    });
  }

  onSubmit() {
    if(this.dogsFormArray.length > 0 && this.dogForm.valid) {
      this.dogService.addDogs(this.dogForm.value).subscribe(() => {
        this.dogService.dogProfil(this.id()).subscribe((list) => {
          console.log(list)
          this.toastService.showSuccess('Màj de vos chien effectué')
        })
      })
    }else{
      this.toastService.showError('Veuillez renseignez tout les champs')
    }
  }
}
