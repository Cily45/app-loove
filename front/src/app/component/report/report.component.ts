import {Component, input, OnChanges, signal, SimpleChanges} from '@angular/core';
import {ReportReason, ReportReasonService} from '../../services/api/report-reason.service';
import {ReportService} from '../../services/api/report.service';
import {FormControl, FormGroup, ReactiveFormsModule, Validators} from '@angular/forms';
import {ToastService} from '../../services/toast.service';
import {firstValueFrom} from 'rxjs';

@Component({
  selector: 'app-report',
  imports: [
    ReactiveFormsModule,
  ],
  templateUrl: './report.component.html',
  standalone: true,
  styleUrl: './report.component.css'
})
export class ReportComponent implements OnChanges {
  reportReasons = signal<ReportReason[]>([])
  id = input<number>(0)
  report = new FormControl('', Validators.required)
  hidden = input<boolean>(true)

  constructor(
    private reportReasonService: ReportReasonService,
    private reportService: ReportService,
    private toastService: ToastService
  ) {
  }

  form = new FormGroup({
    report: this.report
  })

  async ngOnChanges(changes: SimpleChanges) {
    if (!changes['hidden'].currentValue) {
      this.reportReasons.set(await firstValueFrom(this.reportReasonService.getAll()))
    }
  }

  close() {
    document.getElementById(`report-${this.id()}`)?.classList.add('hidden');
  }

  async onSubmit() {
    if (this.report.value !== '' && this.report.value !== null) {
      let report = {
        id: this.id(),
        reason: parseInt(this.report.value)
      }
      const response = await firstValueFrom(this.reportService.createReport(report))
      if (response['error']) {
        this.toastService.showError('Déjà signaler');
      } else {
        this.toastService.showSuccess('Signalement effectuer');
      }
    }
  }
}
