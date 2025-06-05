import {Component, inject, OnInit, signal} from '@angular/core'
import {
  MatAccordion,
  MatExpansionPanel,
  MatExpansionPanelHeader,
  MatExpansionPanelTitle
} from '@angular/material/expansion'
import {MatSliderModule} from '@angular/material/slider'
import {MatButtonModule} from '@angular/material/button';
import {FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule} from '@angular/forms';
import {MatCheckboxModule} from '@angular/material/checkbox';
import {MatInputModule} from '@angular/material/input';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatCardModule} from '@angular/material/card';
import {HobbiesService, Hobby} from '../../services/api/hobbies.service';
import {MatIconModule} from '@angular/material/icon';
import {SubscriptionService} from '../../services/api/subscription.service';
import {Gender, GenderService} from '../../services/api/gender.service';

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
  isSubscribe = signal<boolean>(false)
  private _formBuilder = inject(FormBuilder)

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

  baseFormGroup: FormGroup = this._formBuilder.group({
    minAge: new FormControl(25),
    maxAge: new FormControl(35),
    distance: new FormControl(80),
    genders: this._formBuilder.array([])
  })

  hobbiesFormGroup: FormGroup = this._formBuilder.group({})
  dogFormGroup: FormGroup = this._formBuilder.group({})

  constructor(
    private hobbiesService: HobbiesService,
    private subscriptionService: SubscriptionService,
    private genderService: GenderService
  ) {

    for (const question of this.questions) {
      this.dogFormGroup.addControl(question.key, new FormControl([]))
    }
  }

  ngOnInit(): void {
    this.hobbiesService.getAll().subscribe(list => {
      this.hobbies.set(list)
      const group: { [key: string]: FormControl } = {}
      for (let hobby of list) {
        group[hobby.id] = new FormControl(false)
      }
      this.hobbiesFormGroup = this._formBuilder.group(group)
    })

    this.subscriptionService.isSubscribe().subscribe(res => {
      this.isSubscribe.set(res)
    })

    this.genderService.getAll().subscribe(res => {
      this.genders.set(res)

      const genderControls: { [key: string]: FormControl } = {}
      for (let gender of res) {
        genderControls[`gender-${gender.id}`] = new FormControl(false)
      }
      this.baseFormGroup = this._formBuilder.group({
        ...this.baseFormGroup.controls,
        ...genderControls
      })
    })
  }

  onSubmit() {
    const baseValues = this.baseFormGroup.value
    const hobbiesValues = this.hobbiesFormGroup.value
    const dogValues = this.dogFormGroup.value

    const selectedGenders = this.genders()
      .filter(gender => baseValues[`gender-${gender.id}`])
      .map(gender => gender.id)

    const selectedHobbies = this.hobbies()
      .filter(hobby => hobbiesValues[hobby.id])
      .map(hobby => hobby.id)

    const form = {
      minAge: baseValues.minAge,
      maxAge: baseValues.maxAge,
      distance: baseValues.distance,
      selectedGenders: selectedGenders,
      selectedHobbies: selectedHobbies,
      dogPreferences: dogValues
    }

    console.log('Formulaire soumis:', form)
  }

  onDogOptionChange(questionKey: string, option: string, event: any) {
    const control = this.dogFormGroup.get(questionKey);
    if (control) {
      const currentValue = control.value || [];
      if (event.target.checked) {
        if (!currentValue.includes(option)) {
          control.setValue([...currentValue, option]);
        }
      } else {
        control.setValue(currentValue.filter((val: string) => val !== option));
      }
    }
  }
}
