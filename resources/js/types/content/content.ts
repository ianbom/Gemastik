import { User } from '../user/interface';
import { MediaItem } from './media';
export interface Content {
    id: number;
    title: string;
    author?: User;
    body: string;
    created_at: string;
    media: MediaItem[];
    content_type: string;
    content: string;
}
