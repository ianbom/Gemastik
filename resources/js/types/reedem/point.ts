export interface Point {
    id: number;
    user_id: number;
    points: number;
    type: 'increment' | 'decrement';
    amount: number;
    description: string;
    created_at: string;
    updated_at: string;
}
