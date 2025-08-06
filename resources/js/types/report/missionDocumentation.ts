import { User } from '../user/interface';
export interface MissionDocumentation {
    mission_id: number;
    uploader_user_id: number;
    content: string;
    media_url: string;
    media_type: string;
    created_at: string;
    updated_at: string;
    uploader: User;
}
