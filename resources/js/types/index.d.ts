import { Notification } from './notification/interface';
import { User } from './user/interface';
export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth?: {
        user: User;
    };
    flash?: {
        success?: string;
        error?: string;
        warning?: string;
        info?: string;
        status?: string;
    };
    notifications?: {
        unread_count: number;
        notifications: Notification[];
    };
};
