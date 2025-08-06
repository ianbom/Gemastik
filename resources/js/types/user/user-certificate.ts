import { Mission } from "../report/mission";
import { User } from "./interface";

export interface UserCertificate {
    id: number;
    user_id: number;
    mission_id: number;
    title: string;
    certificate_url: string;
    code?: string;
    created_at: string;
    updated_at: string;
}
