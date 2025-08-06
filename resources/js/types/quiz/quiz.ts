export interface Quiz {
    id: number;
    title: string;
    description: string;
    thumbnail_url: string;
    difficulty: 'mudah' | 'sedang' | 'sulit';
    points_reward: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}
