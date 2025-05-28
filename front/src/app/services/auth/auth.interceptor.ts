import {HttpInterceptorFn} from '@angular/common/http'
import {inject} from '@angular/core'
import {Router} from '@angular/router'
import {catchError} from 'rxjs/operators'
import {throwError} from 'rxjs'
import {AuthService} from './auth.service';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const token = localStorage.getItem('authToken')
  const router = inject(Router)

  const authService = inject(AuthService)

  if (token) {
    req = req.clone({
      setHeaders: {
        'X-Access-Token': `Bearer ${token}`
      }
    });
  }

  return next(req).pipe(
    catchError(error => {
      if (error.status === 401) {

        authService.logout();
        router.navigate(['/login'])
      }
      return throwError(() => error)
    })
  );
};
