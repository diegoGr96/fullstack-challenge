export interface Order {
    id: number;
    portfolio_id: number;
    allocation_id: number;
    shares: number;
    type: string;
    status: string;
}
