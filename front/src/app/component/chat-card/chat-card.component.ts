import {Component, input, OnInit, signal} from '@angular/core';
import {MatIconModule} from '@angular/material/icon';
import {NgClass} from '@angular/common';
import {RouterLink} from '@angular/router';
import {Message} from '../../services/api/message.service';
import {environment} from '../../env';

@Component({
  selector: 'app-chat-card',
  imports: [
    MatIconModule,
    NgClass,
    RouterLink
  ],
  templateUrl: './chat-card.component.html',
  styleUrl: './chat-card.component.scss'
})
export class ChatCardComponent implements OnInit {
  message = input<Message>()
  time = signal<string>('')


  ngOnInit(): void {
      if(this.message()) {
        // @ts-ignore
        this.time.set(this.getTime(this.message().date, this.message().hour))
      }
  }

  getTime(dateString: string, hoursString: string): string {
    let date = new Date(`${dateString}T${hoursString}`)
    let currentDate = new Date()
    let timePassed = (currentDate.getTime() - date.getTime()) / 1000
    let year = 60 * 60 * 24 * 30 * 365
    let month = 60 * 60 * 24 * 30
    let day = 60 * 60 * 24
    let hour = 60 * 60
    let minute = 60

    if (timePassed >= year) {
      return `${Math.floor(timePassed / year)} années`;
    } else if (timePassed >= month) {
      return `${Math.floor(timePassed / month)} mois`;
    } else if (timePassed >= day) {
      return `${Math.floor(timePassed / day)} jours`;
    } else if (timePassed >= hour) {
      return `${Math.floor(timePassed / hour)} heures`;
    } else if (timePassed >= minute) {
      return `${Math.floor(timePassed / minute)} minutes`;
    }

    return "now"
  }

  protected readonly environment = environment;
}
