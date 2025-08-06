// src/CompleteProfile/types/profile.ts
export interface Province {
    id: number;
    name: string;
}

export interface City {
    id: number;
    name: string;
    province_id: number;
}

export interface District {
    id: number;
    name: string;
    city_id: number;
}

// Data yang akan dikirim melalui form submission
export interface ProfileFormData {
    phone: string;
    province_id: number | null;
    city_id: number | null;
    district_id: number | null;
    address: string;
}

// Inertia's error bag structure
export interface InertiaErrors {
    [key: string]: string; // Key is field name, value is error message
}
