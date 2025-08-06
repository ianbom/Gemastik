import Navbar from '@/components/core/Navbar';
import NotificationSidebar from '@/components/core/NotificationSidebar';
import { PageProps } from '@/types';
import { getProfileMenuContent } from '@/utils/profileMenuContent';
import { showToast } from '@/utils/toast';
import { router as Inertia, usePage } from '@inertiajs/react';
import { ReactNode, useEffect, useState } from 'react';
import { Toaster } from 'sonner';
import FloatingChat from '../chatbot/FloatingChat';
import Footer from '../core/Footer';
interface Props {
    children: ReactNode;
    currentPage: string;
}

export default function CommunityLayout({ children, currentPage }: Props) {
    const { auth } = usePage<PageProps>().props;
    const user = auth?.user;
    const { flash = {} } = usePage().props;
    const [isNotificationOpen, setIsNotificationOpen] = useState(false);
    const navItems = [
        { id: 'homepage', label: 'Beranda', key: 'community/homepage' },
        {
            id: 'community/report',
            label: 'Laporan',
            key: 'community/report',
        },
        {
            id: 'community/mission',
            label: 'Misi',
            key: 'community/mission',
        },
        { id: 'community/map', label: 'Peta', key: 'community/map' },
        {
            id: 'community/education',
            label: 'Konten Edukasi',
            key: 'community/education',
        },
    ];
    useEffect(() => {
        console.log('Flash data di CommunityLayout:', flash);
        showToast.handleFlash(flash);
    }, [flash]);
    const handleNavigate = (page: string) => {
        Inertia.visit(`/${page}`);
    };

    const handleProfileClick = () => {
        if (user?.role === 'citizen') {
            Inertia.visit(route('profile.show'));
        } else {
            Inertia.visit(route('community.profile.show'));
        }
    };
    const handleLogoutClick = () => {
        Inertia.post(route('logout'));
    };
    return (
        <div className="min-h-screen bg-gradient-to-br from-emerald-50 to-white">
            <Navbar
                user={user}
                navItems={navItems}
                onNavigate={handleNavigate}
                currentPage={currentPage}
                onNotificationClick={() => setIsNotificationOpen(true)}
                profileMenuContent={getProfileMenuContent({
                    user,
                    onProfileClick: handleProfileClick,
                    onLogoutClick: handleLogoutClick,
                })}
            />
            <main className="pt-16">{children}</main>
            <NotificationSidebar
                isOpen={isNotificationOpen}
                onClose={() => setIsNotificationOpen(false)}
            />
            <FloatingChat />
            <Toaster position="top-right" richColors closeButton />
            <Footer />
        </div>
    );
}
