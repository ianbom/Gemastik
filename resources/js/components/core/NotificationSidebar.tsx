import { PageProps } from '@/types';
import { Notification } from '@/types/notification/interface';
import { router, usePage } from '@inertiajs/react';
import { AlertCircle, CheckCircle, Heart, Info, Trash2, X } from 'lucide-react';
import React from 'react';
import Button from './Button';
const ScrollArea = ({
    children,
    className = '',
}: {
    children: React.ReactNode;
    className?: string;
}) => <div className={`overflow-y-auto ${className}`}>{children}</div>;

interface NotificationSidebarProps {
    isOpen: boolean;
    onClose: () => void;
}

const NotificationSidebar: React.FC<NotificationSidebarProps> = ({
    isOpen,
    onClose,
}) => {
    const { notifications: sharedNotifications } = usePage<PageProps>().props;
    const notifications = sharedNotifications?.notifications || [];
    const unread_count = sharedNotifications?.unread_count || 0;

    const getIcon = (type: string | null) => {
        switch (type) {
            case 'report_update':
                return (
                    <AlertCircle className="h-5 w-5 flex-shrink-0 text-blue-500" />
                );
            case 'mission_assigned':
                return (
                    <CheckCircle className="h-5 w-5 flex-shrink-0 text-green-500" />
                );
            case 'badge_earned':
                return (
                    <Heart className="h-5 w-5 flex-shrink-0 text-purple-500" />
                );
            case 'donation_received':
                return (
                    <Heart className="h-5 w-5 flex-shrink-0 text-pink-500" />
                );
            default:
                return <Info className="h-5 w-5 flex-shrink-0 text-gray-500" />;
        }
    };

    // SOLUSI 1: Tambahkan 'only' parameter untuk refresh data notifikasi
    const handleMarkAsRead = (
        e: React.MouseEvent,
        notificationId: string | number,
    ) => {
        e.preventDefault();
        e.stopPropagation();

        router.put(
            `/read-notification/${notificationId}`,
            {},
            {
                preserveScroll: true,
                only: ['notifications'], // Hanya refresh data notifications
                onSuccess: () => {
                    console.log(
                        `Notifikasi ${notificationId} berhasil ditandai sebagai dibaca.`,
                    );
                },
                onError: (errors) => {
                    console.error(
                        'Gagal menandai notifikasi sebagai dibaca:',
                        errors,
                    );
                },
            },
        );
    };

    const handleDeleteNotification = (
        e: React.MouseEvent,
        notificationId: string | number,
    ) => {
        e.preventDefault();
        e.stopPropagation();

        if (
            window.confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')
        ) {
            router.delete(
                route('notification.delete', { id: notificationId }),
                {
                    preserveScroll: true,
                    only: ['notifications'], // Hanya refresh data notifications
                    onSuccess: () => {
                        console.log(
                            `Notifikasi ${notificationId} berhasil dihapus.`,
                        );
                    },
                    onError: (errors) => {
                        console.error('Gagal menghapus notifikasi:', errors);
                    },
                },
            );
        }
    };

    const handleViewAll = () => {
        router.put(
            route('notification.readAll'),
            {},
            {
                preserveScroll: true,
                only: ['notifications'], // Hanya refresh data notifications
                onSuccess: () => {
                    console.log(
                        'Semua notifikasi berhasil ditandai sebagai dibaca.',
                    );
                    onClose();
                },
                onError: (errors) => {
                    console.error(
                        'Gagal menandai semua notifikasi sebagai terbaca:',
                        errors,
                    );
                    onClose();
                },
            },
        );
    };

    if (!isOpen) return null;

    return (
        <>
            {/* Overlay */}
            <div
                className="fixed inset-0 z-40 bg-black bg-opacity-50 transition-opacity"
                onClick={onClose}
            />

            {/* Sidebar */}
            <div className="fixed right-0 top-0 z-50 h-full w-full max-w-sm transform bg-white shadow-xl transition-transform duration-300 ease-in-out">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-gray-200 p-4">
                    <h2 className="text-lg font-semibold text-gray-900">
                        Notifikasi
                        {unread_count > 0 && (
                            <span className="ml-2 inline-block rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">
                                {unread_count}
                            </span>
                        )}
                    </h2>
                    <Button variant="ghost" size="sm" onClick={onClose}>
                        <X className="h-5 w-5" />
                    </Button>
                </div>

                {/* Notifications List */}
                <ScrollArea className="h-[calc(100vh-8rem)]">
                    {notifications && notifications.length > 0 ? (
                        <div className="space-y-2 p-3">
                            {notifications.map((notification: Notification) => (
                                <div
                                    key={notification.id}
                                    className={`group relative cursor-pointer rounded-lg border p-3 transition-colors hover:shadow-md ${
                                        !notification.is_read
                                            ? 'border-blue-200 bg-blue-50'
                                            : 'border-gray-200 bg-white hover:bg-gray-50'
                                    }`}
                                >
                                    <div className="flex items-start space-x-3">
                                        {getIcon(notification.type)}
                                        <div className="min-w-0 flex-1">
                                            <h3
                                                className={`text-sm font-medium ${
                                                    !notification.is_read
                                                        ? 'text-gray-900'
                                                        : 'text-gray-700'
                                                }`}
                                            >
                                                {notification.title}
                                            </h3>
                                            <p className="mt-1 text-sm text-gray-600">
                                                {notification.body}
                                            </p>
                                            <p className="mt-2 text-xs text-gray-500">
                                                {new Date(
                                                    notification.created_at,
                                                ).toLocaleString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'long',
                                                    year: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                })}
                                            </p>
                                            {!notification.is_read && (
                                                <Button
                                                    variant="ghost"
                                                    size="sm"
                                                    className="mt-2 h-auto p-0 text-xs font-semibold text-blue-600 hover:underline"
                                                    onClick={(
                                                        e: React.MouseEvent<HTMLButtonElement>,
                                                    ) =>
                                                        handleMarkAsRead(
                                                            e,
                                                            notification.id,
                                                        )
                                                    }
                                                >
                                                    Tandai Sudah Dibaca
                                                </Button>
                                            )}
                                        </div>
                                    </div>

                                    {/* Tombol Hapus */}
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        className="absolute right-2 top-2 h-auto p-1 text-gray-400 opacity-0 transition-opacity hover:bg-red-50 hover:text-red-600 group-hover:opacity-100"
                                        onClick={(
                                            e: React.MouseEvent<HTMLButtonElement>,
                                        ) =>
                                            handleDeleteNotification(
                                                e,
                                                notification.id,
                                            )
                                        }
                                        title="Hapus notifikasi"
                                    >
                                        <Trash2 className="h-4 w-4" />
                                    </Button>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="flex h-full items-center justify-center">
                            <div className="text-center">
                                <Info className="mx-auto mb-3 h-12 w-12 text-gray-400" />
                                <p className="text-sm italic text-gray-500">
                                    Tidak ada notifikasi.
                                </p>
                            </div>
                        </div>
                    )}
                </ScrollArea>

                {/* Footer */}
                <div className="border-t border-gray-200 p-4">
                    <Button
                        variant="outline"
                        className="w-full"
                        onClick={handleViewAll}
                    >
                        Lihat Semua Notifikasi
                    </Button>
                </div>
            </div>
        </>
    );
};

export default NotificationSidebar;
