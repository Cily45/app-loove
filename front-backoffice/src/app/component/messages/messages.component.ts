import { Component, effect, input, OnInit, signal} from '@angular/core';
import {Message, MessageService} from '../../services/api/message.service';

@Component({
  selector: 'app-messages',
  imports: [],
  templateUrl: './messages.component.html',
  styleUrl: './messages.component.css'
})
export class MessagesComponent {
  id0 = input<number>(0)
  id1 = input<number>(0)
  messages = signal<Message[]>([])
  constructor(private messageService: MessageService) {
    effect(() => {
      if (this.id0() !== 0 || this.id1() !== 0) {
        this.messageService.messagesById(this.id0(), this.id1()).subscribe(list => {
          this.messages.set(list)
        })
      }
    })
  }
}
