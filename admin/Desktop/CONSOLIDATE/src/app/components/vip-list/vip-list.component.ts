import { Component } from '@angular/core';
import { AttendeeService } from '../../services/attendee.service';
import { Attendee } from '../../models/attendee.model';

@Component({
  selector: 'app-vip-list',
  templateUrl: './vip-list.component.html'
})
export class VipListComponent {
  vips: Attendee[];
  constructor(private attendeeService: AttendeeService) {
    this.vips = this.attendeeService.getVIPs();
  }
}
