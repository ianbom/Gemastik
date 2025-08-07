import CitizenLayout from '@/components/layouts/CitizenLayout';
import GroupChatPage from '@/components/chatgroup/ChatGroupPage';
import { PageProps } from '@/types';
import { usePage } from '@inertiajs/react';

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

interface ChatGroupPageRouteProps {
    chatGroups: ChatGroup[];
    [key: string]: unknown;
}

const ChatGroupPageRoute = () => {
    const { props } = usePage<PageProps<ChatGroupPageRouteProps>>();
    const { chatGroups } = props;

    console.log('chatGroups', chatGroups);

    return (
        <CitizenLayout currentPage="chat">
            <GroupChatPage chatGroups={chatGroups} />
        </CitizenLayout>
    );
};

export default ChatGroupPageRoute;
