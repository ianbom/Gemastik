import EditProfilePage from '@/components/community/EditProfilePage';
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
    // const user = props.auth?.user ?? null;
    const user = usePage<PageProps>().props.auth?.user;
    const provinces = props.provinces ?? [];
    if (!user) return null;

    return (
        <CitizenLayout currentPage="community/edit-profile">
            <EditProfilePage
                auth={{ user }}
                provinces={provinces}
                onBack={() => Inertia.visit('/community/profile')}
            />
        </CitizenLayout>
    );
};
export default EditProfilePageRoute;
