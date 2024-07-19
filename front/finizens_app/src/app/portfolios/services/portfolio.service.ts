import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import {
  FindPortfolioResponse,
  GetPortfoliosResponse,
  Portfolio,
} from '../interfaces/portfolio.interfaces';
import { environment } from '../../../environment';
import { BehaviorSubject, catchError, map, Observable, of, Subject } from 'rxjs';
import { OrderRequest } from '../interfaces/order-request.interface';
import { Order } from '../interfaces/order.interface';

@Injectable({
  providedIn: 'root',
})
export class PortfolioService {
  API_URL = environment.api_url;

  public portfolioList: Portfolio[] = [];

  private portfolioSubject = new Subject<Portfolio | null>();
  portfolio$ = this.portfolioSubject.asObservable();

  constructor(private http: HttpClient) {
    // this.loadPortfolios();
  }

  loadPortfolios() {
    this.http
      .get<GetPortfoliosResponse>(`${this.API_URL}/portfolios`)
      .subscribe((response) => {
        this.portfolioList = response.data;
      });
  }

  findPortfolio(portfolioId: number): Observable<Portfolio | null> {
    return this.http
      .get<FindPortfolioResponse>(`${this.API_URL}/portfolios/${portfolioId}`)
      .pipe(
        map((findPortfolioResponse) => {
          this.portfolioSubject.next(findPortfolioResponse.data);
          return findPortfolioResponse.data;
        }),
        catchError((error) => {
          console.error(error);
          this.portfolioSubject.next(null);
          return of(null);
        })
      );
  }

  createOrder(order: OrderRequest) {
    this.http.post(`${this.API_URL}/orders`, order).subscribe((response) => {
      this.findPortfolio(order.portfolio).subscribe();
    });
  }

  completeOrder(order: Order) {
    this.http.patch(`${this.API_URL}/orders/${order.id}`, {'status': 'completed'}).subscribe((response) => {
      this.findPortfolio(order.portfolio_id).subscribe();
    });
  }
}
