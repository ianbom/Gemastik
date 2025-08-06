import ContentDetailsPage from '@/components/educational-content/ContentDetailsPage';
import CitizenLayout from '@/components/layouts/CitizenLayout';
import { PageProps } from '@/types';
import { Content } from '@/types/content';
import { router as Inertia, usePage } from '@inertiajs/react';
interface ContentDetailsPageRouteProps {
    content: Content;
    [key: string]: unknown;
}
const ContentDetailsPageRoute = () => {
    const { props } = usePage<PageProps<ContentDetailsPageRouteProps>>();
    const content = props.content;
    const handleBack = () => {
        Inertia.visit(route('content.index'));
    };
    return (
        <CitizenLayout currentPage="education/{id}">
            <ContentDetailsPage content={content} onBack={handleBack} />
        </CitizenLayout>
    );
};

export default ContentDetailsPageRoute;
