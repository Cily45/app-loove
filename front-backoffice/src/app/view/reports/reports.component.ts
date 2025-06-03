import {Component, OnInit, signal} from '@angular/core';
import {RouterLink} from "@angular/router";
import {ReportService, Report} from '../../services/api/report.service';
import {MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import {MatPaginatorModule, PageEvent} from '@angular/material/paginator';
import {log} from '@angular-devkit/build-angular/src/builders/ssr-dev-server';

@Component({
  selector: 'app-reports',
  imports: [
    RouterLink,
    MatTableModule,
    MatIconModule,
    MatPaginatorModule
  ],
  templateUrl: './reports.component.html',
  styleUrl: './reports.component.css'
})
export class ReportsComponent implements OnInit {
  reports = signal<Report[]>([])
  //{
  //     id: 0, reason: '', is_solved: false
  //   },
  displayedColumns: string[] = ['id', 'reason','date', 'is_solved','edit'];
  page = signal<number>(1)
  quantity = signal<number>(10)
  pageEvent?: PageEvent
  constructor(private reportService: ReportService) {
  }

  ngOnInit() {
    this.reportService.getAll(this.quantity(), this.page()).subscribe(list => {
        this.reports.set(list)
      }
    )
  }
  handlePageEvent(e: PageEvent) {
    this.pageEvent = e;
    this.quantity.set(e.pageSize);
    this.page.set(e.pageIndex + 1)
    this.reportService.getAll(this.quantity(), this.page()).subscribe(list => {
      this.reports.set(list)
    })
  }

}
