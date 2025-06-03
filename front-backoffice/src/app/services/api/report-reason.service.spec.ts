import { TestBed } from '@angular/core/testing';

import { ReportReasonService } from './report-reason.service';

describe('ReportReasonService', () => {
  let service: ReportReasonService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ReportReasonService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
