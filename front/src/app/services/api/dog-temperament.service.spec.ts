import { TestBed } from '@angular/core/testing';

import { DogTemperamentService } from './dog-temperament.service';

describe('DogTemperamentService', () => {
  let service: DogTemperamentService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(DogTemperamentService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
