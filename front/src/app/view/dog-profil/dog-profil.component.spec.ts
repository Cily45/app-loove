import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DogProfilComponent } from './dog-profil.component';

describe('DogProfilComponent', () => {
  let component: DogProfilComponent;
  let fixture: ComponentFixture<DogProfilComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [DogProfilComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(DogProfilComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
