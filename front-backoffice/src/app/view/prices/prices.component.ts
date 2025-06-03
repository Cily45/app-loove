import {Component, OnInit} from '@angular/core';
import {RouterLink} from "@angular/router";
import {Price, PriceService} from '../../services/api/price.service';
import {MatButtonModule} from '@angular/material/button';
import {FormArray, FormGroup, ReactiveFormsModule, Validators, FormBuilder} from '@angular/forms';
import {ToastService} from '../../services/toast.service';

@Component({
  selector: 'app-prices',
  imports: [
    RouterLink, MatButtonModule, ReactiveFormsModule
  ],
  templateUrl: './prices.component.html',
  styleUrl: './prices.component.css'
})
export class PricesComponent implements OnInit {
  pricesForm: FormGroup

  constructor(private pricesService: PriceService, private fb: FormBuilder, private toastService : ToastService) {
    this.pricesForm = this.fb.group({
      prices: this.fb.array([])
    })
  }

  get pricesFormArray() {
    return this.pricesForm.get('prices') as FormArray
  }

  ngOnInit() {
    this.pricesService.getAll().subscribe(prices => {
      prices.forEach((price: Partial<Price>) => {
        this.pricesFormArray.push(this.fb.group({
          id: [price?.id || ''],
          price: [price?.price || '', Validators.required],
          quantity: [price?.quantity || ''],
        }))
      })
    })
  }

  onSubmit() {
    if (this.pricesForm.valid) {
      const formData = this.pricesForm.value.prices
      this.pricesService.update(formData).subscribe({
        next: (response) => {
          this.toastService.showSuccess('Mise à jour réussie')
        },
        error: (error) => {
          this.toastService.showError('Erreur lors de la mise à jour')
        }
      })
    }
  }
}
