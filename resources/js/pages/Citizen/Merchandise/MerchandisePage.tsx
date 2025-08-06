import CitizenLayout from '@/components/layouts/CitizenLayout';
import MerchandisePage from '@/components/merchandise/MerchandisePage';
import { PageProps } from '@/types';
import { usePage } from '@inertiajs/react';

interface Merchandise {
    id: number;
    name: string;
    description: string;
    image_url: string;
    points_cost: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface MerchandisePageRouteProps {
    merchandise: Merchandise[];
    userPoints: number;
    [key: string]: unknown;
}

const MerchandisePageRoute = () => {
    const { props } = usePage<PageProps<MerchandisePageRouteProps>>();
    const merchandise = props.merchandise || [];
    const userPoints = Number(props.userPoints ?? 0);
    console.log('point', userPoints);
    return (
        <CitizenLayout currentPage="merchandise">
            <MerchandisePage
                merchandise={merchandise}
                userPoints={userPoints}
            />
        </CitizenLayout>
    );
};

export default MerchandisePageRoute;
