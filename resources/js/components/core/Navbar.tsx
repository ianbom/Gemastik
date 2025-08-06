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
            <nav className="fixed left-0 right-0 top-0 z-50 border-b border-emerald-100 bg-white/95 shadow-sm backdrop-blur-md">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 items-center justify-between">
                        <div
                            className="group flex cursor-pointer items-center"
                            onClick={() => onNavigate('/homepage')}
                        >
                            <div className="mr-3 flex h-10 w-10 items-center justify-center rounded-xl">
                                <img
                                    src="/assets/images/LogoSobatBumi.png"
                                    alt="Logo Sobat Bumi"
                                    className="h-10 w-10"
                                />
                            </div>
                            <span className="bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-2xl font-bold text-transparent">
                                SobatBumi
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
                                                ? 'bg-emerald-100 text-emerald-700 shadow-sm'
                                                : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-600'
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
                                                    ? 'bg-emerald-100 text-emerald-600'
                                                    : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-600'
                                            }`}
                                            title={item.label}
                                        >
                                            <IconComponent size={20} />
                                        </button>
                                    ) : null;
                                })}
                            <button
                                onClick={onNotificationClick}
                                className="relative rounded-lg p-2 text-gray-500 transition-colors hover:bg-emerald-50 hover:text-emerald-600"
                                title="Notifikasi"
                            >
                                <Bell size={20} />
                                {unreadCount > 0 && (
                                    <Badge className="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center bg-red-500 p-0 text-xs text-white">
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
                                className="rounded-lg p-2 text-gray-500 transition-colors hover:bg-emerald-50 hover:text-emerald-600 md:hidden"
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
                    className="fixed inset-0 z-40 bg-black bg-opacity-50 transition-opacity md:hidden"
                    onClick={() => setIsMobileMenuOpen(false)}
                />
            )}
            <div
                className={`fixed left-0 top-0 z-50 h-full w-64 transform bg-white shadow-xl transition-transform duration-300 ease-in-out md:hidden ${
                    isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
                }`}
            >
                <div className="flex h-full flex-col">
                    <div className="flex items-center justify-between border-b border-emerald-100 p-4">
                        <div
                            className="group flex cursor-pointer items-center"
                            onClick={() => {
                                onNavigate('/homepage');
                                setIsMobileMenuOpen(false);
                            }}
                        >
                            <div className="mr-3 flex h-8 w-8 items-center justify-center rounded-lg">
                                <img
                                    src="/assets/images/LogoSobatBumi.png"
                                    alt="Logo Sobat Bumi"
                                    className="h-8 w-8"
                                />
                            </div>
                            <span className="bg-gradient-to-r from-emerald-600 to-emerald-800 bg-clip-text text-lg font-bold text-transparent">
                                SobatBumi
                            </span>
                        </div>
                        <button
                            onClick={() => setIsMobileMenuOpen(false)}
                            className="rounded-lg p-2 text-gray-500 transition-colors hover:bg-emerald-50 hover:text-emerald-600"
                        >
                            <X size={20} />
                        </button>
                    </div>

                    <div className="flex-1 overflow-y-auto p-4">
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
                                                ? 'bg-emerald-100 text-emerald-700 shadow-sm'
                                                : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-600'
                                        }`}
                                    >
                                        {item.label}
                                    </button>
                                ))}
                        </nav>
                    </div>

                    {user && (
                        <div className="border-t border-emerald-100 p-4">
                            <div className="flex items-center space-x-3">
                                <div className="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100">
                                    <span className="text-sm font-medium text-emerald-700">
                                        {user.name?.charAt(0)?.toUpperCase() ||
                                            'U'}
                                    </span>
                                </div>
                                <div className="min-w-0 flex-1">
                                    <p className="truncate text-sm font-medium text-gray-900">
                                        {user.name}
                                    </p>
                                    <p className="truncate text-xs text-gray-500">
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
