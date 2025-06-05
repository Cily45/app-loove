import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from "@angular/router";
import {MatTableModule} from '@angular/material/table';
import {Profil, UserService} from '../../services/api/user.service';
import {MatIconModule} from '@angular/material/icon';
import {MatPaginatorModule, PageEvent} from '@angular/material/paginator';
import {ToastService} from '../../services/toast.service';
import {BannedService} from '../../services/api/banned.service';

@Component({
  selector: 'app-users',
  imports: [
    RouterLink, MatTableModule, MatIconModule, MatPaginatorModule
  ],
  templateUrl: './users.component.html',
  styleUrl: './users.component.css'
})

export class UsersComponent implements OnInit {
  displayedColumns: string[] = ['id', 'firstname', 'lastname', 'is_banned', 'suppress'];
  users = signal<Profil[]>([{
    id: 0, lastname: '', firstname: '', is_banned: false,
    profil_photo: '',
    description: '',
    birthday: '',
    match_code: 0,
    gender: ''
  },])
  page = signal<number>(1)
  quantity = signal<number>(10)
  length = signal<number>(100)
  pageEvent?: PageEvent

  constructor(private bannedService: BannedService, private userService: UserService, private toastService: ToastService) {
  }

  ngOnInit() {
    this.userService.getAllProfil(this.quantity(), this.page()).subscribe(list => {
      this.users.set(list)
    })
    this.userService.count().subscribe(res => {
      this.length.set(res)
    })
  }

  handlePageEvent(e: PageEvent) {
    this.pageEvent = e;
    this.quantity.set(e.pageSize);
    this.page.set(e.pageIndex + 1)
    this.userService.getAllProfil(this.quantity(), this.page()).subscribe(list => {
      this.users.set(list)
    })
  }

  banned(id: number, isBaned: boolean) {
    if (isBaned) {
      if (confirm(`Êtes-vous sûr de vouloir débannir l'utilisateur n°${id}?`)) {
        this.bannedService.delete(id).subscribe(res => {
          if (res) {
            this.toastService.showSuccess(`Utilisateur n°${id} n'est plus banni`)
            this.userService.getAllProfil(this.quantity(), this.page()).subscribe(list => {
              this.users.set(list)
            })
          } else {
            this.toastService.showError('Erreur lors du débannissement')
          }
        })
      }
    } else {
      if (confirm(`Êtes-vous sûr de vouloir bannir l'utilisateur n°${id} pendant 1 mois?`)) {
        this.bannedService.add(id, 1).subscribe(res => {
          if (res) {
            this.toastService.showSuccess(`Utilisateur n°${id} est maintenant banni`)
            this.userService.getAllProfil(this.quantity(), this.page()).subscribe(list => {
              this.users.set(list)
            })
          } else {
            this.toastService.showError('Erreur lors de la suppression')
          }
        })
      }
    }

  }

  delete(id: number) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l\'utilisateur n°${id}?`)) {
      this.userService.deleteUser(id).subscribe(res => {
          if (res) {
            this.toastService.showSuccess(`Utilisateur n°${id} est maintenant supprimer`)
            this.userService.getAllProfil(this.quantity(), this.page()).subscribe(list => {
              this.users.set(list)
            })
          } else {
            this.toastService.showError('Erreur lors de la suppression')
          }
        }
      )
    }
  }


}
