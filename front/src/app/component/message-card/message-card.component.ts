import {Component, input} from '@angular/core';
import {Message} from '../../services/api/message.service';
import {NgClass} from '@angular/common';
import {environment} from '../../env';
import {getTime} from '../helper';

@Component({
  selector: 'app-message-card',
  imports: [
    NgClass
  ],
  templateUrl: './message-card.component.html',
  styleUrl: './message-card.component.scss'
})
export class MessageCardComponent{
  message = input<Message>({
    id: 0,
    message: '',
    date: '',
    hour: '',
    is_view: 0,
    sender_id: 0,
    receiver_id: 0,
    is_that_user: 0,
  })
  photo = input<string>()

getDatetime():string {
    let date = this.message()?.date.split('-').reverse().join('-')
    let hour = this.message()?.hour.substring(0,5)
    return date + " " + hour
}


  protected readonly environment = environment;
  protected readonly getTime = getTime;
}
