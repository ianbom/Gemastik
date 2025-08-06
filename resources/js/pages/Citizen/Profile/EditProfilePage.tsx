import EditProfilePage from '@/components/citizen/profile/EditProfilePage';
import CitizenLayout from '@/components/layouts/CitizenLayout';
import { PageProps } from '@/types';
import { Province } from '@/types/area/interface';
import { router as Inertia, usePage } from '@inertiajs/react';

interface EditProfilePageRouteProps {
    provinces: Province[];
    [key: string]: unknown;
}

const EditProfilePageRoute = () => {
    const { props } = usePage<PageProps<EditProfilePageRouteProps>>();
    const user = props.auth?.user ?? null;
    const provinces = props.provinces ?? [];
    if (!user) return null;

    return (
        <CitizenLayout currentPage="profile">
            <EditProfilePage
                auth={{ user }}
                provinces={provinces}
                onBack={() => Inertia.visit('/profile')}
            />
        </CitizenLayout>
    );
};
export default EditProfilePageRoute;
