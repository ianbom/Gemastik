import { useState } from 'react';

interface ImageWithPopupProps {
    src: string;
    alt: string;
    className?: string;
}

export default function ImageWithPopup({
    src,
    alt,
    className,
}: ImageWithPopupProps) {
    const [open, setOpen] = useState(false);

    const handleOverlayClick = (e: React.MouseEvent) => {
        if (
            e.target instanceof HTMLElement &&
            e.target.id === 'modal-overlay'
        ) {
            setOpen(false);
        }
    };

    return (
        <>
            <div className="aspect-[4/5] w-full overflow-hidden rounded">
                <img
                    src={src}
                    alt={alt}
                    onClick={() => setOpen(true)}
                    // className="h-full w-full cursor-pointer object-cover transition-transform duration-300 hover:scale-105"
                    className={`h-full w-full cursor-pointer object-cover transition-transform duration-300 hover:scale-105 ${className ?? ''}`}
                />
            </div>
            {open && (
                <div
                    id="modal-overlay"
                    onClick={handleOverlayClick}
                    className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 px-4"
                >
                    <div className="relative flex max-h-[90vh] max-w-[90vw] flex-col items-center overflow-auto rounded bg-white p-4">
                        <button
                            onClick={() => setOpen(false)}
                            className="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-2xl font-bold text-white hover:text-black"
                            aria-label="Tutup modal"
                        >
                            ✕
                        </button>
                        <img
                            src={src}
                            alt={alt}
                            className="mb-4 h-auto max-w-full object-contain"
                        />
                        <a
                            href={src}
                            download
                            className="rounded bg-gray-800 px-4 py-2 text-sm text-white shadow transition hover:bg-gray-700"
                        >
                            Download
                        </a>
                    </div>
                </div>
            )}
        </>
    );
}
