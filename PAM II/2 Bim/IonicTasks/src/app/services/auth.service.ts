import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { DatabaseService } from './database.service';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private currentUser: any | null = null;

  constructor(
    private db: DatabaseService,
    private router: Router,
  ) {}

  async init() {
    await this.db.init();
  }

  get user() {
    return this.currentUser;
  }

  async register(name: string, email: string, password: string) {
    const existing = await this.db.getUserByEmail(email);
    if (existing) {
      throw new Error('Já existe um usuário com esse e-mail.');
    }
    const id = await this.db.createUser(name, email, password);
    this.currentUser = { id, name, email };
  }

  async login(email: string, password: string) {
    const user = await this.db.getUserByEmail(email);
    if (!user || user.password !== password) {
      throw new Error('E-mail ou senha inválidos.');
    }
    this.currentUser = user;
  }

  logout() {
    this.currentUser = null;
    this.router.navigateByUrl('/login', { replaceUrl: true });
  }
}
