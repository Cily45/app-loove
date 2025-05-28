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

  constructor(private hobbiesService: HobbiesService, private subscriptionService : SubscriptionService) {
    for (const question of this.questions) {
      this.dogFormGroup.addControl(question.key, new FormControl([]))
    }
  }

  baseFormGroup = this._formBuilder.group({
    manCheck: new FormControl(''),
    womanCheck: new FormControl(''),
    otherCheck: new FormControl(''),
    minAge: new FormControl(25),
    maxAge: new FormControl(35),
    distance: new FormControl(''),
    photoCheck: new FormControl(''),
  })

  hobbiesFormGroup: FormGroup = this._formBuilder.group({})
  dogFormGroup: FormGroup = this._formBuilder.group({})


  ngOnInit(): void {
    this.hobbiesService.getAll().subscribe(list => {
      this.hobbies.set(list)
      const group: { [key: string]: FormControl } = {}
      for (let hobby of list) {
        group[hobby.id] = new FormControl()
      }
      this.hobbiesFormGroup = this._formBuilder.group(group)
    })
    this.subscriptionService.isSubscribe().subscribe(res =>{
      this.isSubscribe.set(res)
    })
  }

  onSubmit() {
    const form = {
      ...this.baseFormGroup.value,
      ...this.hobbiesFormGroup.value,
      ...this.dogFormGroup.value
    }
    console.log(form)
  }
}
