import { Component } from '@angular/core';

@Component({
  selector: 'app-faqs',
  templateUrl: './faqs.component.html',
  styleUrls: ['./faqs.component.css']
})
export class FaqsComponent {
  faqs = [
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false },
    { open: false }
  ];

  toggleFaq(index: number) {
    this.faqs[index].open = !this.faqs[index].open;
  }
}