import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Bot, Send, Trash2, X } from 'lucide-react';
import type React from 'react';
import { useCallback, useEffect, useRef, useState } from 'react';

// Definisikan tipe untuk pesan
interface Message {
    id: string;
    content: string;
    role: 'user' | 'assistant' | 'error';
    timestamp: Date;
}

// Kunci untuk menyimpan data di localStorage
const CHAT_STORAGE_KEY = 'sobatbumi-chat-history';
const FAB_POSITION_KEY = 'sobatbumi-fab-position';

// Konstanta untuk pesan awal agar bisa digunakan kembali
const initialMessages: Message[] = [
    {
        id: '1',
        content:
            'Halo! Saya Asisten KawanBumi. Ada yang bisa saya bantu terkait isu lingkungan?',
        role: 'assistant',
        timestamp: new Date(),
    },
];

// Helper function untuk mendapatkan CSRF token dari meta tag
const getCsrfToken = (): string => {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') || '' : '';
};

export default function FloatingChat() {
    const [isOpen, setIsOpen] = useState(false);
    const [input, setInput] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    // State & Ref untuk Drag Jendela Chat
    const [position, setPosition] = useState({ x: 0, y: 0 });
    const [isDragging, setIsDragging] = useState(false);
    const chatDragStartRef = useRef<{ x: number; y: number } | null>(null);
    const chatInitialPositionRef = useRef<{ x: number; y: number }>({
        x: 0,
        y: 0,
    });

    // State & Ref untuk Drag Tombol (FAB)
    const [isFabDragging, setIsFabDragging] = useState(false);
    const fabDragStartRef = useRef<{ x: number; y: number } | null>(null);
    const fabInitialPositionRef = useRef<{ x: number; y: number }>({
        x: 0,
        y: 0,
    });
    const fabWasDragged = useRef(false);

    // Inisialisasi posisi FAB dari localStorage
    const [fabPosition, setFabPosition] = useState(() => {
        try {
            const storedPosition = localStorage.getItem(FAB_POSITION_KEY);
            return storedPosition ? JSON.parse(storedPosition) : { x: 0, y: 0 };
        } catch (error) {
            return { x: 0, y: 0 };
        }
    });
    const [messages, setMessages] = useState<Message[]>(() => {
        try {
            const storedMessages = localStorage.getItem(CHAT_STORAGE_KEY);
            if (storedMessages) {
                const parsedMessages = JSON.parse(storedMessages) as (Omit<
                    Message,
                    'timestamp'
                > & { timestamp: string })[];

                return parsedMessages.map((msg) => ({
                    ...msg,
                    timestamp: new Date(msg.timestamp),
                }));
            }
        } catch (error) {
            console.error('Gagal memuat pesan dari localStorage:', error);
            localStorage.removeItem(CHAT_STORAGE_KEY);
        }
        return initialMessages;
    });

    const messagesEndRef = useRef<HTMLDivElement>(null);
    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
    };

    // Simpan riwayat chat ke localStorage
    useEffect(() => {
        try {
            if (messages.length > 1) {
                localStorage.setItem(
                    CHAT_STORAGE_KEY,
                    JSON.stringify(messages),
                );
            } else {
                localStorage.removeItem(CHAT_STORAGE_KEY);
            }
        } catch (error) {
            console.error('Gagal menyimpan pesan ke localStorage:', error);
        }
        scrollToBottom();
    }, [messages]);

    // Simpan posisi FAB ke localStorage
    useEffect(() => {
        try {
            localStorage.setItem(FAB_POSITION_KEY, JSON.stringify(fabPosition));
        } catch (error) {
            console.error('Gagal menyimpan posisi FAB:', error);
        }
    }, [fabPosition]);

    const toggleChat = () => {
        if (fabWasDragged.current) {
            fabWasDragged.current = false;
            return;
        }
        const newIsOpenState = !isOpen;
        setIsOpen(newIsOpenState);
        if (newIsOpenState) {
            setPosition(fabPosition);
        } else {
            setPosition({ x: 0, y: 0 });
        }
    };

    const handleClearHistory = () => {
        if (
            window.confirm(
                'Apakah Anda yakin ingin menghapus semua riwayat percakapan?',
            )
        ) {
            localStorage.removeItem(CHAT_STORAGE_KEY);
            setMessages(initialMessages);
        }
    };

    const handleSendMessage = async () => {
        if (!input.trim() || isLoading) return;
        const userMessage: Message = {
            id: Date.now().toString(),
            content: input,
            role: 'user',
            timestamp: new Date(),
        };
        setMessages((prev) => [...prev, userMessage]);
        setInput('');
        setIsLoading(true);
        try {
            const response = await fetch('/api/chatbot/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({ prompt: userMessage.content }),
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(
                    data.error || 'Gagal mendapatkan respons dari server.',
                );
            }
            const aiResponse: Message = {
                id: (Date.now() + 1).toString(),
                content: data.response,
                role: 'assistant',
                timestamp: new Date(),
            };
            setMessages((prev) => [...prev, aiResponse]);
        } catch (error) {
            console.error('Chatbot API Error:', error);
            const errorMessage: Message = {
                id: (Date.now() + 1).toString(),
                content:
                    'Oops! Sepertinya terjadi gangguan. Mohon coba lagi beberapa saat.',
                role: 'error',
                timestamp: new Date(),
            };
            setMessages((prev) => [...prev, errorMessage]);
        } finally {
            setIsLoading(false);
        }
    };

    const handleKeyPress = (e: React.KeyboardEvent) => {
        if (e.key === 'Enter') {
            handleSendMessage();
        }
    };

    // Handlers untuk Drag Jendela Chat
    const handleChatDragStart = useCallback(
        (
            e:
                | React.MouseEvent<HTMLDivElement>
                | React.TouchEvent<HTMLDivElement>,
        ) => {
            e.preventDefault();
            setIsDragging(true);
            chatInitialPositionRef.current = position;
            const clientX = 'touches' in e ? e.touches[0].clientX : e.clientX;
            const clientY = 'touches' in e ? e.touches[0].clientY : e.clientY;
            chatDragStartRef.current = { x: clientX, y: clientY };
        },
        [position],
    );

    const handleChatDragMove = useCallback(
        (e: MouseEvent | TouchEvent) => {
            if (!isDragging || !chatDragStartRef.current) return;
            const clientX = 'touches' in e ? e.touches[0].clientX : e.clientX;
            const clientY = 'touches' in e ? e.touches[0].clientY : e.clientY;
            const dx = clientX - chatDragStartRef.current.x;
            const dy = clientY - chatDragStartRef.current.y;
            setPosition({
                x: chatInitialPositionRef.current.x + dx,
                y: chatInitialPositionRef.current.y + dy,
            });
        },
        [isDragging],
    );

    const handleChatDragEnd = useCallback(() => {
        setIsDragging(false);
        chatDragStartRef.current = null;
    }, []);

    useEffect(() => {
        if (isDragging) {
            window.addEventListener('mousemove', handleChatDragMove);
            window.addEventListener('mouseup', handleChatDragEnd);
            window.addEventListener('touchmove', handleChatDragMove);
            window.addEventListener('touchend', handleChatDragEnd);
        }
        return () => {
            window.removeEventListener('mousemove', handleChatDragMove);
            window.removeEventListener('mouseup', handleChatDragEnd);
            window.removeEventListener('touchmove', handleChatDragMove);
            window.removeEventListener('touchend', handleChatDragEnd);
        };
    }, [isDragging, handleChatDragMove, handleChatDragEnd]);

    // Handlers untuk Drag Tombol (FAB)
    const handleFabDragStart = useCallback(
        (
            e:
                | React.MouseEvent<HTMLButtonElement>
                | React.TouchEvent<HTMLButtonElement>,
        ) => {
            if (isOpen) return;
            e.preventDefault();
            setIsFabDragging(true);
            fabWasDragged.current = false;
            fabInitialPositionRef.current = fabPosition;
            const clientX = 'touches' in e ? e.touches[0].clientX : e.clientX;
            const clientY = 'touches' in e ? e.touches[0].clientY : e.clientY;
            fabDragStartRef.current = { x: clientX, y: clientY };
        },
        [isOpen, fabPosition],
    );

    const handleFabDragMove = useCallback(
        (e: MouseEvent | TouchEvent) => {
            if (!isFabDragging || !fabDragStartRef.current) return;
            fabWasDragged.current = true;
            const clientX = 'touches' in e ? e.touches[0].clientX : e.clientX;
            const clientY = 'touches' in e ? e.touches[0].clientY : e.clientY;
            const dx = clientX - fabDragStartRef.current.x;
            const dy = clientY - fabDragStartRef.current.y;
            setFabPosition({
                x: fabInitialPositionRef.current.x + dx,
                y: fabInitialPositionRef.current.y + dy,
            });
        },
        [isFabDragging],
    );

    const handleFabDragEnd = useCallback(() => {
        setIsFabDragging(false);
        fabDragStartRef.current = null;
    }, []);

    useEffect(() => {
        if (isFabDragging) {
            window.addEventListener('mousemove', handleFabDragMove);
            window.addEventListener('mouseup', handleFabDragEnd);
            window.addEventListener('touchmove', handleFabDragMove);
            window.addEventListener('touchend', handleFabDragEnd);
        }
        return () => {
            window.removeEventListener('mousemove', handleFabDragMove);
            window.removeEventListener('mouseup', handleFabDragEnd);
            window.removeEventListener('touchmove', handleFabDragMove);
            window.removeEventListener('touchend', handleFabDragEnd);
        };
    }, [isFabDragging, handleFabDragMove, handleFabDragEnd]);

    return (
        <>
            {/* Jendela Chat */}
            <div
                className={`fixed bottom-24 right-6 z-40 flex h-[500px] w-80 flex-col rounded-xl border border-gray-200 bg-white shadow-lg sm:w-96 ${
                    isOpen
                        ? 'scale-100 opacity-100'
                        : 'pointer-events-none scale-95 opacity-0'
                } ${isDragging ? 'transition-none' : 'transition-all duration-300 ease-in-out'}`}
                style={{
                    transform: `translate(${position.x}px, ${position.y}px)`,
                }}
            >
                <div
                    onMouseDown={handleChatDragStart}
                    onTouchStart={handleChatDragStart}
                    className={`flex items-center justify-between rounded-t-xl bg-sky-600 p-4 text-white ${
                        isDragging ? 'cursor-grabbing' : 'cursor-grab'
                    }`}
                >
                    <h3 className="text-lg font-semibold select-none">
                        KawanBumi AI Assistant
                    </h3>
                    <div className="flex items-center">
                        <Button
                            variant="ghost"
                            size="sm"
                            onClick={handleClearHistory}
                            onMouseDown={(e) => e.stopPropagation()}
                            onTouchStart={(e) => e.stopPropagation()}
                            className="h-auto p-1 mr-1 text-white hover:bg-sky-700"
                            title="Hapus riwayat"
                        >
                            <Trash2 size={18} />
                        </Button>
                        <Button
                            variant="ghost"
                            size="sm"
                            onClick={toggleChat}
                            onMouseDown={(e) => e.stopPropagation()}
                            onTouchStart={(e) => e.stopPropagation()}
                            className="h-auto p-1 text-white hover:bg-sky-700"
                            title="Tutup chat"
                        >
                            <X size={18} />
                        </Button>
                    </div>
                </div>
                <div className="flex-1 h-full p-4 space-y-4 overflow-y-auto bg-gray-50">
                    {messages.map((message) => (
                        <div
                            key={message.id}
                            className={`flex items-start gap-2.5 ${message.role === 'user' ? 'justify-end' : 'justify-start'}`}
                        >
                            {message.role !== 'user' && (
                                <Bot className="flex-shrink-0 w-6 h-6 text-sky-600" />
                            )}
                            <div
                                className={`max-w-[85%] rounded-lg p-3 text-sm ${
                                    message.role === 'user'
                                        ? 'rounded-br-none bg-sky-600 text-white'
                                        : message.role === 'assistant'
                                          ? 'rounded-bl-none bg-gray-100 text-gray-800'
                                          : 'rounded-bl-none bg-red-100 text-red-800'
                                }`}
                            >
                                {message.content}
                            </div>
                        </div>
                    ))}
                    {isLoading && (
                        <div className="flex items-start justify-start gap-2.5">
                            <Bot className="flex-shrink-0 w-6 h-6 text-sky-600" />
                            <div className="flex items-center space-x-1.5 rounded-lg bg-gray-100 p-3">
                                <span
                                    className="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                    style={{ animationDelay: '0s' }}
                                ></span>
                                <span
                                    className="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                    style={{ animationDelay: '0.2s' }}
                                ></span>
                                <span
                                    className="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                                    style={{ animationDelay: '0.4s' }}
                                ></span>
                            </div>
                        </div>
                    )}
                    <div ref={messagesEndRef} />
                </div>
                <div className="p-4 border-t border-gray-200 rounded-b-xl">
                    <div className="flex items-center space-x-2">
                        <Input
                            value={input}
                            onChange={(e) => setInput(e.target.value)}
                            onKeyPress={handleKeyPress}
                            placeholder="Tanyakan sesuatu..."
                            className="flex-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500"
                            disabled={isLoading}
                        />
                        <Button
                            onClick={handleSendMessage}
                            size="sm"
                            className="p-2 text-white h-9 w-9 bg-sky-600 hover:bg-sky-700"
                            disabled={!input.trim() || isLoading}
                        >
                            <Send size={16} />
                        </Button>
                    </div>
                </div>
            </div>
            <Button
                onClick={toggleChat}
                onMouseDown={handleFabDragStart}
                onTouchStart={handleFabDragStart}
                style={{
                    transform: `translate(${fabPosition.x}px, ${fabPosition.y}px)`,
                }}
                className={`fixed bottom-6 right-6 z-50 flex transform items-center gap-2 rounded-lg bg-sky-600 px-4 py-3 text-white shadow-lg hover:bg-sky-700 ${
                    isOpen ? 'rotate-[360deg] scale-95' : 'rotate-0'
                } ${isFabDragging ? 'cursor-grabbing transition-none' : 'cursor-grab transition-all duration-300 ease-in-out'}`}
            >
                {isOpen ? (
                    <X size={20} />
                ) : (
                    <img
                        src="/assets/images/sobatbumibnw.png"
                        alt="KawanBumi"
                        className="w-6 h-6"
                    />
                )}
                <span className="text-sm font-medium whitespace-nowrap">
                    KawanBumi AI Assistant
                </span>
            </Button>
        </>
    );
}
