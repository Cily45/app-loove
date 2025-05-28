import {Component, OnInit, signal} from '@angular/core';
import {ActivatedRoute, Route} from '@angular/router';
import {MessageService, Message} from '../../services/api/message.service';
import {MatIconModule} from '@angular/material/icon';
import {Profil, UserService} from '../../services/api/user.service';
import {MessageCardComponent} from '../../component/message-card/message-card.component';
import {FormControl, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import {ReportComponent} from '../../component/report/report.component';
import {environment} from '../../env';

@Component({
  selector: 'app-message',
  imports: [
    MatIconModule,
    MessageCardComponent,
    ReactiveFormsModule,
    ReportComponent,
  ],
  templateUrl: './message.component.html',
  styleUrl: './message.component.scss'
})
export class MessageComponent implements OnInit {
  profil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 2
  })
  messages = signal<Message[]>([])
  message = new FormControl('', Validators.required);
  userProfil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 2
  })


  constructor(private route: ActivatedRoute, private messagesService: MessageService, private userService: UserService
  ) {
  }

  ngOnInit(): void {
    let id =  parseInt(<string>this.route.snapshot.paramMap.get('id'))
    this.userProfil.set(JSON.parse(<string>localStorage.getItem('profil')))
    this.messagesService.messagesById(id).subscribe(list => {
      this.messages.set(list.reverse())
      if (id!== null) {
        this.userService.userProfil(id).subscribe(list => {
          this.profil.set(list)})
      }
    })
    if ( this.messages().length > 0 && !this.messages()[this.messages().length].is_that_user && !this.messages()[this.messages().length].is_view) {
      this.messagesService.updateMessage(this.messages()[this.messages().length].id).subscribe()
    }
  }

  onSubmit() {

      let data = {
        "receiver_id": this.profil().id,
        "message": this.message.value
      }
      this.messagesService.sendMessage(data).subscribe()
      this.message.reset()
  }

  protected readonly environment = environment;
}
