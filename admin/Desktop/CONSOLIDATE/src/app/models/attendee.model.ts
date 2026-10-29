export interface Attendee {
  name: string;
  age: number;
  campusOrWorkplace: string;
  invitedBy: string;
  status: 'VIP' | 'Regular';
  hasCellGroup: boolean;
}
