import CitizenProfilePage from '@/components/citizen/profile/CitizenProfilePage';
import CitizenLayout from '@/components/layouts/CitizenLayout';
import { PageProps } from '@/types';
import { Donation } from '@/types/donation/interface';
import { Point } from '@/types/reedem/point';
import { Report } from '@/types/report';
import { Mission } from '@/types/report/mission';
import { User } from '@/types/user/interface';
import { UserBadge } from '@/types/user/user-badge';
import { UserCertificate } from '@/types/user/user-certificate';
import { usePage } from '@inertiajs/react';
interface ProfilePageRouteProps {
    myReports: Report[];
    myReportsCount: number;
    myMissions: Mission[];
    myMissionCounts: number;
    myDonations: Donation[];
    myPoints: Point[];
    myBadges: UserBadge[];
    myBadgeCounts: number;
    myCertificates: UserCertificate[];
    [key: string]: unknown;
}
const ProfilePageRoute = () => {
    const { props } = usePage<PageProps<ProfilePageRouteProps>>();
    const user = props.auth?.user ?? null;
    const myReports = props.myReports;
    const myReportsCount = props.myReportsCount;
    const myMissions = props.myMissions;
    const myMissionCounts = props.myMissionCounts;
    const myBadges = props.myBadges;
    const myCertificates = props.myCertificates;
    const myDonations = props.myDonations;
    const myPoints = props.myPoints;
    const myBadgeCounts = props.myBadgeCounts;

    console.log('jumlah bagde:', myBadgeCounts);
    console.log('Bagde:', myBadges);
    console.log('Sertif:', myCertificates);

    return (
        <CitizenLayout currentPage="profile">
            <CitizenProfilePage
                user={user as User}
                myPoints={myPoints}
                myReports={myReports}
                myReportsCount={myReportsCount}
                myMissions={myMissions}
                myMissionCounts={myMissionCounts}
                myDonations={myDonations}
                myBadges={myBadges}
                myCertificates={myCertificates}
                myBadgeCounts={myBadgeCounts}
            />
        </CitizenLayout>
    );
};
export default ProfilePageRoute;
