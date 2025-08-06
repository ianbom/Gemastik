import { Badge } from '../badge/interface';

export interface UserBadge {
    id: number;
    user_id: number;
    badge_id: number;
    created_at: string;
    updated_at: string;
    badge: Badge;
}
