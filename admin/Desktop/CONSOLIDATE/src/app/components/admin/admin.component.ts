import { Component } from '@angular/core';
import { AttendeeService } from '../../services/attendee.service';
import { Attendee } from '../../models/attendee.model';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.component.html'
})
export class AdminComponent {
  all: Attendee[];
  constructor(private attendeeService: AttendeeService) {
    this.all = this.attendeeService.getAll();
  }
}
