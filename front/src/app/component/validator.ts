import { AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms'

export function birthDateValidator (): ValidatorFn {
  return (control: AbstractControl) => {
    if (!control.value) return null
    const [year, month,day ] = control.value.split('-').map(Number)
    const today = new Date()
    const birthDate = new Date(year, month - 1, day)
    if (birthDate.getDate() !== day || birthDate.getMonth() + 1 !== month || birthDate.getFullYear() !== year) {
      return { invalidDate: true }
    }

    const ageInMilliseconds = today.getTime() - birthDate.getTime()
    const ageDate = new Date(ageInMilliseconds)
    const age = Math.abs(ageDate.getUTCFullYear() - 1970)

    return age >= 18 && age <= 110 ? null : { invalidAge: true }
  }
}

export function matchPassword (passwordKey = 'password', confirmKey = 'passwordConfirm'): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    const password = control.get(passwordKey)
    const confirm = control.get(confirmKey)

    if (!password || !confirm) return null

    if (confirm.errors && !confirm.errors['mismatchPassword']) {
      return null
    }

    if (password.value !== confirm.value) {
      confirm.setErrors({ mismatchPassword: true })
    } else {
      confirm.setErrors(null)
    }

    return null
  }
}


