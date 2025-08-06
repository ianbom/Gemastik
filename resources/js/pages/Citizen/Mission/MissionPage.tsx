import CitizenLayout from '@/components/layouts/CitizenLayout';
import MissionPage from '@/components/mission/MissionPage';
import { PageProps } from '@/types';
import { Province } from '@/types/area/interface';
import { Mission } from '@/types/report/mission';
import { router as Inertia, usePage } from '@inertiajs/react';
interface MissionPageRouteProps {
    missions: {
        data: Mission[];
    };
    provinces: {
        provinces: Province[];
    };
    myMissions: boolean;
    [key: string]: unknown;
}
const MissionPageRoute = () => {
    const { props } = usePage<PageProps<MissionPageRouteProps>>();
    const missions = props.missions?.data || [];
    const myMissions = props.myMissions;
    const provinces = props.provinces.provinces ?? [];
    const handleViewDetails = (reportId: number | null) => {
        if (reportId) {
            Inertia.visit(route('report.show', { id: reportId }));
        } else {
            alert('Laporan tidak tersedia untuk misi ini.');
        }
    };
    // console.log('props.provinces', props.provinces);

    // console.log('misi-provinsi', provinces);

    return (
        <CitizenLayout currentPage="mission">
            <MissionPage
                myMissions={myMissions}
                missions={missions}
                onViewDetails={handleViewDetails}
                provinces={provinces}
            />
        </CitizenLayout>
    );
};

export default MissionPageRoute;
