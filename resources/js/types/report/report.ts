import { User } from '../user/interface';
import { MediaItem } from './media';
import { Mission } from './mission';
export interface Report {
    id: number;
    title: string;
    category: string;
    reporter?: User;
    description: string;
    province_id: number;
    province: { name: string };
    city: { name: string };
    district: { name: string };
    status: string;
    image?: string;
    hasMission?: boolean;
    upvotes_count: number;
    dislikes_count: number;
    created_at: string;
    media: MediaItem[];
    mission?: Mission;
    address: string;
    latitude: number;
    completion_details: string;
    longitude: number;
    data: [];
    is_donation: boolean;
}
