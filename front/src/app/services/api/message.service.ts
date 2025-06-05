import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {environment} from '../../env';

export interface Message {
  id: number
  message: string
  date: string
  hour: string
  is_view: number
  firstname: string,
  lastname: string,
  profil_photo: string,
  is_that_user: number
}

@Injectable({
  providedIn: 'root'
})
export class MessageService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}


  messages(): Observable<Message[]> {
    return this.http.get<Message[]>(`${this.apiUrl}/messages`)
  }

    messagesById(id: number, id1: number): Observable<Message[]> {
    return this.http.get<Message[]>(`${this.apiUrl}/messages/${id}/${id1}`)
  }

  sendMessage(form: { receiver_id: number; message: string | null }){
    return this.http.post<string[]>(`${this.apiUrl}/send-message`, form)
  }

  updateMessage(id: number){
    return this.http.get<string[]>(`${this.apiUrl}/vue/${id}`)
  }

}
