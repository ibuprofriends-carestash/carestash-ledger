import { Injectable } from '@angular/core';
import { Attendee } from '../models/attendee.model';

@Injectable({ providedIn: 'root' })
export class AttendeeService {
  private vipKey = 'exer2-vips';
  private regKey = 'exer2-regulars';

  private load(key: string): Attendee[] {
    const raw = localStorage.getItem(key);
    return raw ? JSON.parse(raw) : [];
  }

  private save(key: string, data: Attendee[]) {
    localStorage.setItem(key, JSON.stringify(data));
  }

  addAttendee(att: Attendee) {
    if (att.status === 'VIP') {
      const vips = this.load(this.vipKey);
      vips.push(att);
      this.save(this.vipKey, vips);
    } else {
      const regs = this.load(this.regKey);
      regs.push(att);
      this.save(this.regKey, regs);
    }
  }

  getVIPs(): Attendee[] {
    return this.load(this.vipKey);
  }

  getRegulars(): Attendee[] {
    return this.load(this.regKey);
  }

  getAll(): Attendee[] {
    return [...this.getVIPs(), ...this.getRegulars()];
  }
}
