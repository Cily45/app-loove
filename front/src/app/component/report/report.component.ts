import {Component, input, OnInit, signal} from '@angular/core';
import {ReportReason, ReportReasonService} from '../../services/api/report-reason.service';
import {ReportService} from '../../services/api/report.service';
import {FormControl, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-report',
  imports: [
    ReactiveFormsModule,
  ],
  templateUrl: './report.component.html',
  standalone: true,
  styleUrl: './report.component.css'
})
export class ReportComponent implements OnInit{
  reportReasons = signal<ReportReason[]>([])
  id = input<number>(0)
report = new FormControl('', Validators.required)
  constructor(private reportReasonService : ReportReasonService, private reportService : ReportService, private toastService : ToastService
  ){}

  form = new FormGroup({
    report: this.report
  })

  ngOnInit(): void {
    this.reportReasonService.getAll().subscribe(list =>{
      this.reportReasons.set(list)
    })
  }

  close(){
    document.getElementById(`report-${this.id()}`)?.classList.add('hidden');
  }
  onSubmit(){
    if (this.report.value !== '' && this.report.value !== null) {
      let report = {
        id: this.id(),
        reason: parseInt(this.report.value)
      }
      this.reportService.createReport(report).subscribe( response => {
        if(response['error']){
          this.toastService.showError('Déjà signaler');
        }else{
          this.toastService.showSuccess('Signalement effectuer');
        }
        }
      )
    }
  }
}
