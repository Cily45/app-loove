import { TestBed } from '@angular/core/testing';

import { PusherBeamsService } from './pusher-beams.service';

describe('PusherBeamsService', () => {
  let service: PusherBeamsService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PusherBeamsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
