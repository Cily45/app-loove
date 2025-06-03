import { Component } from '@angular/core';
import {MatIconModule} from '@angular/material/icon';
import {RouterLink, RouterLinkActive} from '@angular/router'

@Component({
  selector: 'app-menu-mobile',
  imports: [MatIconModule, RouterLink, RouterLinkActive],
  templateUrl: './menu-mobile.component.html',
  styleUrl: './menu-mobile.component.scss'
})
export class MenuMobileComponent {

}
