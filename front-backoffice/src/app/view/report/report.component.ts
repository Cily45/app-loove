import {Component, OnInit, signal} from '@angular/core';
import {ActivatedRoute, Router, RouterLink} from "@angular/router";
import {ReportService, Report} from '../../services/api/report.service';
import {MatButton, MatButtonModule} from '@angular/material/button';
import {ProfilComponent} from '../../component/profil/profil.component';
import {MessagesComponent} from '../../component/messages/messages.component';
import {FormsModule} from '@angular/forms';
import {ToastService} from '../../services/toast.service';
import {UserService} from '../../services/api/user.service';
import {BannedService} from '../../services/api/banned.service';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-report',
  imports: [
    RouterLink,
    MatButton,
    ProfilComponent,
    MessagesComponent,
    FormsModule
  ],
  templateUrl: './report.component.html',
  styleUrl: './report.component.css'
})

export class ReportComponent implements OnInit {
  report = signal<Report>({
    id: 0,
    reason: '',
    is_solved: false,
    date: '',
    complainant_id: 0,
    accused_id: 0,
  })
  selectedSanction: string = 'Rien';
  selectedTime: number = 1;


  constructor(
    private reportService: ReportService,
    private bannedService: BannedService,
    private route: ActivatedRoute,
    private userService: UserService,
    private toastService: ToastService,
    private router: Router
  ) {
  }

  ngOnInit() {
    let id: number = parseInt(<string>this.route.snapshot.paramMap.get('id'))

    this.reportService.get(id).subscribe(res => {
      this.report.set(res)
    })
  }

 async onSubmit() {
    let isActionValidate = false
    switch (this.selectedSanction) {
      case "Avertissement": {
          isActionValidate = true
          const res = await firstValueFrom(this.reportService.sendWarning({"id" : this.report().accused_id, "reason" : this.report().reason}))
        if (res) {
          this.toastService.showSuccess(`L'utilisateur n°${this.report().accused_id} a bien été averti par email.`)
        } else {
          this.toastService.showError('Une erreur est survenue')
        }
        break
      }
      case "Bannissement": {
        if (confirm(`Êtes-vous sûr de vouloir bannir l'utilisateur n°${this.report().accused_id} pendant ${this.selectedTime} mois?`)) {
          isActionValidate = true
          const res = await firstValueFrom(this.bannedService.add(this.report().accused_id, this.selectedTime))
          if (res) {
            this.toastService.showSuccess(`L'utilisateur n°${this.report().accused_id} a été supprimé`)
          } else {
            this.toastService.showError('Une erreur est survenue')
          }
        }
        break
      }
      case "Suppression de compte": {
        if (confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur n°${this.report().accused_id} ?`)) {
          isActionValidate = true
          const res = await firstValueFrom(this.userService.deleteUser(this.report().accused_id))
          if (res) {
            this.toastService.showSuccess(`L'utilisateur n°${this.report().accused_id} a été supprimé`)
          } else {
            this.toastService.showError('Une erreur est survenue')
          }
        }
        break
      }
      case "Rien": {
        isActionValidate = true
        break
      }
    }
    if (isActionValidate) {
      const res = await firstValueFrom(this.reportService.update(this.report().id))
      if (res) {
        this.toastService.showSuccess('Signalement traité')
        this.router.navigate(['/signalements'])
      } else {
        this.toastService.showError('Une erreur est survenue')
      }
    }
  }
}
