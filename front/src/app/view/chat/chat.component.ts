import {Component, OnInit, signal} from '@angular/core';
import {ChatCardComponent} from '../../component/chat-card/chat-card.component';
import {MessageService, Message, MessageCard} from '../../services/api/message.service';
import {firstValueFrom} from 'rxjs';


@Component({
  selector: 'app-chat-desktop',
  imports: [
    ChatCardComponent
  ],
  templateUrl: './chat.component.html',
  styleUrl: './chat.component.scss'
})
export class ChatComponent implements OnInit {
  messages = signal<MessageCard[]>([])

  constructor(
    private messagesService: MessageService
  ) {
  }

  async ngOnInit() {
    const messages = await firstValueFrom(this.messagesService.messages())
      if (messages.length > 0) {
        this.messages.set(messages)
      }
  }


  protected readonly Object = Object;
}
