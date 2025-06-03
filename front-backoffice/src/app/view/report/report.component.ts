import {Component, OnInit, signal} from '@angular/core';
import {ActivatedRoute, RouterLink} from "@angular/router";
import {ReportService, Report} from '../../services/api/report.service';

@Component({
  selector: 'app-report',
  imports: [
    RouterLink
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

  constructor(private reportService: ReportService, private route: ActivatedRoute) {}

  ngOnInit() {
    let id: number = parseInt(<string>this.route.snapshot.paramMap.get('id'))

    this.reportService.get(id).subscribe(res => {
      this.report.set(res)
      console.log(res)
    })
  }
}
