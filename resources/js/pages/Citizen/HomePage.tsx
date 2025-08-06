import FeatureSection from '@/components/core/homepage/FeatureSection';
import HeroSection from '@/components/core/homepage/HeroSection';
import JoinSection from '@/components/core/homepage/JoinSection';
import QnASection from '@/components/core/homepage/QnASection';
import StatSection from '@/components/core/homepage/StatSection';
import CitizenLayout from '@/components/layouts/CitizenLayout';
const HomePage = () => {
    return (
        <CitizenLayout currentPage="homepage">
            <div className="min-h-screen bg-gradient-to-b from-green-50 to-white">
                <HeroSection />
                <StatSection />
                <FeatureSection />
                <JoinSection />
                <QnASection />
            </div>
        </CitizenLayout>
    );
};

export default HomePage;
