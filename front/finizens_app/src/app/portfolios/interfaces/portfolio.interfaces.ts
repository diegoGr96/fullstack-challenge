import { Allocation } from "./allocation.interface";
import { Order } from "./order.interface";

export interface GetPortfoliosResponse {
    execution_time: number;
    status: number;
    data: Portfolio[];
}

export interface FindPortfolioResponse {
    execution_time: number;
    status: number;
    data: Portfolio;
}

export interface Portfolio {
    id: number;
    allocations: Allocation[];
    orders: Order[];
}
