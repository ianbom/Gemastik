import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { router } from '@inertiajs/react';
import {
    ArrowLeft,
    File,
    Image,
    Paperclip,
    Reply,
    Search,
    Send,
    Users,
    Video,
    X,
} from 'lucide-react';
import { useEffect, useRef, useState } from 'react';

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
    reply_to?: ChatMessage; // For nested reply data
}

interface ChatGroup {
    id: number;
    mission_id: number;
    mission: Mission;
    chats?: ChatMessage[];
    unread_messages_count?: number;
}

interface DetailChatGroupProps {
    messages: ChatMessage[];
    allGroups: ChatGroup[];
    activeGroupId?: number;
    currentUserId: number;
}

const DetailChatGroup: React.FC<DetailChatGroupProps> = ({
    messages,
    allGroups,
    activeGroupId,
    currentUserId,
}) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [newMessage, setNewMessage] = useState('');
    const [isSending, setIsSending] = useState(false);
    const [showSidebar, setShowSidebar] = useState(true);
    const [replyingTo, setReplyingTo] = useState<ChatMessage | null>(null);
    const [selectedFile, setSelectedFile] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);
    const messagesEndRef = useRef<HTMLDivElement>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    console.log(
        'Tipe activeGroupId:',
        typeof activeGroupId,
        '| Nilai:',
        activeGroupId,
    );

    const numericActiveGroupId = Number(activeGroupId);
    const activeGroup =
        allGroups.find((group) => group.id === numericActiveGroupId) ||
        allGroups[0];

    if (!allGroups.find((group) => group.id === numericActiveGroupId)) {
        console.warn(
            `Grup dengan ID ${numericActiveGroupId} tidak ditemukan di dalam 'allGroups'. Kembali ke grup pertama.`,
        );
    }

    console.log('Grup Aktif yang Ditemukan:', activeGroup);

    // Filter chat groups based on search term
    const filteredChatGroups = allGroups.filter((group) =>
        group.mission.title.toLowerCase().includes(searchTerm.toLowerCase()),
    );

    // Scroll to bottom when messages change
    useEffect(() => {
        // scrollToBottom();
    }, [messages]);

    // Handle file selection
    const handleFileSelect = (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files?.[0];
        if (file) {
            // Check file size (10MB limit)
            if (file.size > 10 * 1024 * 1024) {
                alert('File terlalu besar. Maksimal 10MB.');
                return;
            }

            // Check file type
            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'video/mp4',
                'video/webm',
                'video/ogg',
            ];
            if (!allowedTypes.includes(file.type)) {
                alert(
                    'Tipe file tidak didukung. Gunakan gambar (JPEG, PNG, GIF) atau video (MP4, WebM, OGG).',
                );
                return;
            }

            setSelectedFile(file);

            // Create preview URL
            const url = URL.createObjectURL(file);
            setPreviewUrl(url);
        }
    };

    // Clear selected file
    const clearSelectedFile = () => {
        setSelectedFile(null);
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            setPreviewUrl(null);
        }
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    // Clear reply
    const clearReply = () => {
        setReplyingTo(null);
    };

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
            // Less than a week
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

    const formatMessageDate = (timestamp: string) => {
        const date = new Date(timestamp);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
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

    // Find replied message
    const findRepliedMessage = (replyId: number | null): ChatMessage | null => {
        if (!replyId) return null;
        return messages.find((msg) => msg.id === replyId) || null;
    };

    const handleGroupClick = (group: ChatGroup) => {
        const targetGroupId = group.id;

        if (targetGroupId !== activeGroupId) {
            console.log(
                `Navigating from group ${activeGroupId} to ${targetGroupId}`,
            );

            router.get(
                route('chatgroup.show', { id: targetGroupId }),
                {},
                {
                    preserveState: true,
                    replace: true,
                },
            );
        }

        // Sembunyikan sidebar di mobile setelah pemilihan
        if (window.innerWidth < 768) {
            setShowSidebar(false);
        }
    };

    const handleSendMessage = async (e: React.FormEvent) => {
        e.preventDefault();
        if ((!newMessage.trim() && !selectedFile) || isSending) return;

        setIsSending(true);

        try {
            const formData = new FormData();

            if (newMessage.trim()) {
                formData.append('content', newMessage.trim());
            }

            if (selectedFile) {
                formData.append('media_url', selectedFile);
            }

            if (replyingTo) {
                formData.append('reply_id', replyingTo.id.toString());
            }

            await router.post(
                `/chat-groups/${activeGroupId}/messages`,
                formData,
                {
                    preserveState: true,
                    preserveScroll: true,
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onSuccess: () => {
                        setNewMessage('');
                        clearSelectedFile();
                        clearReply();
                    },
                },
            );
        } catch (error) {
            console.error('Failed to send message:', error);
        } finally {
            setIsSending(false);
        }
    };

    // Group messages by date
    const groupMessagesByDate = (messages: ChatMessage[]) => {
        const grouped: { [key: string]: ChatMessage[] } = {};
        messages.forEach((message) => {
            const dateKey = formatMessageDate(message.created_at);
            if (!grouped[dateKey]) {
                grouped[dateKey] = [];
            }
            grouped[dateKey].push(message);
        });
        return grouped;
    };

    const groupedMessages = groupMessagesByDate(messages);

    // Check if message is from current user
    const isOwnMessage = (message: ChatMessage) => {
        return message.user.id === currentUserId;
    };

    // Handle reply to message
    const handleReply = (message: ChatMessage) => {
        setReplyingTo(message);
    };

    // Get file type icon
    const getFileIcon = (fileType: string) => {
        if (fileType.startsWith('image/')) return <Image className="w-4 h-4" />;
        if (fileType.startsWith('video/')) return <Video className="w-4 h-4" />;
        return <File className="w-4 h-4" />;
    };

    return (
        <div className="flex h-screen bg-background">
            {/* Hidden file input */}
            <input
                ref={fileInputRef}
                type="file"
                accept="image/*,video/*"
                onChange={handleFileSelect}
                className="hidden"
            />

            {/* Sidebar - Chat Groups List */}
            <div
                className={`${showSidebar ? 'w-80' : 'w-0'} overflow-hidden border-r bg-card transition-all duration-300 md:block md:w-80`}
            >
                <div className="p-4 border-b">
                    <div className="flex items-center justify-between mb-4">
                        <h2 className="text-lg font-semibold">Grup Chat</h2>
                        <Button
                            variant="ghost"
                            size="sm"
                            className="md:hidden"
                            onClick={() => setShowSidebar(false)}
                        >
                            <ArrowLeft className="w-4 h-4" />
                        </Button>
                    </div>

                    {/* Search Bar */}
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

                {/* Chat Groups List */}
                <div className="h-full pb-20 overflow-y-auto">
                    {filteredChatGroups.length === 0 ? (
                        <div className="p-4">
                            <div className="py-8 text-center">
                                <Users className="w-12 h-12 mx-auto mb-4 text-muted-foreground" />
                                <p className="text-sm text-muted-foreground">
                                    {searchTerm
                                        ? 'Tidak ada grup yang ditemukan'
                                        : 'Belum ada grup chat'}
                                </p>
                            </div>
                        </div>
                    ) : (
                        <div className="p-2 space-y-1">
                            {filteredChatGroups.map((group) => {
                                const lastMessage = getLastMessage(group);
                                const isActive = group.id === activeGroupId;

                                return (
                                    <Card
                                        key={group.id}
                                        className={`cursor-pointer p-3 transition-all hover:bg-accent ${
                                            isActive
                                                ? 'border-primary bg-accent'
                                                : ''
                                        }`}
                                        onClick={() => handleGroupClick(group)}
                                    >
                                        <div className="flex items-start space-x-3">
                                            <Avatar className="flex-shrink-0 w-10 h-10">
                                                <AvatarImage
                                                    src={
                                                        group.mission
                                                            .thumbnail || ''
                                                    }
                                                    alt={group.mission.title}
                                                />
                                                <AvatarFallback className="text-xs bg-primary text-primary-foreground">
                                                    {getInitials(
                                                        group.mission.title,
                                                    )}
                                                </AvatarFallback>
                                            </Avatar>

                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-start justify-between mb-1">
                                                    <h3 className="pr-2 text-sm font-medium truncate text-foreground">
                                                        {group.mission.title}
                                                    </h3>
                                                    {group.unread_messages_count &&
                                                        group.unread_messages_count >
                                                            0 && (
                                                            <span className="inline-flex items-center justify-center flex-shrink-0 px-2 py-1 text-xs font-bold leading-none text-white rounded-full bg-primary">
                                                                {
                                                                    group.unread_messages_count
                                                                }
                                                            </span>
                                                        )}
                                                </div>

                                                {lastMessage && (
                                                    <div className="space-y-1">
                                                        <p className="text-xs truncate text-muted-foreground">
                                                            <span className="font-medium">
                                                                Last Message :
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
            </div>

            {/* Main Chat Area */}
            <div className="flex flex-col flex-1">
                {activeGroup ? (
                    <>
                        {/* Chat Header */}
                        <div className="flex items-center justify-between p-4 border-b bg-card">
                            <div className="flex items-center space-x-3">
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    className="md:hidden"
                                    onClick={() => setShowSidebar(true)}
                                >
                                    <ArrowLeft className="w-4 h-4" />
                                </Button>
                                <Avatar className="w-10 h-10">
                                    <AvatarImage
                                        src={
                                            activeGroup.mission.thumbnail || ''
                                        }
                                        alt={activeGroup.mission.title}
                                    />
                                    <AvatarFallback className="bg-primary text-primary-foreground">
                                        {getInitials(activeGroup.mission.title)}
                                    </AvatarFallback>
                                </Avatar>
                                <div>
                                    <h3 className="font-semibold text-foreground">
                                        {activeGroup.mission.title}
                                    </h3>
                                    <p className="text-sm text-muted-foreground">
                                        Grup Chat Misi
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Messages Area */}
                        <div className="flex-1 p-4 space-y-4 overflow-y-auto">
                            {Object.entries(groupedMessages).map(
                                ([date, dateMessages]) => (
                                    <div key={date}>
                                        {/* Date Separator */}
                                        <div className="flex items-center justify-center my-4">
                                            <div className="px-3 py-1 rounded-full bg-accent">
                                                <span className="text-xs font-medium text-muted-foreground">
                                                    {date}
                                                </span>
                                            </div>
                                        </div>

                                        {/* Messages for this date */}
                                        {dateMessages.map((message, index) => {
                                            const isOwn = isOwnMessage(message);
                                            const isSameUser =
                                                index > 0 &&
                                                dateMessages[index - 1].user
                                                    .id === message.user.id;
                                            const isRecentMessage =
                                                index > 0 &&
                                                new Date(
                                                    message.created_at,
                                                ).getTime() -
                                                    new Date(
                                                        dateMessages[
                                                            index - 1
                                                        ].created_at,
                                                    ).getTime() <
                                                    300000;
                                            const repliedMessage =
                                                findRepliedMessage(
                                                    message.reply_id,
                                                );

                                            return (
                                                <div
                                                    key={message.id}
                                                    className={`group flex ${isOwn ? 'justify-end' : 'justify-start'} ${
                                                        isSameUser &&
                                                        isRecentMessage
                                                            ? 'mt-1'
                                                            : 'mt-4'
                                                    }`}
                                                >
                                                    <div
                                                        className={`flex max-w-[70%] items-start ${isOwn ? 'flex-row-reverse' : 'flex-row'}`}
                                                    >
                                                        {/* Avatar - only show if not same user or not recent */}
                                                        {(!isSameUser ||
                                                            !isRecentMessage) && (
                                                            <Avatar
                                                                className={`h-8 w-8 flex-shrink-0 ${isOwn ? 'ml-3' : 'mr-3'}`}
                                                            >
                                                                <AvatarImage
                                                                    src={
                                                                        message
                                                                            .user
                                                                            .profile_url ||
                                                                        ''
                                                                    }
                                                                    alt={
                                                                        message
                                                                            .user
                                                                            .name
                                                                    }
                                                                />
                                                                <AvatarFallback className="text-xs bg-secondary text-secondary-foreground">
                                                                    {getInitials(
                                                                        message
                                                                            .user
                                                                            .name,
                                                                    )}
                                                                </AvatarFallback>
                                                            </Avatar>
                                                        )}

                                                        {/* Spacer when avatar is hidden */}
                                                        {isSameUser &&
                                                            isRecentMessage && (
                                                                <div
                                                                    className={`w-8 flex-shrink-0 ${isOwn ? 'ml-3' : 'mr-3'}`}
                                                                />
                                                            )}

                                                        <div className="relative flex-1 min-w-0">
                                                            {/* Name and timestamp - only show if not same user or not recent */}
                                                            {(!isSameUser ||
                                                                !isRecentMessage) && (
                                                                <div
                                                                    className={`mb-1 flex items-center justify-between ${isOwn ? 'flex-row-reverse' : 'flex-row'}`}
                                                                >
                                                                    <div
                                                                        className={`flex items-baseline space-x-2 ${isOwn ? 'flex-row-reverse space-x-reverse' : ''}`}
                                                                    >
                                                                        <span className="text-sm font-medium text-foreground">
                                                                            {isOwn
                                                                                ? 'Anda'
                                                                                : message
                                                                                      .user
                                                                                      .name}
                                                                        </span>
                                                                        <span className="text-xs text-muted-foreground">
                                                                            {formatTimestamp(
                                                                                message.created_at,
                                                                            )}
                                                                        </span>
                                                                    </div>

                                                                    {/* Reply button */}
                                                                    <Button
                                                                        variant="ghost"
                                                                        size="sm"
                                                                        className="w-6 h-6 p-0 transition-opacity opacity-0 group-hover:opacity-100"
                                                                        onClick={() =>
                                                                            handleReply(
                                                                                message,
                                                                            )
                                                                        }
                                                                    >
                                                                        <Reply className="w-3 h-3" />
                                                                    </Button>
                                                                </div>
                                                            )}

                                                            {/* Message bubble */}
                                                            <div
                                                                className={`relative rounded-lg p-3 ${
                                                                    isOwn
                                                                        ? 'ml-auto bg-primary text-primary-foreground'
                                                                        : 'border bg-card'
                                                                }`}
                                                            >
                                                                {/* Replied message preview */}
                                                                {repliedMessage && (
                                                                    <div
                                                                        className={`mb-2 rounded border-l-2 p-2 ${
                                                                            isOwn
                                                                                ? 'border-primary-foreground/30 bg-primary-foreground/10'
                                                                                : 'border-primary bg-muted'
                                                                        }`}
                                                                    >
                                                                        <div
                                                                            className={`text-xs font-medium ${
                                                                                isOwn
                                                                                    ? 'text-primary-foreground/70'
                                                                                    : 'text-muted-foreground'
                                                                            }`}
                                                                        >
                                                                            Membalas{' '}
                                                                            {repliedMessage
                                                                                .user
                                                                                .id ===
                                                                            currentUserId
                                                                                ? 'Anda'
                                                                                : repliedMessage
                                                                                      .user
                                                                                      .name}
                                                                        </div>
                                                                        <div
                                                                            className={`text-xs ${
                                                                                isOwn
                                                                                    ? 'text-primary-foreground/90'
                                                                                    : 'text-foreground/70'
                                                                            } truncate`}
                                                                        >
                                                                            {repliedMessage.content ||
                                                                                (repliedMessage.media_url
                                                                                    ? 'Media'
                                                                                    : 'Pesan')}
                                                                        </div>
                                                                    </div>
                                                                )}

                                                                {message.content && (
                                                                    <p className="text-sm break-words whitespace-pre-wrap">
                                                                        {
                                                                            message.content
                                                                        }
                                                                    </p>
                                                                )}

                                                                {/* Updated Media Display - Similar to Comment System */}
                                                                {message.media_url && (
                                                                    <div
                                                                        className={`${message.content ? 'mt-3' : ''}`}
                                                                    >
                                                                        {message.media_type ===
                                                                            'video' ||
                                                                        message.media_type?.startsWith(
                                                                            'video/',
                                                                        ) ? (
                                                                            <video
                                                                                src={`/storage/${message.media_url}`}
                                                                                controls
                                                                                preload="metadata"
                                                                                className="w-full max-w-md rounded-lg aspect-video"
                                                                            />
                                                                        ) : message.media_type?.startsWith(
                                                                              'image',
                                                                          ) ? (
                                                                            <img
                                                                                src={`/storage/${message.media_url}`}
                                                                                alt={`Media untuk pesan`}
                                                                                className="object-cover w-auto transition-opacity rounded-lg shadow-sm cursor-pointer max-h-64 hover:opacity-95"
                                                                                onClick={() =>
                                                                                    window.open(
                                                                                        `/storage/${message.media_url}`,
                                                                                        '_blank',
                                                                                    )
                                                                                }
                                                                                loading="lazy"
                                                                            />
                                                                        ) : (
                                                                            <a
                                                                                href={`/storage/${message.media_url}`}
                                                                                target="_blank"
                                                                                rel="noopener noreferrer"
                                                                                className={`inline-flex items-center space-x-2 rounded border p-2 hover:underline ${
                                                                                    isOwn
                                                                                        ? 'border-primary-foreground/30 text-primary-foreground'
                                                                                        : 'border-border text-primary'
                                                                                }`}
                                                                            >
                                                                                <Paperclip className="w-4 h-4" />
                                                                                <span className="text-sm">
                                                                                    File
                                                                                    Attachment
                                                                                </span>
                                                                            </a>
                                                                        )}
                                                                    </div>
                                                                )}
                                                            </div>

                                                            {/* Show timestamp for consecutive messages on hover */}
                                                            {isSameUser &&
                                                                isRecentMessage && (
                                                                    <div
                                                                        className={`mt-1 flex items-center justify-between ${isOwn ? 'flex-row-reverse' : 'flex-row'}`}
                                                                    >
                                                                        <div
                                                                            className={`text-xs text-muted-foreground opacity-0 transition-opacity hover:opacity-100 ${
                                                                                isOwn
                                                                                    ? 'text-right'
                                                                                    : 'text-left'
                                                                            }`}
                                                                        >
                                                                            {formatTimestamp(
                                                                                message.created_at,
                                                                            )}
                                                                        </div>

                                                                        {/* Reply button for consecutive messages */}
                                                                        <Button
                                                                            variant="ghost"
                                                                            size="sm"
                                                                            className="w-6 h-6 p-0 transition-opacity opacity-0 group-hover:opacity-100"
                                                                            onClick={() =>
                                                                                handleReply(
                                                                                    message,
                                                                                )
                                                                            }
                                                                        >
                                                                            <Reply className="w-3 h-3" />
                                                                        </Button>
                                                                    </div>
                                                                )}
                                                        </div>
                                                    </div>
                                                </div>
                                            );
                                        })}
                                    </div>
                                ),
                            )}

                            {messages.length === 0 && (
                                <div className="flex flex-col items-center justify-center h-full py-12 text-center">
                                    <Users className="w-12 h-12 mb-4 text-muted-foreground" />
                                    <h3 className="mb-2 text-lg font-medium text-foreground">
                                        Belum ada pesan
                                    </h3>
                                    <p className="text-muted-foreground">
                                        Mulai percakapan dengan mengirim pesan
                                        pertama
                                    </p>
                                </div>
                            )}

                            <div ref={messagesEndRef} />
                        </div>

                        {/* Message Input */}
                        <div className="p-4 border-t bg-card">
                            {/* Reply Preview */}
                            {replyingTo && (
                                <div className="p-3 mb-3 border-l-4 rounded-lg border-primary bg-muted">
                                    <div className="flex items-start justify-between">
                                        <div className="flex-1">
                                            <div className="flex items-center mb-1 space-x-2">
                                                <Reply className="w-4 h-4 text-primary" />
                                                <span className="text-sm font-medium text-foreground">
                                                    Membalas{' '}
                                                    {replyingTo.user.id ===
                                                    currentUserId
                                                        ? 'Anda'
                                                        : replyingTo.user.name}
                                                </span>
                                            </div>
                                            <p className="text-sm truncate text-muted-foreground">
                                                {replyingTo.content ||
                                                    (replyingTo.media_url
                                                        ? 'Media'
                                                        : 'Pesan')}
                                            </p>
                                        </div>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            onClick={clearReply}
                                            className="w-6 h-6 p-0"
                                        >
                                            <X className="w-4 h-4" />
                                        </Button>
                                    </div>
                                </div>
                            )}

                            {/* File Preview */}
                            {selectedFile && previewUrl && (
                                <div className="p-3 mb-3 rounded-lg bg-muted">
                                    <div className="flex items-start justify-between">
                                        <div className="flex items-center space-x-3">
                                            {getFileIcon(selectedFile.type)}
                                            <div>
                                                <p className="text-sm font-medium">
                                                    {selectedFile.name}
                                                </p>
                                                <p className="text-xs text-muted-foreground">
                                                    {(
                                                        selectedFile.size /
                                                        1024 /
                                                        1024
                                                    ).toFixed(2)}{' '}
                                                    MB
                                                </p>
                                            </div>
                                        </div>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            onClick={clearSelectedFile}
                                            className="w-6 h-6 p-0"
                                        >
                                            <X className="w-4 h-4" />
                                        </Button>
                                    </div>

                                    {/* File Preview */}
                                    {selectedFile.type.startsWith('image/') && (
                                        <div className="mt-3">
                                            <img
                                                src={previewUrl}
                                                alt="Preview"
                                                className="object-cover w-auto border border-gray-200 rounded-lg shadow-sm max-h-32"
                                            />
                                        </div>
                                    )}

                                    {selectedFile.type.startsWith('video/') && (
                                        <div className="mt-3">
                                            <video
                                                src={previewUrl}
                                                className="w-full max-w-xs border border-gray-200 rounded-lg shadow-sm aspect-video"
                                                controls
                                            />
                                        </div>
                                    )}
                                </div>
                            )}

                            <form
                                onSubmit={handleSendMessage}
                                className="flex items-end space-x-2"
                            >
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    onClick={() =>
                                        fileInputRef.current?.click()
                                    }
                                    className="h-10 px-3"
                                >
                                    <Paperclip className="w-4 h-4" />
                                </Button>

                                <div className="flex-1">
                                    <Textarea
                                        value={newMessage}
                                        onChange={(e) =>
                                            setNewMessage(e.target.value)
                                        }
                                        placeholder="Tulis pesan..."
                                        className="max-h-32 min-h-[40px] resize-none"
                                        onKeyDown={(e) => {
                                            if (
                                                e.key === 'Enter' &&
                                                !e.shiftKey
                                            ) {
                                                e.preventDefault();
                                                handleSendMessage(e);
                                            }
                                        }}
                                    />
                                </div>

                                <Button
                                    type="submit"
                                    disabled={
                                        (!newMessage.trim() && !selectedFile) ||
                                        isSending
                                    }
                                    size="sm"
                                    className="h-10 px-3"
                                >
                                    <Send className="w-4 h-4" />
                                </Button>
                            </form>

                            <p className="mt-2 text-xs text-muted-foreground">
                                Tekan Enter untuk mengirim, Shift + Enter untuk
                                baris baru
                            </p>
                        </div>
                    </>
                ) : (
                    <div className="flex items-center justify-center flex-1">
                        <div className="text-center">
                            <Users className="w-12 h-12 mx-auto mb-4 text-muted-foreground" />
                            <h3 className="mb-2 text-lg font-medium text-foreground">
                                Pilih Grup Chat
                            </h3>
                            <p className="text-muted-foreground">
                                Pilih grup chat dari sidebar untuk mulai
                                percakapan
                            </p>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default DetailChatGroup;
