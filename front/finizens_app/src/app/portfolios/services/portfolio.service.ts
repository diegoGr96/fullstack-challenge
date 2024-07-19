import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import {
  FindPortfolioResponse,
  GetPortfoliosResponse,
  Portfolio,
} from '../interfaces/portfolio.interfaces';
import { environment } from '../../../environment';
import {
  catchError,
  map,
  Observable,
  of,
  Subject,
} from 'rxjs';
import { OrderRequest } from '../interfaces/order-request.interface';
import { Order } from '../interfaces/order.interface';
import { GetNextOrderIdResponse } from '../interfaces/get-next-order-id-response.interfaces';

@Injectable({
  providedIn: 'root',
})
export class PortfolioService {
  API_URL = environment.api_url;

  public portfolioList: Portfolio[] = [];

  portfolio$ = new Subject<Portfolio | null>();

  constructor(private http: HttpClient) {
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
          this.portfolio$.next(findPortfolioResponse.data);
          return findPortfolioResponse.data;
        }),
        catchError((error) => {
          console.error(error);
          this.portfolio$.next(null);
          return of(null);
        })
      );
  }

  getNextOrderId(): Observable<GetNextOrderIdResponse> {
    return this.http.get<GetNextOrderIdResponse>(
      `${this.API_URL}/orders/next-id`
    );
  }

  createOrder(order: OrderRequest): Observable<void> {
    return this.http.post<void>(`${this.API_URL}/orders`, order);
  }

  completeOrder(order: Order): Observable<void> {
    return this.http.patch<void>(`${this.API_URL}/orders/${order.id}`, {
      status: 'completed',
    });
  }
}
