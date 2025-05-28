import { Component } from '@angular/core';
import {MatButton} from "@angular/material/button";
import {MatFormField} from "@angular/material/form-field.d-BJpDa0PI";
import {MatInput, MatLabel} from "@angular/material/input";
import {MatStepLabel, MatStepperNext} from "@angular/material/stepper";
import {ReactiveFormsModule} from "@angular/forms";

@Component({
  selector: 'app-dog-form',
    imports: [
        MatButton,
        MatFormField,
        MatInput,
        MatLabel,
        MatStepLabel,
        MatStepperNext,
        ReactiveFormsModule
    ],
  templateUrl: './dog-form.component.html',
  styleUrl: './dog-form.component.scss'
})
export class DogFormComponent {

}
