import {Component, OnInit, signal} from '@angular/core';
import {ChatCardComponent} from '../../component/chat-card/chat-card.component';
import {MessageService, Message, MessageCard} from '../../services/api/message.service';
import {firstValueFrom} from 'rxjs';
import {SpinnerComponent} from '../../component/spinner/spinner.component';


@Component({
  selector: 'app-chat-desktop',
  imports: [
    ChatCardComponent,
    SpinnerComponent
  ],
  templateUrl: './chat.component.html',
  styleUrl: './chat.component.scss'
})
export class ChatComponent implements OnInit {
  messages = signal<MessageCard[]>([])
  isLoading = true

  constructor(
    private messagesService: MessageService
  ) {
  }

  async ngOnInit() {
    this.isLoading = false
    const messages = await firstValueFrom(this.messagesService.messages())
    this.isLoading = true
      if (messages.length > 0) {
        this.messages.update(u => messages)
      }
  }


  protected readonly Object = Object;
}
