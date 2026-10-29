import { Component } from '@angular/core';
import { Attendee } from '../../models/attendee.model';
import { AttendeeService } from '../../services/attendee.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent {
  model: Attendee = {
    name: '',
    age: 0,
    campusOrWorkplace: '',
    invitedBy: '',
    status: 'Regular',
    hasCellGroup: false
  };

  constructor(private attendeeService: AttendeeService, private router: Router) {}

  submit() {
    this.attendeeService.addAttendee(this.model);
    if (this.model.status === 'VIP') {
      this.router.navigate(['/vip']);
    } else {
      this.router.navigate(['/regular']);
    }
  }
}
