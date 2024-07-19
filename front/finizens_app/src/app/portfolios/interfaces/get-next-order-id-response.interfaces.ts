import { Allocation } from "./allocation.interface";
import { Order } from "./order.interface";

export interface GetNextOrderIdResponse {
    execution_time: number;
    status: number;
    data: {
        value: number;
    };
}
