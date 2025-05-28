import { Injectable } from '@angular/core';
import { MatSnackBar, MatSnackBarConfig } from '@angular/material/snack-bar';

@Injectable({ providedIn: 'root' })
export class ToastService {

  constructor(private snackBar: MatSnackBar) {}

  private show(message: string, config: MatSnackBarConfig) {
    this.snackBar.open(message, '', {
      duration: 2000,
      horizontalPosition: 'end',
      verticalPosition: 'top',
      ...config,
    });
  }

  showSuccess(message: string) {
    this.show(message, { panelClass: ['toast-success'] });
  }

  showError(message: string) {
    this.show(message, { panelClass: ['toast-error'] });
  }

  showInfo(message: string) {
    this.show(message, { panelClass: ['toast-info'] });
  }
}
