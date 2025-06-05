import { TestBed } from '@angular/core/testing';

import { DogGenderService } from './dog-gender.service';

describe('DogGenderService', () => {
  let service: DogGenderService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(DogGenderService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
