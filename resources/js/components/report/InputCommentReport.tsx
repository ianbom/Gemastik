import { useForm } from '@inertiajs/react';
import { CirclePlus, FileVideo, Upload, X } from 'lucide-react';
import React, { useEffect, useRef, useState } from 'react';

// Anda bisa memindahkan komponen UI ini ke file terpisah jika perlu
const Card = ({
    children,
    className = '',
}: {
    children: React.ReactNode;
    className?: string;
}) => (
    <div className={`rounded-lg border bg-white shadow-sm ${className}`}>
        {children}
    </div>
);
const CardHeader = ({ children }: { children: React.ReactNode }) => (
    <div className="p-6 pb-4">{children}</div>
);
const CardTitle = ({
    children,
    className = '',
}: {
    children: React.ReactNode;
    className?: string;
}) => <h3 className={`text-lg font-semibold ${className}`}>{children}</h3>;
const CardContent = ({ children }: { children: React.ReactNode }) => (
    <div className="p-6 pt-0">{children}</div>
);
const Textarea = React.forwardRef<
    HTMLTextAreaElement,
    React.TextareaHTMLAttributes<HTMLTextAreaElement>
>(({ className = '', ...props }, ref) => (
    <textarea
        className={`w-full resize-none rounded-md border border-gray-300 px-3 py-2 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500 ${className}`}
        ref={ref}
        {...props}
    />
));
Textarea.displayName = 'Textarea';
type ButtonVariant = 'default' | 'outline';
type ButtonSize = 'sm' | 'md' | 'lg';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    children: React.ReactNode;
    variant?: ButtonVariant;
    size?: ButtonSize;
    className?: string;
}

const Button = ({
    children,
    variant = 'default',
    size = 'md',
    className = '',
    ...props
}: ButtonProps) => {
    const baseClasses =
        'inline-flex items-center justify-center rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none';
    const variants: Record<ButtonVariant, string> = {
        default: 'bg-sky-600 text-white hover:bg-sky-700 focus:ring-sky-500',
        outline:
            'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-sky-500',
    };
    const sizes: Record<ButtonSize, string> = {
        sm: 'px-3 py-1.5 text-sm',
        md: 'px-4 py-2 text-sm',
        lg: 'px-6 py-3 text-base',
    };

    return (
        <button
            className={`${baseClasses} ${variants[variant]} ${sizes[size]} ${className}`}
            {...props}
        >
            {children}
        </button>
    );
};
// Akhir dari komponen UI

interface CommentUploadCardProps {
    reportId: number | string;
}

const CommentUploadCard = ({ reportId }: CommentUploadCardProps) => {
    // 1. Gunakan useForm untuk state management yang terintegrasi dengan Inertia
    const { data, setData, post, processing, errors, reset } = useForm<{
        comment: string;
        media: File | null;
        report_id: number | string;
    }>({
        comment: '',
        media: null,
        report_id: reportId,
    });

    const [preview, setPreview] = useState<string | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    // Fungsi untuk membuat preview saat file dipilih
    useEffect(() => {
        if (data.media) {
            if (data.media.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onloadend = () => setPreview(reader.result as string);
                reader.readAsDataURL(data.media);
            } else {
                setPreview(null); // Tidak ada preview untuk video, hanya tampilkan info
            }
        } else {
            setPreview(null);
        }
    }, [data.media]);

    const handleFileSelect = (e: React.ChangeEvent<HTMLInputElement>) => {
        setData('media', e.target.files?.[0] || null);
    };

    const removeFile = () => {
        setData('media', null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('comments.store'), {
            forceFormData: true,
            onSuccess: () => {
                reset();
            },
            preserveScroll: true,
        });
    };

    const formatFileSize = (bytes: number) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    return (
        <Card>
            <CardHeader>
                <CardTitle className="flex items-center">
                    <CirclePlus size={20} className="mr-2" />
                    Tambah Komentar
                </CardTitle>
            </CardHeader>
            <CardContent>
                <form onSubmit={handleSubmit}>
                    <Textarea
                        placeholder="Tulis komentar Anda..."
                        value={data.comment}
                        onChange={(e) => setData('comment', e.target.value)}
                        rows={3}
                        className="mb-3"
                    />
                    {/* 3. Tampilkan error validasi dari Laravel */}
                    {errors.comment && (
                        <p className="mt-1 text-sm text-red-500">
                            {errors.comment}
                        </p>
                    )}
                    {errors.media && (
                        <p className="mt-1 text-sm text-red-500">
                            {errors.media}
                        </p>
                    )}

                    {/* File Preview Section */}
                    {data.media && (
                        <div className="mt-2 mb-4">
                            {preview ? (
                                // Preview untuk gambar
                                <div className="relative inline-block">
                                    <img
                                        src={preview}
                                        alt="Preview"
                                        className="object-contain h-auto max-w-full border border-gray-200 rounded-lg max-h-48"
                                    />
                                    <button
                                        type="button"
                                        onClick={removeFile}
                                        className="absolute p-1 text-white bg-red-500 rounded-full -right-2 -top-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    >
                                        <X size={14} />
                                    </button>
                                </div>
                            ) : (
                                // Info untuk video atau file lain
                                <div className="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-gray-50">
                                    <div className="flex items-center min-w-0 space-x-3">
                                        <FileVideo
                                            size={24}
                                            className="flex-shrink-0 text-blue-500"
                                        />
                                        <div className="min-w-0">
                                            <p className="text-sm font-medium text-gray-900 truncate">
                                                {data.media.name}
                                            </p>
                                            <p className="text-sm text-gray-500">
                                                {formatFileSize(
                                                    data.media.size,
                                                )}
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        onClick={removeFile}
                                        className="flex-shrink-0 p-1 text-red-500 rounded hover:text-red-700 focus:outline-none"
                                    >
                                        <X size={16} />
                                    </button>
                                </div>
                            )}
                        </div>
                    )}

                    <div className="flex items-center justify-between mt-3">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => fileInputRef.current?.click()}
                        >
                            <Upload size={16} className="mr-2" />
                            Lampirkan File
                        </Button>
                        <input
                            type="file"
                            ref={fileInputRef}
                            onChange={handleFileSelect}
                            accept="image/*,video/*"
                            className="hidden"
                        />
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Mengirim...' : 'Kirim Komentar'}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    );
};

export default CommentUploadCard;
