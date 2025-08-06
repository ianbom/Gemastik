import { User } from '../user/interface';
import { Merchandise } from './merchandise';
export interface Reedem {
    id: number;
    user: User;
    merchandise: Merchandise;
    user_id: number;
    merchandise_id: string;
    points_spent: number;
    status: string;
    shipping_address: string;
    logistic: string | null;
    tracking_number: string | null;
    created_at: string;
    updated_at: string;
}
