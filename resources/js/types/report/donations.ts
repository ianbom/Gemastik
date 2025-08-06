import { User } from '@/types/user/interface';
import { Report } from './report';

export interface Donation {
    id: number;
    user: User;
    report: Report;
    amount: number;
    payment_method: string;
    transaction_id: string;
    snap_token: string;
    payment_response: string;
    created_at: string;
}
