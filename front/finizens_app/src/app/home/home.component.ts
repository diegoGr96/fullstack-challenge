import { CommonModule } from '@angular/common';
import { ChangeDetectionStrategy, Component } from '@angular/core';
import { Portfolio } from '../portfolios/interfaces/portfolio.interfaces';
import { PortfolioService } from '../portfolios/services/portfolio.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrl: './home.component.css',
})
export class HomeComponent {
  constructor(private portfolioService: PortfolioService) {}

  ngOnInit() {
    this.portfolioService.loadPortfolios();
  }

  get portfolios(): Portfolio[] {
    return this.portfolioService.portfolioList;
  }

  // callApi() {
  //   this.testService.callApi();
  // }
}
