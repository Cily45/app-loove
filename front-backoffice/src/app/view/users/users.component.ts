import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from "@angular/router";
import {MatTableModule} from '@angular/material/table';
import {Profil, UserService} from '../../services/api/user.service';
import {MatIconModule} from '@angular/material/icon';
import {MatPaginatorModule, PageEvent} from '@angular/material/paginator';
import {ToastService} from '../../services/toast.service';

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
    match_code: 0
  },])
  page = signal<number>(1)
  quantity = signal<number>(10)
  pageEvent?: PageEvent

  constructor(private userService: UserService, private toastService: ToastService) {
  }

  ngOnInit() {
    this.userService.getAllProfil(this.quantity(), this.page()).subscribe(list => {
      this.users.set(list)
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

  delete(id: number) {
    this.userService.deleteUser(id).subscribe(res => {
        if (res) {
          this.toastService.showSuccess(`Utilisateur n°${id} supprimer`)
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
