import CommunityProfilePage from '@/components/community/CommunityProfilePage';
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
    myBadges: UserBadge[];
    myDonations: Donation[];
    myPoints: Point[];
    myCertificates: UserCertificate[];
    myBadgeCounts: number;
    [key: string]: unknown;
}
const ProfilePageRoute = () => {
    const { props } = usePage<PageProps<ProfilePageRouteProps>>();
    const user = props.auth?.user;
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
    console.log('badge:', myBadges);
    console.log('sertif:', myCertificates);

    return (
        <CitizenLayout currentPage="community/profile">
            <CommunityProfilePage
                user={user as User}
                myReports={myReports}
                myReportsCount={myReportsCount}
                myMissions={myMissions}
                myMissionCounts={myMissionCounts}
                myCertificates={myCertificates}
                myDonations={myDonations}
                myBadges={myBadges}
                myPoints={myPoints}
                myBadgeCounts={myBadgeCounts}
            />
        </CitizenLayout>
    );
};
export default ProfilePageRoute;
