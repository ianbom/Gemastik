import {User} from '@/types/user/interface'

export interface Comment {
    id: number | string;
    user: User;
    comment: string;
    media_url: string;
    media_type: 'image' | 'video';
    created_at: string;
    replies?: Comment[];

}
