import { Component } from '@angular/core';
import { Portfolio } from '../../interfaces/portfolio.interfaces';
import { ActivatedRoute, Router } from '@angular/router';
import { PortfolioService } from '../../services/portfolio.service';
import { Subscription, switchMap } from 'rxjs';
import { Allocation } from '../../interfaces/allocation.interface';
import { OrderRequest } from '../../interfaces/order-request.interface';
import { Order } from '../../interfaces/order.interface';
import { GetNextOrderIdResponse } from '../../interfaces/get-next-order-id-response.interfaces';

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

    this.portfolioSubscription = this.portfolioService.portfolio$.subscribe(
      (portfolio) => {
        if (portfolio === null) {
          return this.router.navigateByUrl('');
        }

        return (this.portfolio = portfolio);
      }
    );

    this.portfolioService.findPortfolio(params['id']).subscribe();
  }

  ngOnDestroy() {
    this.portfolioSubscription.unsubscribe();
  }

  createOrder(allocation: Allocation, type: string) {
    if (!this.portfolio) return;

    this.portfolioService
      .getNextOrderId()
      .pipe(
        switchMap((response: GetNextOrderIdResponse) => {
          const orderParams: OrderRequest = {
            id: response.data.value ?? 1,
            portfolio: this.portfolio!.id,
            allocation: allocation.id,
            shares: allocation.shares,
            type: type,
          };

          return this.portfolioService.createOrder(orderParams);
        }),
        switchMap(() => this.portfolioService.findPortfolio(this.portfolio!.id))
      )
      .subscribe();
  }

  completeOrder(order: Order) {
    this.portfolioService.completeOrder(order).pipe(
      switchMap(() => this.portfolioService.findPortfolio(this.portfolio!.id))
    )
    .subscribe();
  }
}
