'use client';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/react';
import { Menu, Search, Users, X } from 'lucide-react';
import React, { useEffect, useState } from 'react';

interface Mission {
    id: number;
    title: string;
    thumbnail: string | null;
}

// interface ChatUser {
//     id: number;
//     name: string;
//     profile_url: string | null;
// }

interface ChatMessage {
    id: number;
    content: string | null;
    media_url: string | null;
    media_type: string | null;
    created_at: string;
    user: {
        id: number;
        name: string;
        profile_url: string | null;
    };
    reply_id: number | null;
}

interface ChatGroup {
    id: number;
    mission_id: number;
    mission: Mission;
    chats?: ChatMessage[];
    unread_messages_count?: number;
}

interface GroupChatPageProps {
    chatGroups: ChatGroup[];
}

const GroupChatPage: React.FC<GroupChatPageProps> = ({ chatGroups }) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [showSidebar, setShowSidebar] = useState(false);
    const [isMobile, setIsMobile] = useState(false);
    useEffect(() => {
        const checkMobile = () => {
            const mobile = window.innerWidth < 768;
            setIsMobile(mobile);
            if (!mobile) {
                setShowSidebar(true);
            } else {
                setShowSidebar(false);
            }
        };
        checkMobile();
        window.addEventListener('resize', checkMobile);
        return () => window.removeEventListener('resize', checkMobile);
    }, []);
    const filteredChatGroups = chatGroups.filter((group) =>
        group.mission.title.toLowerCase().includes(searchTerm.toLowerCase()),
    );

    const formatTimestamp = (timestamp: string) => {
        const date = new Date(timestamp);
        const now = new Date();
        const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60);

        if (diffInHours < 24) {
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
            });
        } else if (diffInHours < 168) {
            return date.toLocaleDateString('id-ID', {
                weekday: 'short',
                hour: '2-digit',
                minute: '2-digit',
            });
        } else {
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit',
            });
        }
    };

    const getInitials = (name: string) => {
        return name
            .split(' ')
            .map((n) => n[0])
            .join('')
            .toUpperCase()
            .slice(0, 2);
    };

    const getLastMessage = (group: ChatGroup) => {
        return group.chats && group.chats.length > 0 ? group.chats[0] : null;
    };

    const handleGroupClick = (group: ChatGroup) => {
        if (isMobile) {
            setShowSidebar(false);
        }
        router.visit(`/chatgroup/${group.id}`);
    };

    const toggleSidebar = () => {
        setShowSidebar(!showSidebar);
    };

    const handleOverlayClick = () => {
        if (isMobile && showSidebar) {
            setShowSidebar(false);
        }
    };

    return (
        <div className="relative flex h-screen bg-background">
            {isMobile && showSidebar && (
                <div
                    className="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm md:hidden"
                    onClick={handleOverlayClick}
                />
            )}
            <div
                className={` ${showSidebar ? 'translate-x-0' : '-translate-x-full'} ${isMobile ? 'fixed left-0 top-0 z-50 h-full' : 'relative'} w-80 max-w-[85vw] overflow-hidden border-r bg-card transition-transform duration-300 ease-in-out md:static md:max-w-none md:translate-x-0`}
            >
                <div className="flex flex-col h-full">
                    <div className="flex-shrink-0 p-4 border-b">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-lg font-semibold">Grup Chat</h2>
                            <Button
                                variant="ghost"
                                size="sm"
                                className="md:hidden"
                                onClick={() => setShowSidebar(false)}
                            >
                                <X className="w-4 h-4" />
                            </Button>
                        </div>

                        <p className="mb-4 text-sm text-muted-foreground">
                            Daftar grup chat dari misi yang Anda ikuti
                        </p>
                        <div className="relative">
                            <Search className="absolute w-4 h-4 transform -translate-y-1/2 left-3 top-1/2 text-muted-foreground" />
                            <Input
                                type="text"
                                placeholder="Cari grup chat..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="pl-10"
                            />
                        </div>
                    </div>
                    <div className="flex-1 overflow-y-auto">
                        {filteredChatGroups.length === 0 ? (
                            <div className="p-4">
                                <div className="py-8 text-center">
                                    <Users className="w-12 h-12 mx-auto mb-4 text-muted-foreground" />
                                    <h3 className="mb-2 text-lg font-medium text-foreground">
                                        {searchTerm
                                            ? 'Tidak ada grup yang ditemukan'
                                            : 'Belum ada grup chat'}
                                    </h3>
                                    <p className="text-sm text-muted-foreground">
                                        {searchTerm
                                            ? 'Coba ubah kata kunci pencarian Anda'
                                            : 'Grup chat akan muncul setelah Anda bergabung dengan misi'}
                                    </p>
                                </div>
                            </div>
                        ) : (
                            <div className="p-2 space-y-1">
                                {filteredChatGroups.map((group) => {
                                    const lastMessage = getLastMessage(group);

                                    return (
                                        <Card
                                            key={group.id}
                                            className="cursor-pointer p-3 transition-all duration-200 hover:scale-[1.02] hover:bg-accent hover:shadow-md active:scale-[0.98]"
                                            onClick={() =>
                                                handleGroupClick(group)
                                            }
                                        >
                                            <div className="flex items-start space-x-3">
                                                <Avatar className="flex-shrink-0 w-12 h-12">
                                                    <AvatarImage
                                                        src={
                                                            group.mission
                                                                .thumbnail || ''
                                                        }
                                                        alt={
                                                            group.mission.title
                                                        }
                                                    />
                                                    <AvatarFallback className="text-xs bg-sky-600 text-primary-foreground">
                                                        {getInitials(
                                                            group.mission.title,
                                                        )}
                                                    </AvatarFallback>
                                                </Avatar>

                                                <div className="flex-1 min-w-0">
                                                    <div className="flex items-start justify-between mb-1">
                                                        <h3 className="pr-2 text-sm font-medium truncate text-foreground">
                                                            {
                                                                group.mission
                                                                    .title
                                                            }
                                                        </h3>
                                                        {group.unread_messages_count &&
                                                            group.unread_messages_count >
                                                                0 && (
                                                                <span className="inline-flex min-w-[1.25rem] flex-shrink-0 items-center justify-center rounded-full bg-primary px-2 py-1 text-xs font-bold leading-none text-white">
                                                                    {group.unread_messages_count >
                                                                    99
                                                                        ? '99+'
                                                                        : group.unread_messages_count}
                                                                </span>
                                                            )}
                                                    </div>

                                                    {lastMessage && (
                                                        <div className="space-y-1">
                                                            <p className="text-xs truncate text-muted-foreground">
                                                                <span className="font-medium">
                                                                    Last
                                                                    Message:
                                                                </span>{' '}
                                                                {lastMessage.content ||
                                                                    'Media'}
                                                            </p>
                                                            <span className="text-xs text-muted-foreground">
                                                                {formatTimestamp(
                                                                    lastMessage.created_at,
                                                                )}
                                                            </span>
                                                        </div>
                                                    )}

                                                    {!lastMessage && (
                                                        <p className="text-xs text-muted-foreground">
                                                            Belum ada pesan
                                                        </p>
                                                    )}
                                                </div>
                                            </div>
                                        </Card>
                                    );
                                })}
                            </div>
                        )}
                    </div>
                    {filteredChatGroups.length > 0 && (
                        <div className="flex-shrink-0 px-4 py-2 mt-2 border-t">
                            <div className="text-xs text-center text-muted-foreground">
                                Menampilkan {filteredChatGroups.length} dari{' '}
                                {chatGroups.length} grup chat
                            </div>
                        </div>
                    )}
                </div>
            </div>
            <div className="flex flex-col flex-1 min-w-0">
                <div className="flex items-center justify-between p-4 border-b bg-card">
                    <div className="flex items-center space-x-3">
                        <Button
                            variant="ghost"
                            size="sm"
                            className="md:hidden"
                            onClick={toggleSidebar}
                        >
                            <Menu className="w-4 h-4" />
                        </Button>
                        <div className="min-w-0">
                            <h3 className="font-semibold text-foreground">
                                Selamat Datang di Grup Chat
                            </h3>
                            <p className="text-sm text-muted-foreground">
                                Pilih grup chat untuk memulai percakapan
                            </p>
                        </div>
                    </div>
                </div>

                <div className="flex items-center justify-center flex-1 p-4 sm:p-8">
                    <div className="w-full max-w-md text-center">
                        <div className="mb-6">
                            <Users className="w-12 h-12 mx-auto mb-4 text-muted-foreground sm:h-16 sm:w-16" />
                        </div>
                        <h2 className="mb-2 text-xl font-bold text-foreground sm:text-2xl">
                            Pilih Grup Chat
                        </h2>
                        <p className="mb-6 text-sm text-muted-foreground sm:text-base">
                            {isMobile ? (
                                <>
                                    Tap tombol menu di atas untuk melihat daftar
                                    grup chat dan mulai percakapan.
                                </>
                            ) : (
                                <>
                                    Pilih grup chat dari sidebar untuk mulai
                                    percakapan dengan sesama peserta misi.
                                </>
                            )}
                        </p>
                        {isMobile && (
                            <Button
                                onClick={toggleSidebar}
                                className="w-full mb-6 sm:w-auto"
                                size="lg"
                            >
                                <Menu className="w-4 h-4 mr-2" />
                                Lihat Grup Chat
                            </Button>
                        )}

                        {chatGroups.length === 0 ? (
                            <div className="p-4 rounded-lg bg-accent sm:p-6">
                                <h3 className="mb-2 font-medium text-foreground">
                                    Belum ada grup chat tersedia
                                </h3>
                                <p className="text-xs text-muted-foreground sm:text-sm">
                                    Grup chat akan otomatis terbuat ketika Anda
                                    bergabung dengan sebuah misi. Bergabunglah
                                    dengan misi untuk mulai berinteraksi dengan
                                    peserta lain!
                                </p>
                            </div>
                        ) : filteredChatGroups.length === 0 && searchTerm ? (
                            <div className="p-4 rounded-lg bg-accent sm:p-6">
                                <h3 className="mb-2 font-medium text-foreground">
                                    Tidak ada hasil pencarian
                                </h3>
                                <p className="text-xs text-muted-foreground sm:text-sm">
                                    Coba gunakan kata kunci yang berbeda untuk
                                    mencari grup chat.
                                </p>
                            </div>
                        ) : null}
                    </div>
                </div>
                <div className="p-3 border-t bg-card sm:p-4">
                    <div className="flex justify-center space-x-4 text-xs text-muted-foreground sm:space-x-6 sm:text-sm">
                        <div className="text-center">
                            <div className="font-semibold text-foreground">
                                {chatGroups.length}
                            </div>
                            <div className="hidden sm:block">Total Grup</div>
                            <div className="sm:hidden">Grup</div>
                        </div>
                        <div className="text-center">
                            <div className="font-semibold text-foreground">
                                {chatGroups.reduce(
                                    (total, group) =>
                                        total +
                                        (group.unread_messages_count || 0),
                                    0,
                                )}
                            </div>
                            <div className="hidden sm:block">
                                Pesan Belum Dibaca
                            </div>
                            <div className="sm:hidden">Belum Dibaca</div>
                        </div>
                        <div className="text-center">
                            <div className="font-semibold text-foreground">
                                {
                                    chatGroups.filter(
                                        (group) =>
                                            group.chats &&
                                            group.chats.length > 0,
                                    ).length
                                }
                            </div>
                            <div className="hidden sm:block">Grup Aktif</div>
                            <div className="sm:hidden">Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default GroupChatPage;
