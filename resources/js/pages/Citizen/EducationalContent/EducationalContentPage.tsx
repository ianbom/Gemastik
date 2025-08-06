import EducationalContentPage from '@/components/educational-content/EducationalContentPage';
import CitizenLayout from '@/components/layouts/CitizenLayout';
import { PageProps } from '@/types';
import { Content } from '@/types/content';
import { router as Inertia, usePage } from '@inertiajs/react';
interface EducationalContentPageRouteProps {
    contents: {
        data: Content[];
    };
    [key: string]: unknown;
}
const EducationalContentPageRoute = () => {
    const { props } = usePage<PageProps<EducationalContentPageRouteProps>>();
    const contents = props.contents?.data || [];

    const handleViewDetails = (id: number) => {
        Inertia.visit(route('content.show', { id }));
    };

    return (
        <CitizenLayout currentPage="education">
            <EducationalContentPage
                contents={contents}
                onViewDetails={handleViewDetails}
            />
        </CitizenLayout>
    );
};

export default EducationalContentPageRoute;
