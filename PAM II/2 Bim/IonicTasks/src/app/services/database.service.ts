import { Injectable } from '@angular/core';
import {
  CapacitorSQLite,
  SQLiteConnection,
  SQLiteDBConnection,
} from '@capacitor-community/sqlite';

@Injectable({ providedIn: 'root' })
export class DatabaseService {
  private sqliteConnection: SQLiteConnection;
  private db?: SQLiteDBConnection;

  constructor() {
    this.sqliteConnection = new SQLiteConnection(CapacitorSQLite);
  }

  async init() {
    // cria conexão e abre banco
    this.db = await this.sqliteConnection.createConnection(
      'ionictasks.db',
      false,
      'no-encryption',
      1,
      false
    );
    await this.db.open();

    await this.createTables();
  }

  private async createTables() {
    const createUsers = `
      CREATE TABLE IF NOT EXISTS users (
        id TEXT PRIMARY KEY,
        name TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
      );
    `;

    const createTasks = `
      CREATE TABLE IF NOT EXISTS tasks (
        id TEXT PRIMARY KEY,
        title TEXT NOT NULL,
        description TEXT,
        start_at TEXT NOT NULL,
        end_at TEXT NOT NULL,
        priority TEXT NOT NULL,
        is_completed INTEGER NOT NULL DEFAULT 0,
        user_id TEXT NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
      );
    `;

    await this.db?.execute(createUsers);
    await this.db?.execute(createTasks);
  }

  // CRUD simples de usuário
  async createUser(name: string, email: string, password: string) {
    const id = crypto.randomUUID();
    const sql = `INSERT INTO users (id, name, email, password) VALUES (?, ?, ?, ?)`;
    const values = [id, name, email, password];
    await this.db?.run(sql, values);
    return id;
  }

  async getUserByEmail(email: string) {
    const sql = `SELECT * FROM users WHERE email = ?`;
    const res = await this.db?.query(sql, [email]);
    return res?.values?.[0];
  }

  async createTask(
    title: string,
    description: string | null,
    startAt: string,
    endAt: string,
    priority: 'LOW' | 'MEDIUM' | 'HIGH',
    userId: string
  ) {
    const id = crypto.randomUUID();
    const sql = `
      INSERT INTO tasks (id, title, description, start_at, end_at, priority, is_completed, user_id)
      VALUES (?, ?, ?, ?, ?, ?, 0, ?)
    `;
    const values = [id, title, description, startAt, endAt, priority, userId];
    await this.db?.run(sql, values);
    return id;
  }

  async listTasksByUser(userId: string) {
    const sql = `SELECT * FROM tasks WHERE user_id = ? ORDER BY start_at`;
    const res = await this.db?.query(sql, [userId]);
    return res?.values ?? [];
  }
}
