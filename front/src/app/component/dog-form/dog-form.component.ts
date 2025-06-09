import { Component } from '@angular/core';
import {ReactiveFormsModule} from "@angular/forms";

@Component({
  selector: 'app-dog-form',
    imports: [
        ReactiveFormsModule
    ],
  templateUrl: './dog-form.component.html',
  styleUrl: './dog-form.component.scss'
})
export class DogFormComponent {

}
