import { Component } from '@angular/core';
import {MatExpansionModule} from '@angular/material/expansion';
import { RouterLink } from '@angular/router'

@Component({
  selector: 'app-faq',
  imports: [MatExpansionModule, RouterLink],
  templateUrl: './faq.component.html',
  styleUrl: './faq.component.scss'
})
export class FaqComponent {

}
