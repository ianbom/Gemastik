

export interface ChatUser {
  id: number;
  name: string;
  profile_url: string | null;
}


export interface ChatMessage {
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


export interface ChatGroup {
  id: number;
  mission_id: number;
  mission_title: string;
  mission_thumbnail: string | null;
}

// The main props for the page component
export interface GroupChatPageProps {
  chatGroups: ChatGroup[];
  initialMessages: ChatMessage[];
  activeGroupId: number;
}
