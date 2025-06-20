import {Component, inject, OnInit, signal} from '@angular/core'
import {MatInputModule} from '@angular/material/input'
import {MatStepper, MatStepperModule} from '@angular/material/stepper'
import {MatButtonModule} from '@angular/material/button'
import {FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms'
import {MatFormFieldModule} from '@angular/material/form-field'
import {RouterLink} from '@angular/router'
import {BreakpointObserver} from '@angular/cdk/layout'
import {Gender, GenderService} from '../../services/api/gender.service'
import {birthDateValidator, matchPassword} from '../../component/validator'
import {MatIconModule} from '@angular/material/icon'
import {UserService} from '../../services/api/user.service'
import {MatSelectModule} from '@angular/material/select'
import {NgIf} from '@angular/common';
import {firstValueFrom} from 'rxjs';
import {SpinnerComponent} from '../../component/spinner/spinner.component';


@Component({
  selector: 'app-register',
  imports: [
    MatButtonModule,
    MatStepperModule,
    FormsModule,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    RouterLink,
    MatIconModule,
    MatSelectModule,
    NgIf,
    SpinnerComponent,
  ],
  templateUrl: './register.component.html',
  styleUrl: './register.component.scss'
})
export class RegisterComponent implements OnInit {
  stepperOrientation: 'horizontal' | 'vertical' = 'horizontal'
  private _formBuilder = inject(FormBuilder)
  genders: Gender[] = []
  hidePassword = signal(true)
  hideConfirm = signal(true)
  isLoading = true
  constructor(
    private breakpointObserver: BreakpointObserver,
    private genderService: GenderService,
    private userService: UserService
  ) {
  }

  firstFormGroup = this._formBuilder.group({
    lastname: new FormControl('', Validators.required),
    firstname: new FormControl('', Validators.required),
    birthday: new FormControl('', [Validators.required, birthDateValidator()])

  })

  secondFormGroup = this._formBuilder.group({
    gender: new FormControl('', Validators.required),
    sexualOrientation: new FormControl('', Validators.required)
  })

  thirdFormGroup = this._formBuilder.group({
    email: new FormControl('', [
      Validators.required,
      Validators.pattern(/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i)
    ]),
    password: new FormControl('', [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/)
    ]),
    passwordConfirm: new FormControl('', Validators.required)
  }, {validators: matchPassword()})

  isEditable = false


  async onSubmit(stepper: MatStepper) {
    if(!this.thirdFormGroup.valid){
      return
    }
    const emailControl = this.thirdFormGroup.get('email')
    const email: string = this.thirdFormGroup.get('email')?.value || 'error'
    this.isLoading = false
    let isEmailUsed = await firstValueFrom(this.userService.isMailUsed(email))
    this.isLoading = true

    if (!emailControl) {
      return
    }

    if (isEmailUsed) {
      emailControl.setErrors({'emailUsed': true})
      return
    }

    emailControl.setErrors(null)

    const form = {
      ...this.firstFormGroup.value,
      ...this.secondFormGroup.value,
      ...this.thirdFormGroup.value
    }

    this.isLoading = false
    const res = await firstValueFrom(this.userService.createUser(form))
    this.isLoading = true

    if (res) {
      stepper.next()
    }
  }

  async ngOnInit() {
    const res = await firstValueFrom(this.breakpointObserver.observe(['(min-width: 768px)']))
    this.stepperOrientation = res.matches ? 'horizontal' : 'vertical'
    this.genders = await firstValueFrom(this.genderService.getAll())
  }
}
