import { TestBed } from '@angular/core/testing';

import { DogSizeService } from './dog-size.service';

describe('DogSizeService', () => {
  let service: DogSizeService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(DogSizeService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
