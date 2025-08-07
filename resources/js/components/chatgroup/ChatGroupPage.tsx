'use client';

import React, { useState, useEffect } from 'react';
import { Search, Users, ArrowLeft } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card } from '@/components/ui/card';
import { router } from '@inertiajs/react';

interface Mission {
  id: number;
  title: string;
  thumbnail: string | null;
}

interface ChatUser {
  id: number;
  name: string;
  profile_url: string | null;
}

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
  const [showSidebar, setShowSidebar] = useState(true);

  // Filter chat groups based on search term
  const filteredChatGroups = chatGroups.filter(group =>
    group.mission.title.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const formatTimestamp = (timestamp: string) => {
    const date = new Date(timestamp);
    const now = new Date();
    const diffInHours = (now.getTime() - date.getTime()) / (1000 * 60 * 60);

    if (diffInHours < 24) {
      return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
      });
    } else if (diffInHours < 168) { // Less than a week
      return date.toLocaleDateString('id-ID', {
        weekday: 'short',
        hour: '2-digit',
        minute: '2-digit'
      });
    } else {
      return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
      });
    }
  };

  const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
  };

  const getLastMessage = (group: ChatGroup) => {
    return group.chats && group.chats.length > 0 ? group.chats[0] : null;
  };

  const handleGroupClick = (group: ChatGroup) => {
    router.visit(`/chatgroup/${group.id}`)
  };

  return (
    <div className="h-screen bg-background flex">
      {/* Sidebar - Chat Groups List */}
      <div className={`${showSidebar ? 'w-80' : 'w-0'} transition-all duration-300 border-r bg-card overflow-hidden md:w-80 md:block`}>
        <div className="p-4 border-b">
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-lg font-semibold">Grup Chat</h2>
            <Button
              variant="ghost"
              size="sm"
              className="md:hidden"
              onClick={() => setShowSidebar(false)}
            >
              <ArrowLeft className="h-4 w-4" />
            </Button>
          </div>

          <p className="text-sm text-muted-foreground mb-4">
            Daftar grup chat dari misi yang Anda ikuti
          </p>

          {/* Search Bar */}
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input
              type="text"
              placeholder="Cari grup chat..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10"
            />
          </div>
        </div>

        {/* Chat Groups List */}
        <div className="overflow-y-auto h-full pb-20">
          {filteredChatGroups.length === 0 ? (
            <div className="p-4">
              <div className="text-center py-8">
                <Users className="h-12 w-12 mx-auto text-muted-foreground mb-4" />
                <h3 className="text-lg font-medium text-foreground mb-2">
                  {searchTerm ? 'Tidak ada grup yang ditemukan' : 'Belum ada grup chat'}
                </h3>
                <p className="text-muted-foreground text-sm">
                  {searchTerm
                    ? 'Coba ubah kata kunci pencarian Anda'
                    : 'Grup chat akan muncul setelah Anda bergabung dengan misi'
                  }
                </p>
              </div>
            </div>
          ) : (
            <div className="space-y-1 p-2">
              {filteredChatGroups.map((group) => {
                const lastMessage = getLastMessage(group);

                return (
                  <Card
                    key={group.id}
                    className="p-3 cursor-pointer transition-all hover:bg-accent hover:shadow-md hover:scale-[1.02] duration-200"
                    onClick={() => handleGroupClick(group)}
                  >
                    <div className="flex items-start space-x-3">
                      <Avatar className="h-12 w-12 flex-shrink-0">
                        <AvatarImage
                          src={group.mission.thumbnail || ''}
                          alt={group.mission.title}
                        />
                        <AvatarFallback className="bg-primary text-primary-foreground text-xs">
                          {getInitials(group.mission.title)}
                        </AvatarFallback>
                      </Avatar>

                      <div className="flex-1 min-w-0">
                        <div className="flex justify-between items-start mb-1">
                          <h3 className="font-medium text-sm text-foreground truncate pr-2">
                            {group.mission.title}
                          </h3>
                          {group.unread_messages_count && group.unread_messages_count > 0 && (
                            <span className="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-primary rounded-full flex-shrink-0">
                              {group.unread_messages_count}
                            </span>
                          )}
                        </div>

                        {lastMessage && (
                          <div className="space-y-1">
                            <p className="text-xs text-muted-foreground truncate">
                              <span className="font-medium">Last Message:</span>{' '}
                              {lastMessage.content || 'Media'}
                            </p>
                            <span className="text-xs text-muted-foreground">
                              {formatTimestamp(lastMessage.created_at)}
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

          {/* Stats */}
          {filteredChatGroups.length > 0 && (
            <div className="mt-4 px-4 pb-4 text-center text-xs text-muted-foreground">
              Menampilkan {filteredChatGroups.length} dari {chatGroups.length} grup chat
            </div>
          )}
        </div>
      </div>

      {/* Main Content Area */}
      <div className="flex-1 flex flex-col">
        {/* Welcome Header */}
        <div className="p-4 border-b bg-card flex items-center justify-between">
          <div className="flex items-center space-x-3">
            <Button
              variant="ghost"
              size="sm"
              className="md:hidden"
              onClick={() => setShowSidebar(true)}
            >
              <ArrowLeft className="h-4 w-4" />
            </Button>
            <div>
              <h3 className="font-semibold text-foreground">
                Selamat Datang di Grup Chat
              </h3>
              <p className="text-sm text-muted-foreground">
                Pilih grup chat untuk memulai percakapan
              </p>
            </div>
          </div>
        </div>

        {/* Main Welcome Area */}
        <div className="flex-1 flex items-center justify-center p-8">
          <div className="text-center max-w-md">
            <div className="mb-6">
              <Users className="h-16 w-16 mx-auto text-muted-foreground mb-4" />
            </div>
            <h2 className="text-2xl font-bold text-foreground mb-2">
              Pilih Grup Chat
            </h2>
            <p className="text-muted-foreground mb-6">
              Pilih grup chat dari sidebar untuk mulai percakapan dengan sesama peserta misi.
            </p>

            {chatGroups.length === 0 ? (
              <div className="bg-accent rounded-lg p-6">
                <h3 className="font-medium text-foreground mb-2">
                  Belum ada grup chat tersedia
                </h3>
                <p className="text-sm text-muted-foreground">
                  Grup chat akan otomatis terbuat ketika Anda bergabung dengan sebuah misi.
                  Bergabunglah dengan misi untuk mulai berinteraksi dengan peserta lain!
                </p>
              </div>
            ) : filteredChatGroups.length === 0 && searchTerm ? (
              <div className="bg-accent rounded-lg p-6">
                <h3 className="font-medium text-foreground mb-2">
                  Tidak ada hasil pencarian
                </h3>
                <p className="text-sm text-muted-foreground">
                  Coba gunakan kata kunci yang berbeda untuk mencari grup chat.
                </p>
              </div>
            ) : (
             <div></div>
            )}
          </div>
        </div>

        {/* Quick Stats Footer */}
        <div className="p-4 border-t bg-card">
          <div className="flex justify-center space-x-6 text-sm text-muted-foreground">
            <div className="text-center">
              <div className="font-semibold text-foreground">{chatGroups.length}</div>
              <div>Total Grup</div>
            </div>
            <div className="text-center">
              <div className="font-semibold text-foreground">
                {chatGroups.reduce((total, group) => total + (group.unread_messages_count || 0), 0)}
              </div>
              <div>Pesan Belum Dibaca</div>
            </div>
            <div className="text-center">
              <div className="font-semibold text-foreground">
                {chatGroups.filter(group => group.chats && group.chats.length > 0).length}
              </div>
              <div>Grup Aktif</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default GroupChatPage;
