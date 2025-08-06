import CitizenLayout from '@/components/layouts/CitizenLayout';
import MyMerchandisePage from '@/components/merchandise/MyMerchandisePage';
import { PageProps } from '@/types';
import { Reedem } from '@/types/reedem/interface';
import { usePage } from '@inertiajs/react';
interface MyMerchandisePageRouteProps {
    reedems: Reedem[];
    [key: string]: unknown;
}
const MyMerchandisePageRoute = () => {
    const { props } = usePage<PageProps<MyMerchandisePageRouteProps>>();
    // const reedems = props.reedeems || [];
    const reedems = props.reedems || [];
    console.log(reedems);
    const user = props.auth?.user ?? null;
    if (!user) return null;
    return (
        <CitizenLayout currentPage="my-merchandise">
            <MyMerchandisePage auth={{ user }} reedems={reedems} />
        </CitizenLayout>
    );
};

export default MyMerchandisePageRoute;
