import { FileText, Newspaper, Play } from 'lucide-react';
export const getTypeIcon = (type: string) => {
    switch (type) {
        case 'artikel':
            return <Newspaper size={16} className="text-purple-600" />;
        case 'video':
            return <Play size={16} className="text-rose-600" />;
        default:
            return <FileText size={16} className="text-sky-600" />;
    }
};
export const getTypeColor = (status: string) => {
    switch (status) {
        case 'artikel':
            return 'bg-purple-100 text-purple-700';
        case 'video':
            return 'bg-rose-100 text-rose-700';
        default:
            return 'bg-sky-100 text-sky-700';
    }
};
