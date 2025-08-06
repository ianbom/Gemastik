export interface District {
    id: number;
    name: string;
    city_id: number;
    created_at?: string;
    updated_at?: string;
}

export interface City {
    id: number;
    name: string;
    province_id: number;
    districts?: District[];
    created_at: string;
    updated_at: string;
}

export interface Province {
    id: number;
    name: string;
    cities?: City[];
    created_at?: string;
    updated_at?: string;
}
