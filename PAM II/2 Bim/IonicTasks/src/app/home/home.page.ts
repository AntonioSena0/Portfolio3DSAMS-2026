import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonItem,
  IonInput,
  IonButton,
  IonList,
  IonLabel,
} from '@ionic/angular/standalone';
import { DatabaseService } from '../services/database.service'; // << IMPORTANTE

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [
    CommonModule,  // *ngIf, *ngFor
    FormsModule,   // ngModel
    IonContent,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonItem,
    IonInput,
    IonButton,
    IonList,
    IonLabel,
  ],
  templateUrl: './home.page.html',
})
export class HomePage {
  email = '';
  password = '';
  user: any = null;
  tasks: any[] = [];

  constructor(private db: DatabaseService) {}

  async register() {
    const id = await this.db.createUser(this.email, this.email, this.password);
    this.user = { id, email: this.email };
  }

  async login() {
    const user = await this.db.getUserByEmail(this.email);
    if (user && user.password === this.password) {
      this.user = user;
      await this.loadTasks();
    } else {
      alert('Usuário ou senha inválidos');
    }
  }

  async loadTasks() {
    if (!this.user) return;
    this.tasks = await this.db.listTasksByUser(this.user.id);
  }

  async addTask() {
    if (!this.user) return;
    await this.db.createTask(
      'Nova tarefa',
      null,
      '2026-06-24',
      '2026-06-25',
      'MEDIUM',
      this.user.id
    );
    await this.loadTasks();
  }
}
