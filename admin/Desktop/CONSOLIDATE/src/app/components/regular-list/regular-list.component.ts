import { Component } from '@angular/core';
import { AttendeeService } from '../../services/attendee.service';
import { Attendee } from '../../models/attendee.model';

@Component({
  selector: 'app-regular-list',
  templateUrl: './regular-list.component.html'
})
export class RegularListComponent {
  regs: Attendee[];
  constructor(private attendeeService: AttendeeService) {
    this.regs = this.attendeeService.getRegulars();
  }
}
