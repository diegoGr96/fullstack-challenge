import { CommonModule } from '@angular/common';
import { ChangeDetectionStrategy, Component, Input, input } from '@angular/core';
import { Portfolio } from '../../interfaces/portfolio.interfaces';

@Component({
  selector: 'portfolio-list',
  templateUrl: './portfolio-list.component.html',
  styleUrl: './portfolio-list.component.css',
})
export class PortfolioListComponent {
  @Input()
  public portfolios: Portfolio[] = [];
}
