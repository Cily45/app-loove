import {Component, input, OnInit, signal} from '@angular/core';
import {MatIconModule} from '@angular/material/icon';
import {NgClass} from '@angular/common';
import {RouterLink} from '@angular/router';
import {MessageCard} from '../../services/api/message.service';
import {environment} from '../../env';
import {getTime} from '../helper';

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
  message = input<MessageCard>()
  time = signal<string>('')

  ngOnInit(): void {
      if(this.message()) {
        // @ts-ignore
        this.time.set(getTime(this.message().date, this.message().hour))
      }
  }

  protected readonly environment = environment;
}
