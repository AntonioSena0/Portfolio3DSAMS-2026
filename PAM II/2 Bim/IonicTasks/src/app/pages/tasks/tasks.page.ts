import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonButton,
  IonButtons,
  IonItem,
  IonLabel,
  IonList,
  IonInput,
  IonSelect,
  IonSelectOption,
  IonDatetime,
  IonIcon,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardSubtitle,
  IonCardContent,
  IonFab,
  IonChip,
  IonFabButton,
} from '@ionic/angular/standalone';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { DatabaseService } from '../../services/database.service';
import { addIcons } from 'ionicons';
import { addOutline, logOutOutline } from 'ionicons/icons';

@Component({
  selector: 'app-tasks',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    IonContent,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonButton,
    IonButtons,
    IonItem,
    IonLabel,
    IonList,
    IonInput,
    IonSelect,
    IonSelectOption,
    IonDatetime,
    IonIcon,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardSubtitle,
    IonCardContent,
    IonFab,
    IonChip,
    IonFabButton,
  ],
  templateUrl: './tasks.page.html',
  styleUrls: ['./tasks.page.scss'],
})
export class TasksPage implements OnInit {
  form: FormGroup;
  tasks: any[] = [];
  showForm = false;

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private db: DatabaseService,
    private router: Router,
  ) {
    addIcons({ addOutline, logOutOutline });

    this.form = this.fb.group({
      title: ['', [Validators.required]],
      description: [''],
      startAt: ['', [Validators.required]],
      endAt: ['', [Validators.required]],
      priority: ['MEDIUM', [Validators.required]],
    });
  }

  get user() {
    return this.auth.user;
  }

  get f() {
    return this.form.controls;
  }

  async ngOnInit() {
    if (!this.user) {
      await this.router.navigateByUrl('/login', { replaceUrl: true });
      return;
    }
    await this.loadTasks();
  }

  async loadTasks() {
    if (!this.user) return;
    this.tasks = await this.db.listTasksByUser(this.user.id);
  }

  toggleForm() {
    this.showForm = !this.showForm;
  }

  async addTask() {
    if (this.form.invalid || !this.user) {
      this.form.markAllAsTouched();
      return;
    }
    const { title, description, startAt, endAt, priority } = this.form.value;

    const start = startAt.split('T')[0];
    const end = endAt.split('T')[0];

    await this.db.createTask(
      title,
      description || null,
      start,
      end,
      priority,
      this.user.id,
    );

    this.form.reset({
      priority: 'MEDIUM',
      startAt: '',
      endAt: '',
    });
    this.showForm = false;
    await this.loadTasks();
  }

  logout() {
    this.auth.logout();
  }

  priorityColor(priority: string) {
    switch (priority) {
      case 'HIGH':
        return 'danger';
      case 'LOW':
        return 'success';
      default:
        return 'warning';
    }
  }
}
