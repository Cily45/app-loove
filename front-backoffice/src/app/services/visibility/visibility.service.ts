import { inject, Injectable, signal } from '@angular/core'
import { Router } from '@angular/router'

@Injectable({
  providedIn: 'root'
})
export class VisibilityService {
  private readonly router = inject(Router)

  public hideFooter = signal<boolean>(false)
  public hideAsideMenu = signal<boolean>(false)

  constructor () {
    this.router.events.subscribe(() => {
      const currentRoute = this.router.routerState.snapshot.root.firstChild
      if (currentRoute) {
        this.hideFooter.set(currentRoute.data['hideFooter'] || false)
        this.hideAsideMenu.set(currentRoute.data['hideAsideMenu'] || false)
      }
    })
  }
}
