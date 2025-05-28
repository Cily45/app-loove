import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LogoOfflineComponent } from './logo-offline.component';

describe('LogoOfflineComponent', () => {
  let component: LogoOfflineComponent;
  let fixture: ComponentFixture<LogoOfflineComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LogoOfflineComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(LogoOfflineComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
