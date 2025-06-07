import {Component, inject, OnInit, signal} from '@angular/core'
import {
  MatAccordion,
  MatExpansionPanel,
  MatExpansionPanelHeader,
  MatExpansionPanelTitle
} from '@angular/material/expansion'
import {MatSliderModule} from '@angular/material/slider'
import {MatButtonModule} from '@angular/material/button';
import {FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule} from '@angular/forms';
import {MatCheckboxModule} from '@angular/material/checkbox';
import {MatInputModule} from '@angular/material/input';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatCardModule} from '@angular/material/card';
import {HobbiesService, Hobby} from '../../services/api/hobbies.service';
import {MatIconModule} from '@angular/material/icon';
import {SubscriptionService} from '../../services/api/subscription.service';
import {Gender, GenderService} from '../../services/api/gender.service';
import {DogSize, DogSizeService} from '../../services/api/dog-size.service';
import {DogTemperament, DogTemperamentService} from '../../services/api/dog-temperament.service';
import {DogGender, DogGenderService} from '../../services/api/dog-gender.service';
import {FilterService} from '../../services/api/filter.service';
import {UserService} from '../../services/api/user.service';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-filter',
  imports: [
    MatAccordion,
    MatExpansionPanel,
    MatExpansionPanelHeader,
    MatExpansionPanelTitle,
    MatSliderModule,
    MatButtonModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule,
    FormsModule,
    MatCheckboxModule,
    MatIconModule,
    ReactiveFormsModule
  ],
  templateUrl: './filter.component.html',
  styleUrl: './filter.component.scss'
})
export class FilterComponent implements OnInit {
  hobbies = signal<Hobby[]>([])
  genders = signal<Gender[]>([])
  dogSizes = signal<DogSize[]>([])
  dogGenders = signal<DogGender[]>([])
  dogTemperaments = signal<DogTemperament[]>([])
  isSubscribe = signal<boolean>(false)
  filterForm: FormGroup;

  constructor(
    private fb: FormBuilder,
    private subcriptionService: SubscriptionService,
    private toastService : ToastService,
    private filterService: FilterService
  ) {
    this.filterForm = this.fb.group({
      genderFilter: fb.array([]),
      hobbyFilter: fb.array([]),
      dogGenderFilter: fb.array([]),
      dogSizeFilter: fb.array([]),
      dogTemperamentFilter: fb.array([]),
      minAge: new FormControl(18),
      maxAge: new FormControl(110),
      distance: new FormControl(0)
    })
  }

  get genderPreferencesArray() {
    return this.filterForm.get('genderFilter') as FormArray
  }

  get selectedHobbiesArray() {
    return this.filterForm.get('hobbyFilter') as FormArray
  }

  get selectedSizeArray() {
    return this.filterForm.get('dogSizeFilter') as FormArray
  }

  get selectedTemperamentArray() {
    return this.filterForm.get('dogTemperamentFilter') as FormArray
  }

  get selectedGenderArray() {
    return this.filterForm.get('dogGenderFilter') as FormArray
  }

  get minAgeControl() {
    return this.filterForm.get('minAge') as FormControl
  }

  get maxAgeControl() {
    return this.filterForm.get('maxAge') as FormControl
  }

  get distanceControl() {
    return this.filterForm.get('distance') as FormControl
  }

  ngOnInit(): void {
    this.subcriptionService.isSubscribe().subscribe(res => {
      this.isSubscribe.set(res)
    })

    this.filterService.getAll().subscribe(list => {
      this.hobbies.set(list.hobbies)
      const hobbiesArray = this.selectedHobbiesArray
      this.hobbies().forEach(hobby => {
        hobbiesArray.push(this.fb.control(hobby.selected === 1))
      });

      this.genders.set(list.genders)
      const genderArray = this.genderPreferencesArray
      this.genders().forEach(gender => {
        genderArray.push(this.fb.control(gender.selected === 1))
      });

      this.dogSizes.set(list.dog_sizes)
      const dogSizeArray = this.selectedSizeArray
      this.dogSizes().forEach(dogSize => {
        dogSizeArray.push(this.fb.control(dogSize.selected === 1))
      });

      this.dogGenders.set(list.dog_genders)
      const dogGenderArray = this.selectedGenderArray
      this.dogGenders().forEach(dogGender => {
        dogGenderArray.push(this.fb.control(dogGender.selected === 1))
      });

      this.dogTemperaments.set(list.dog_temperaments)
      const dogTemperamentArray = this.selectedTemperamentArray
      this.dogTemperaments().forEach(dogTemperament => {
        dogTemperamentArray.push(this.fb.control(dogTemperament.selected === 1))
      });

      this.minAgeControl.setValue(list.filter.min_age)
      this.maxAgeControl.setValue(list.filter.max_age)
      this.distanceControl.setValue(list.filter.distance)
    })
  }

  onGenderChange(index: number, event: Event) {
    const target = event.target as HTMLInputElement;
    this.genderPreferencesArray.at(index).setValue(target.checked)
  }

  onHobbyChange(index: number, event: Event) {
    const target = event.target as HTMLInputElement;
    this.selectedHobbiesArray.at(index).setValue(target.checked)
  }

  onDogSizeChange(index: number, event: Event) {
    const target = event.target as HTMLInputElement;
    this.selectedSizeArray.at(index).setValue(target.checked)
  }

  onDogTemperamentChange(index: number, event: Event) {
    const target = event.target as HTMLInputElement;
    this.selectedTemperamentArray.at(index).setValue(target.checked)
  }

  onDogGenderChange(index: number, event: Event) {
    const target = event.target as HTMLInputElement;
    this.selectedGenderArray.at(index).setValue(target.checked)
  }

  onSubmit() {
    const formData = {
      genders: this.genders().filter((_, index) =>
        this.genderPreferencesArray.at(index).value
      ),
      hobbies: this.hobbies().filter((_, index) =>
        this.selectedHobbiesArray.at(index).value
      ),
      dogGender: this.dogGenders().filter((_, index) =>
        this.selectedGenderArray.at(index).value
      ),
      dogSize: this.dogSizes().filter((_, index) =>
        this.selectedSizeArray.at(index).value
      ),
      dogTemperament: this.dogTemperaments().filter((_, index) =>
        this.selectedTemperamentArray.at(index).value
      ),
      minAge: this.minAgeControl.value,
      maxAge: this.maxAgeControl.value,
      distance: this.distanceControl.value
    }
    this.filterService.add(formData).subscribe(res => {
      console.log(res)
    })
  }
}
