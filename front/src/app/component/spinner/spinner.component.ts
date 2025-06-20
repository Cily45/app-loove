import {Component, OnInit, signal} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NgClass} from '@angular/common';

@Component({
  selector: 'app-spinner',
  imports: [
    NgClass
  ],
  templateUrl: './spinner.component.html',
  styleUrl: './spinner.component.css'
})
export class SpinnerComponent implements OnInit{
  isNavbar = signal<boolean>(false)

  constructor(private route: ActivatedRoute) {
  }

  ngOnInit(): void {
    const initial = this.route.snapshot.data['hideAsideMenu'];
    this.isNavbar.set(!!initial);

    this.route.data.subscribe(data => {
      this.isNavbar.set(!!data['hideAsideMenu']);
    });

  }
}
