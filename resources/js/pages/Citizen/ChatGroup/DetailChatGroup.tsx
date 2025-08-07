import DetailChatGroup from '@/components/chatgroup/DetailChatGroup';
import CitizenLayout from '@/components/layouts/CitizenLayout';
import { PageProps } from '@/types';
import { usePage } from '@inertiajs/react';

interface Mission {
    id: number;
    title: string;
    thumbnail: string | null;
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

interface DetailChatGroupPageProps {
    messages: ChatMessage[];
    allGroups: ChatGroup[];
    activeGroupId?: number;
    [key: string]: unknown;
}

const DetailChatGroupRoute = () => {
    const { props } = usePage<PageProps<DetailChatGroupPageProps>>();
    const { messages, allGroups } = props;
    const activeGroupId = props.activeGroupId;

    console.log('messages', messages);
    console.log('allGroups', allGroups);
    console.log('activeGroupId', activeGroupId);

    return (
        <CitizenLayout currentPage="chat">
            <DetailChatGroup
                messages={messages}
                allGroups={allGroups}
                activeGroupId={activeGroupId}
                currentUserId={props.auth?.user?.id ?? 0}
            />
        </CitizenLayout>
    );
};

export default DetailChatGroupRoute;
