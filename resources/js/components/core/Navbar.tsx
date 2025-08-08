import { PageProps } from '@/types';
import { NavItems, NavUser } from '@/types/navbar/interface';
import { User } from '@/types/user/interface';
import { usePage } from '@inertiajs/react';
import { Bell, Medal, Menu, ShoppingBag, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Badge } from '../ui/badge';
import ProfileMenuDropdown from './ProfileDropdown';

interface NavbarProps {
    navItems: NavItems[];
    onNavigate: (page: string) => void;
    currentPage: string;
    onNotificationClick: () => void;
    user?: NavUser;
    profileMenuContent: React.ReactNode;
}

const Navbar = ({
    navItems,
    onNavigate,
    currentPage,
    onNotificationClick,
    profileMenuContent,
    user,
}: NavbarProps) => {
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
    const { notifications } = usePage<PageProps>().props;
    const unreadCount = notifications?.unread_count || 0;

    useEffect(() => {
        setIsMobileMenuOpen(false);
    }, [currentPage]);

    useEffect(() => {
        const handleEscape = (e: KeyboardEvent) => {
            if (e.key === 'Escape') {
                setIsMobileMenuOpen(false);
            }
        };

        if (isMobileMenuOpen) {
            document.addEventListener('keydown', handleEscape);
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }

        return () => {
            document.removeEventListener('keydown', handleEscape);
            document.body.style.overflow = 'unset';
        };
    }, [isMobileMenuOpen]);

    const handleNavItemClick = (key: string) => {
        onNavigate(key);
        setIsMobileMenuOpen(false);
    };

    const getIconForNavItem = (key: string) => {
        switch (key) {
            case 'leaderboard':
                return Medal;
            case 'merchandise':
                return ShoppingBag;
            default:
                return null;
        }
    };

    return (
        <>
            <nav className="fixed top-0 left-0 right-0 z-50 border-b shadow-sm border-sky-100 bg-white/95 backdrop-blur-md">
                <div className="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        <div
                            className="flex items-center cursor-pointer group"
                            onClick={() => onNavigate('/homepage')}
                        >
                            <div className="flex items-center justify-center w-10 h-10 mr-3 rounded-xl">
                                <img
                                    src="/assets/images/kawanbumi-logo.png"
                                    alt="Logo KawanBumi"
                                    className="w-10 h-10"
                                />
                            </div>
                            <span className="text-2xl font-bold text-transparent bg-gradient-to-r from-sky-600 to-sky-800 bg-clip-text">
                                KawanBumi
                            </span>
                        </div>

                        <div className="hidden space-x-1 md:flex">
                            {navItems
                                .filter((item) => item.showOnDesktop)
                                .map((item) => (
                                    <button
                                        key={item.id}
                                        onClick={() => onNavigate(item.key)}
                                        className={`rounded-lg px-4 py-2 text-sm font-medium transition-all duration-200 ${
                                            currentPage === item.key
                                                ? 'bg-sky-100 text-sky-700 shadow-sm'
                                                : 'text-gray-600 hover:bg-sky-50 hover:text-sky-600'
                                        }`}
                                    >
                                        {item.label}
                                    </button>
                                ))}
                        </div>

                        <div className="flex items-center space-x-2">
                            {navItems
                                .filter(
                                    (item) =>
                                        !item.showOnDesktop &&
                                        getIconForNavItem(item.key),
                                )
                                .map((item) => {
                                    const IconComponent = getIconForNavItem(
                                        item.key,
                                    );
                                    return IconComponent ? (
                                        <button
                                            key={item.id}
                                            onClick={() => onNavigate(item.key)}
                                            className={`relative hidden rounded-lg p-2 transition-colors md:block ${
                                                currentPage === item.key
                                                    ? 'bg-sky-100 text-sky-600'
                                                    : 'text-gray-500 hover:bg-sky-50 hover:text-sky-600'
                                            }`}
                                            title={item.label}
                                        >
                                            <IconComponent size={20} />
                                        </button>
                                    ) : null;
                                })}
                            <button
                                onClick={onNotificationClick}
                                className="relative p-2 text-gray-500 transition-colors rounded-lg hover:bg-sky-50 hover:text-sky-600"
                                title="Notifikasi"
                            >
                                <Bell size={20} />
                                {unreadCount > 0 && (
                                    <Badge className="absolute flex items-center justify-center w-5 h-5 p-0 text-xs text-white bg-red-500 -right-1 -top-1">
                                        {unreadCount > 99 ? '99+' : unreadCount}
                                    </Badge>
                                )}
                            </button>
                            <ProfileMenuDropdown
                                user={user as User}
                                menuItems={profileMenuContent}
                            />
                            <button
                                onClick={() =>
                                    setIsMobileMenuOpen(!isMobileMenuOpen)
                                }
                                className="p-2 text-gray-500 transition-colors rounded-lg hover:bg-sky-50 hover:text-sky-600 md:hidden"
                                aria-label="Toggle mobile menu"
                            >
                                {isMobileMenuOpen ? (
                                    <X size={20} />
                                ) : (
                                    <Menu size={20} />
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            {isMobileMenuOpen && (
                <div
                    className="fixed inset-0 z-40 transition-opacity bg-black bg-opacity-50 md:hidden"
                    onClick={() => setIsMobileMenuOpen(false)}
                />
            )}
            <div
                className={`fixed left-0 top-0 z-50 h-full w-64 transform bg-white shadow-xl transition-transform duration-300 ease-in-out md:hidden ${
                    isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
                }`}
            >
                <div className="flex flex-col h-full">
                    <div className="flex items-center justify-between p-4 border-b border-sky-100">
                        <div
                            className="flex items-center cursor-pointer group"
                            onClick={() => {
                                onNavigate('/homepage');
                                setIsMobileMenuOpen(false);
                            }}
                        >
                            <div className="flex items-center justify-center w-8 h-8 mr-3 rounded-lg">
                                <img
                                    src="/assets/images/LogoSobatBumi.png"
                                    alt="Logo KawanBumi"
                                    className="w-8 h-8"
                                />
                            </div>
                            <span className="text-lg font-bold text-transparent bg-gradient-to-r from-sky-600 to-sky-800 bg-clip-text">
                                KawanBumi
                            </span>
                        </div>
                        <button
                            onClick={() => setIsMobileMenuOpen(false)}
                            className="p-2 text-gray-500 transition-colors rounded-lg hover:bg-sky-50 hover:text-sky-600"
                        >
                            <X size={20} />
                        </button>
                    </div>

                    <div className="flex-1 p-4 overflow-y-auto">
                        <nav className="space-y-2">
                            {navItems
                                .filter((item) => item.showOnMobile)
                                .map((item) => (
                                    <button
                                        key={item.id}
                                        onClick={() =>
                                            handleNavItemClick(item.key)
                                        }
                                        className={`flex w-full items-center rounded-lg px-4 py-3 text-left text-sm font-medium transition-all duration-200 ${
                                            currentPage === item.key
                                                ? 'bg-sky-100 text-sky-700 shadow-sm'
                                                : 'text-gray-600 hover:bg-sky-50 hover:text-sky-600'
                                        }`}
                                    >
                                        {item.label}
                                    </button>
                                ))}
                        </nav>
                    </div>

                    {user && (
                        <div className="p-4 border-t border-sky-100">
                            <div className="flex items-center space-x-3">
                                <div className="flex items-center justify-center w-8 h-8 rounded-full bg-sky-100">
                                    <span className="text-sm font-medium text-sky-700">
                                        {user.name?.charAt(0)?.toUpperCase() ||
                                            'U'}
                                    </span>
                                </div>
                                <div className="flex-1 min-w-0">
                                    <p className="text-sm font-medium text-gray-900 truncate">
                                        {user.name}
                                    </p>
                                    <p className="text-xs text-gray-500 truncate">
                                        {user.email}
                                    </p>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </>
    );
};

export default Navbar;
