import CitizenLayout from '@/components/layouts/CitizenLayout';
import CreateReportPage from '@/components/report/CreateReportPage';
import { PageProps } from '@/types';
import { Province } from '@/types/area/interface';
import { router as Inertia, usePage } from '@inertiajs/react';
interface CreateReportPageRouteProps {
    provinces: Province[];
    [key: string]: unknown;
}
const CreateReportPageRoute = () => {
    const { props } = usePage<PageProps<CreateReportPageRouteProps>>();
    const provinces = props.provinces || [];
    console.log(provinces.map((prov) => prov.cities));

    const handleBack = () => {
        Inertia.visit(route('report'));
    };
    console.log(props.provinces);
    return (
        <CitizenLayout currentPage="create-report">
            <CreateReportPage onBack={handleBack} provinces={provinces} />
        </CitizenLayout>
    );
};
export default CreateReportPageRoute;
