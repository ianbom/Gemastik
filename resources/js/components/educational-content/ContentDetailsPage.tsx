import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Content } from '@/types/content';
import { getContentTypeLabel } from '@/utils/contentTypeLabel';
import { getTypeColor, getTypeIcon } from '@/utils/educationColor';
import { formatFullDateTime } from '@/utils/formatDate';
import { ArrowLeft, FileText } from 'lucide-react';
import Badge from '../core/Badge';
import ImageWithPopup from '../core/ImageWithPopup';
import RenderHTML from '../RenderHtml';
interface ContentDetailsPageProps {
    content: Content;
    onBack: () => void;
}
const ContentDetailsPage = ({ content, onBack }: ContentDetailsPageProps) => {
    return (
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="flex items-center justify-between mb-6">
                <Button
                    variant="ghost"
                    onClick={onBack}
                    className="text-gray-600 hover:text-sky-600"
                >
                    <ArrowLeft size={20} className="mr-2" />
                    Kembali ke Daftar Konten
                </Button>
                <div className="space-x-1 text-sm text-gray-500">
                    <span className="cursor-pointer hover:underline">Home</span>
                    <span className="cursor-pointer hover:underline">
                        / Konten
                    </span>{' '}
                    /<span className="font-medium text-gray-700">Detail</span>
                </div>
            </div>
            <div className="space-y-8">
                <Card>
                    <CardContent className="p-6">
                        <div className="flex flex-col items-start justify-between mb-6 md:flex-row">
                            <div className="flex-1">
                                <div className="flex flex-wrap gap-2 mb-3">
                                    <Badge
                                        className={getTypeColor(
                                            content.content_type,
                                        )}
                                    >
                                        <div className="flex items-center gap-1">
                                            {getTypeIcon(content.content_type)}
                                            {getContentTypeLabel(
                                                content.content_type,
                                            )}
                                        </div>
                                    </Badge>
                                </div>

                                <h1 className="mb-4 text-3xl font-bold text-gray-900">
                                    {content.title}
                                </h1>

                                <div className="text-sm text-gray-600">
                                    <div className="flex items-center">
                                        <span>
                                            Oleh: {content.author?.name}
                                        </span>
                                    </div>
                                    <div className="flex items-center">
                                        <span>
                                            Diunggah:{' '}
                                            {formatFullDateTime(
                                                content.created_at,
                                            )}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <CardContent className="px-0 py-4">
                            <div className="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2">
                                {content.media?.map((mediaItem, index) => {
                                    const type = mediaItem.media_type;
                                    if (type === 'document') {
                                        return (
                                            <a
                                                key={index}
                                                href={`/storage/${mediaItem.media_url}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="flex flex-col p-4 space-y-2 transition-colors border rounded-lg hover:bg-gray-50 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0"
                                            >
                                                <FileText className="mx-auto h-14 w-14 shrink-0 text-sky-600 sm:mx-0" />
                                                <div className="w-full text-center sm:w-0 sm:flex-1 sm:text-left">
                                                    <p className="font-medium text-gray-900 truncate">
                                                        {mediaItem.media_url
                                                            ? mediaItem.media_url.replace(
                                                                  /^contents\//,
                                                                  '',
                                                              )
                                                            : `Dokumen ${index + 1}`}
                                                    </p>
                                                    <p className="text-sm text-gray-500">
                                                        Modul PDF
                                                    </p>
                                                </div>
                                            </a>
                                        );
                                    }
                                    return (
                                        <div
                                            key={index}
                                            className="overflow-hidden bg-black rounded-lg aspect-video"
                                        >
                                            {type === 'video' ? (
                                                <video
                                                    controls
                                                    className="object-contain w-full h-full"
                                                    src={`/storage/${mediaItem.media_url}`}
                                                />
                                            ) : (
                                                <ImageWithPopup
                                                    src={`/storage/${mediaItem.media_url}`}
                                                    alt={`Media ${index + 1}`}
                                                    className="object-cover w-full h-full transition-transform duration-300 hover:scale-105"
                                                />
                                            )}
                                        </div>
                                    );
                                })}
                            </div>

                            <div className="prose prose-emerald max-w-none">
                                <div
                                    dangerouslySetInnerHTML={{
                                        __html: content.content,
                                    }}
                                />
                            </div>
                        </CardContent>
                        <div className="leading-relaxed text-gray-700">
                            <h2 className="mb-4 text-2xl font-bold text-gray-900"></h2>
                            <RenderHTML
                                htmlString={content.body}
                                className="prose max-w-none prose-li:text-black prose-table:border prose-th:border prose-td:border"
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
};

export default ContentDetailsPage;
