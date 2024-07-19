import { CommonModule } from '@angular/common';
import { ChangeDetectionStrategy, Component } from '@angular/core';
import { Portfolio } from '../../interfaces/portfolio.interfaces';
import { ActivatedRoute, Router } from '@angular/router';
import { PortfolioService } from '../../services/portfolio.service';
import { map, Observable, Subscription } from 'rxjs';
import { Allocation } from '../../interfaces/allocation.interface';
import { OrderRequest } from '../../interfaces/order-request.interface';
import { Order } from '../../interfaces/order.interface';

@Component({
  selector: 'app-portfolio',
  templateUrl: './portfolio.component.html',
  styleUrl: './portfolio.component.css',
})
export class PortfolioComponent {
  public portfolio: Portfolio | null = null;
  portfolioSubscription: Subscription = new Subscription();

  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private portfolioService: PortfolioService
  ) {}

  ngOnInit(): void {
    const params = this.activatedRoute.snapshot.params;
    console.log(params);

    this.portfolioSubscription = this.portfolioService.portfolio$.subscribe(
      (portfolio) => {
        if (portfolio === null) {
          return this.router.navigateByUrl('');
        }

        return this.portfolio = portfolio;
      }
    );

    this.portfolioService.findPortfolio(params['id']).subscribe();
  }

  ngOnDestroy() {
    this.portfolioSubscription.unsubscribe();
  }

  createOrder(allocation: Allocation, type: string) {
    if (!this.portfolio) return;

    const orderParams: OrderRequest = {
      id: (this.portfolio?.orders.length ?? 0) + 1,
      portfolio: this.portfolio?.id,
      allocation: allocation.id,
      shares: allocation.shares,
      type: 'sell',
    };

    this.portfolioService.createOrder(orderParams);
  }

  completeOrder(order: Order) {
    console.log('CompleteOrder');
    console.log(order);
    console.log('---------');

    this.portfolioService.completeOrder(order);
  }
}
