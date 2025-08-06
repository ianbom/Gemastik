import { Report } from '../report';
import { User } from '../user/interface';
export interface Donation {
    id: number;
    user: User;
    report: Report;
    user_id: number;
    report_id: number;
    amount: number;
    payment_method: string | null;
    transaction_id: string | null;
    snap_token: string | null;
    payment_response: string | null;
    status: string;
    created_at: string;
    updated_at: string | null;
}
