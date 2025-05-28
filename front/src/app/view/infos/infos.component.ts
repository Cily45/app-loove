import { Component} from '@angular/core'
import {MatExpansionModule} from '@angular/material/expansion';
import { RouterLink } from '@angular/router'

@Component({
  selector: 'app-infos',
  imports: [MatExpansionModule, RouterLink],
  templateUrl: './infos.component.html',
  styleUrl: './infos.component.scss'
})
export class InfosComponent {
}
