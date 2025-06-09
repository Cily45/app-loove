import {Component, OnInit, signal} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {MessageService, Message} from '../../services/api/message.service';
import {MatIconModule} from '@angular/material/icon';
import {Profil, UserService} from '../../services/api/user.service';
import {MessageCardComponent} from '../../component/message-card/message-card.component';
import {FormControl, ReactiveFormsModule, Validators} from '@angular/forms';
import {ReportComponent} from '../../component/report/report.component';
import {environment} from '../../env';
import {ProfilComponent} from '../../component/profil/profil.component';
import {log} from '@angular-devkit/build-angular/src/builders/ssr-dev-server';

@Component({
  selector: 'app-message',
  imports: [
    MatIconModule,
    MessageCardComponent,
    ReactiveFormsModule,
    ReportComponent,
    ProfilComponent,
  ],
  templateUrl: './message.component.html',
  styleUrl: './message.component.scss'
})
export class MessageComponent implements OnInit {
  profil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: 'Utilisateur supprimer',
    profil_photo: 'user-delete.webp',
    description: '',
    birthday: '',
    match_code: 2,
    gender: '',
    distance_km: 0
  })
  messages = signal<Message[]>([])
  message: FormControl<string> = new FormControl('',{
    nonNullable: true,
    validators: [Validators.required],
  });
  userProfil = signal<Profil>({
    id: 0,
    lastname: '',
    firstname: '',
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 2,
    gender: '',
    distance_km: 0
  })


  constructor(private route: ActivatedRoute, private messagesService: MessageService, private userService: UserService
  ) {
  }

  ngOnInit(): void {
    let id = parseInt(<string>this.route.snapshot.paramMap.get('id'))
    this.userProfil.set(JSON.parse(<string>localStorage.getItem('profil')))

    this.messagesService.messagesById(this.userProfil().id, id).subscribe(list => {
      this.messages.set(list.reverse())

      if (id !== null) {
        this.userService.userProfil(id).subscribe(res => {
          if (res) {
            this.profil.set(res)
          }
        })
      }

      if (this.messages().length > 0 && this.messages()[this.messages().length-1].is_view !== 1 && this.messages()[this.messages().length-1].is_that_user !== 1) {
        this.messagesService.updateMessage(this.messages()[this.messages().length-1].id).subscribe()
      }
    })

  }

  onSubmit() {
    let data = {
      "receiver_id": this.profil().id,
      "message": this.message.value
    }
    this.messagesService.sendMessage(data).subscribe()
    this.message.reset()
  }

  openProfil(){
    document.getElementById(`profil-${this.profil().id}`)?.classList.remove('hidden');
  }

  openReport(){
    document.getElementById(`report-${this.profil().id}`)?.classList.remove('hidden');
  }

  protected readonly environment = environment;
}
