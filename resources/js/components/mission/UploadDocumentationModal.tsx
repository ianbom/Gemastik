import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { router as Inertia } from '@inertiajs/react';
import { FileImage, Upload, X } from 'lucide-react';
import { useState } from 'react';

interface FileWithPreview {
    file: File;
    preview: string;
    id: string;
    type: 'image' | 'video';
}

export default function UploadDocumentationModal({
    open,
    onClose,
    missionId,
}: {
    open: boolean;
    onClose: () => void;
    missionId: number;
}) {
    const [files, setFiles] = useState<FileWithPreview[]>([]);
    const [content, setContent] = useState('');
    const [isDragging, setIsDragging] = useState(false);
    const [uploading, setUploading] = useState(false);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const selectedFiles = e.target.files;
        if (selectedFiles) {
            processFiles(Array.from(selectedFiles));
        }
    };

    const processFiles = (fileList: File[]) => {
        const newFiles: FileWithPreview[] = fileList.map((file) => {
            const mimeType = file.type;
            const isVideo = mimeType.startsWith('video/');

            return {
                file,
                preview: URL.createObjectURL(file),
                id: Math.random().toString(36).substr(2, 9),
                type: isVideo ? 'video' : 'image',
            };
        });

        setFiles((prev) => [...prev, ...newFiles]);
    };

    const removeFile = (id: string) => {
        setFiles((prev) => {
            const fileToRemove = prev.find((f) => f.id === id);
            if (fileToRemove) {
                URL.revokeObjectURL(fileToRemove.preview);
            }
            return prev.filter((f) => f.id !== id);
        });
    };

    const handleDragOver = (e: React.DragEvent) => {
        e.preventDefault();
        setIsDragging(true);
    };

    const handleDragLeave = (e: React.DragEvent) => {
        e.preventDefault();
        setIsDragging(false);
    };

    const handleDrop = (e: React.DragEvent) => {
        e.preventDefault();
        setIsDragging(false);

        const droppedFiles = Array.from(e.dataTransfer.files).filter((file) =>
            file.type.startsWith('image/'),
        );

        if (droppedFiles.length > 0) {
            processFiles(droppedFiles);
        }
    };

    const handleUpload = async () => {
        if (files.length === 0) {
            alert('Pilih setidaknya satu gambar untuk diupload');
            return;
        }

        setUploading(true);

        const formData = new FormData();
        files.forEach((fileObj) => {
            formData.append('media[]', fileObj.file);
        });
        formData.append('mission_id', missionId.toString());
        formData.append('content', content);

        try {
            await Inertia.post(
                '/mission/media-documentation/upload',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onSuccess: () => {
                        // Clean up object URLs
                        files.forEach((fileObj) => {
                            URL.revokeObjectURL(fileObj.preview);
                        });
                        setFiles([]);
                        setContent('');
                        onClose();
                    },
                    onError: (errors) => {
                        console.error('Upload failed:', errors);
                    },
                    onFinish: () => {
                        setUploading(false);
                    },
                },
            );
        } catch (error) {
            console.error('Upload error:', error);
            setUploading(false);
        }
    };

    const handleClose = () => {
        // Clean up object URLs when closing
        files.forEach((fileObj) => {
            URL.revokeObjectURL(fileObj.preview);
        });
        setFiles([]);
        setContent('');
        onClose();
    };

    return (
        <Dialog open={open} onOpenChange={handleClose}>
            <DialogContent className="max-h-[80vh] max-w-2xl overflow-y-auto">
                <DialogHeader>
                    <DialogTitle className="flex items-center gap-2">
                        <FileImage className="h-5 w-5" />
                        Upload Dokumentasi Misi
                    </DialogTitle>
                </DialogHeader>

                <div className="space-y-4">
                    {/* Description Field */}
                    <div>
                        <label className="mb-2 block text-sm font-medium text-gray-700">
                            Deskripsi Dokumentasi
                        </label>
                        <textarea
                            value={content}
                            onChange={(e) => setContent(e.target.value)}
                            placeholder="Jelaskan tentang dokumentasi ini..."
                            className="w-full resize-none rounded-md border border-gray-300 p-3 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                            rows={3}
                        />
                    </div>

                    {/* File Upload Area */}
                    <div>
                        <label className="mb-2 block text-sm font-medium text-gray-700">
                            Gambar Dokumentasi
                        </label>

                        <div
                            className={`rounded-lg border-2 border-dashed p-6 text-center transition-colors ${
                                isDragging
                                    ? 'border-blue-400 bg-blue-50'
                                    : 'border-gray-300 hover:border-gray-400'
                            }`}
                            onDragOver={handleDragOver}
                            onDragLeave={handleDragLeave}
                            onDrop={handleDrop}
                        >
                            <Upload className="mx-auto mb-4 h-12 w-12 text-gray-400" />
                            <p className="mb-2 text-gray-600">
                                Drag & drop gambar di sini atau{' '}
                                <label className="cursor-pointer text-blue-600 hover:text-blue-700">
                                    pilih file
                                    <input
                                        type="file"
                                        multiple
                                        accept="image/jpeg,image/png,image/jpg,image/gif,video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv"
                                        onChange={handleFileChange}
                                        className="hidden"
                                    />
                                </label>
                            </p>
                            <p className="text-sm text-gray-500">
                                Maksimal 2MB per file, format: JPG, PNG, GIF
                            </p>
                        </div>
                    </div>

                    {/* Image Previews */}
                    {files.length > 0 && (
                        <div>
                            <h3 className="mb-3 text-sm font-medium text-gray-700">
                                Preview Gambar ({files.length})
                            </h3>
                            <div className="grid grid-cols-2 gap-4 md:grid-cols-3">
                                {files.map((fileObj) => (
                                    <div
                                        key={fileObj.id}
                                        className="group relative"
                                    >
                                        <div className="aspect-square overflow-hidden rounded-lg border bg-gray-100">
                                            {fileObj.type === 'image' ? (
                                                <img
                                                    src={fileObj.preview}
                                                    alt={fileObj.file.name}
                                                    className="h-full w-full object-cover"
                                                />
                                            ) : (
                                                <div className="flex h-full w-full items-center justify-center bg-gray-200">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        className="h-8 w-8 text-gray-600"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={2}
                                                            d="M14.752 11.168l-4.596-2.65A1 1 0 009 9.34v5.32a1 1 0 001.156.982l4.596-1.02A1 1 0 0016 13.68v-3.36a1 1 0 00-1.248-.984z"
                                                        />
                                                    </svg>
                                                </div>
                                            )}
                                        </div>
                                        <button
                                            onClick={() =>
                                                removeFile(fileObj.id)
                                            }
                                            className="absolute right-2 top-2 rounded-full bg-red-500 p-1 text-white opacity-0 transition-opacity hover:bg-red-600 group-hover:opacity-100"
                                        >
                                            <X className="h-4 w-4" />
                                        </button>
                                        <p className="mt-1 truncate text-xs text-gray-500">
                                            {fileObj.file.name}
                                        </p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Action Buttons */}
                    <div className="flex justify-end gap-3 border-t pt-4">
                        <button
                            onClick={handleClose}
                            disabled={uploading}
                            className="rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Batal
                        </button>
                        <button
                            onClick={handleUpload}
                            disabled={uploading || files.length === 0}
                            className="flex items-center gap-2 rounded-md bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {uploading ? (
                                <>
                                    <div className="h-4 w-4 animate-spin rounded-full border-b-2 border-white"></div>
                                    Uploading...
                                </>
                            ) : (
                                <>
                                    <Upload className="h-4 w-4" />
                                    Upload ({files.length})
                                </>
                            )}
                        </button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    );
}
