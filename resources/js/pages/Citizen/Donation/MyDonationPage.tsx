import CitizenLayout from '@/components/layouts/CitizenLayout';
import MyDonationPage from '@/components/my-donation/MyDonationPage';
import { PageProps } from '@/types';
import { Donation } from '@/types/donation/interface';
import { usePage } from '@inertiajs/react';
interface MyDonationPageRouteProps {
    donations: {
        data: Donation[];
    };
    [key: string]: unknown;
}
const MyDonationPageRoute = () => {
    const { props } = usePage<PageProps<MyDonationPageRouteProps>>();
    const donations = props.donations?.data || [];

    return (
        <CitizenLayout currentPage="my-donation">
            <MyDonationPage donations={donations} />
        </CitizenLayout>
    );
};

export default MyDonationPageRoute;
