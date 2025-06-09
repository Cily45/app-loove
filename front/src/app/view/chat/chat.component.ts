import {Component, OnInit, signal} from '@angular/core';
import {ChatCardComponent} from '../../component/chat-card/chat-card.component';
import {MessageService, Message} from '../../services/api/message.service';


@Component({
  selector: 'app-chat-desktop',
  imports: [
    ChatCardComponent
  ],
  templateUrl: './chat.component.html',
  styleUrl: './chat.component.scss'
})
export class ChatComponent implements OnInit {
  messages = signal<Message[]>([])

  constructor(
    private messagesService: MessageService
  ) {
  }

  ngOnInit(): void {
    this.messagesService.messages().subscribe(list => {
      if (list.length > 0) {
        this.messages.set(list)
      }
    })
  }


  protected readonly Object = Object;
}
