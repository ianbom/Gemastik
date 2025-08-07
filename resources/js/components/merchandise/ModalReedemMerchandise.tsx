import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';
import { router } from '@inertiajs/react';
import {
    AlertCircle,
    CheckCircle,
    MapPin,
    RefreshCcw,
    ShoppingBag,
    Star,
} from 'lucide-react';
import React, { useState } from 'react';
import { toast } from 'sonner'; // Optional: for better notifications

interface Merchandise {
    id: number;
    name: string;
    description: string;
    image_url: string;
    points_cost: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface ModalRedeemMerchandiseProps {
    isOpen: boolean;
    onClose: () => void;
    merchandise: Merchandise | null;
    userPoints: number;
    onSuccess?: () => void; // Optional callback for success
}

const ModalRedeemMerchandise: React.FC<ModalRedeemMerchandiseProps> = ({
    isOpen,
    onClose,
    merchandise,
    userPoints,
    onSuccess,
}) => {
    const [address, setAddress] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);
    const [error, setError] = useState<string>('');

    // Calculate remaining points after exchange
    const remainingPoints = merchandise
        ? userPoints - merchandise.points_cost
        : userPoints;

    // Check if user has enough points
    const hasEnoughPoints = merchandise
        ? userPoints >= merchandise.points_cost
        : false;

    const handleClose = () => {
        if (!isProcessing) {
            setAddress('');
            setIsProcessing(false);
            setError('');
            onClose();
        }
    };

    const handleConfirm = async () => {
        if (!merchandise || !address.trim() || !hasEnoughPoints) return;

        setIsProcessing(true);
        setError('');

        try {
            // Prepare data according to your backend schema
            const redeemData = {
                merchandise_id: merchandise.id,
                points_spent: merchandise.points_cost,
                shipping_address: address.trim(),
            };

            // Send request to Laravel backend
            await new Promise((resolve, reject) => {
                router.post('merchandise', redeemData, {
                    onSuccess: (page) => {
                        // Show success message
                        if (typeof toast !== 'undefined') {
                            toast.success(
                                'Redeem berhasil! Merchandise akan segera diproses.',
                            );
                        }

                        // Call success callback if provided
                        if (onSuccess) {
                            onSuccess();
                        }

                        resolve(page);
                        handleClose();
                    },
                    onError: (errors) => {
                        console.error('Redeem failed:', errors);

                        // Handle different types of errors
                        if (typeof errors === 'string') {
                            setError(errors);
                        } else if (errors.err) {
                            setError(errors.err);
                        } else if (errors.message) {
                            setError(errors.message);
                        } else {
                            setError(
                                'Terjadi kesalahan saat melakukan redeem. Silakan coba lagi.',
                            );
                        }

                        reject(errors);
                    },
                    onFinish: () => {
                        setIsProcessing(false);
                    },
                });
            });
        } catch (error) {
            console.error('Exchange failed:', error);
            setError(
                'Terjadi kesalahan saat melakukan redeem. Silakan coba lagi.',
            );
            setIsProcessing(false);
        }
    };

    // Validation for address
    const isAddressValid = address.trim().length >= 10; // Minimum 10 characters for address

    return (
        <Dialog open={isOpen} onOpenChange={handleClose}>
            <DialogContent className="max-w-md">
                <DialogHeader>
                    <DialogTitle className="flex items-center gap-2">
                        <ShoppingBag className="w-5 h-5 text-sky-600" />
                        Tukar Merchandise
                    </DialogTitle>
                    <DialogDescription>
                        Masukkan alamat pengiriman untuk melakukan penukaran
                        merchandise
                    </DialogDescription>
                </DialogHeader>

                {merchandise && (
                    <div className="space-y-4">
                        {/* Error Message */}
                        {error && (
                            <div className="p-3 border border-red-200 rounded-lg bg-red-50">
                                <div className="flex items-start gap-2">
                                    <AlertCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-red-600" />
                                    <p className="text-sm text-red-700">
                                        {error}
                                    </p>
                                </div>
                            </div>
                        )}

                        {/* Merchandise Details */}
                        <div className="p-4 border rounded-lg">
                            <div className="flex gap-4">
                                <img
                                    src={`/storage/${merchandise.image_url}`}
                                    alt={merchandise.name}
                                    className="object-cover w-20 h-20 rounded-lg"
                                />
                                <div className="flex-1">
                                    <h4 className="font-semibold text-gray-900">
                                        {merchandise.name}
                                    </h4>
                                    <p className="text-sm text-gray-600 line-clamp-2">
                                        {merchandise.description}
                                    </p>
                                    <div className="flex items-center mt-2 text-yellow-600">
                                        <Star
                                            size={14}
                                            className="mr-1 fill-current"
                                        />
                                        <span className="font-semibold">
                                            {merchandise.points_cost.toLocaleString(
                                                'id-ID',
                                            )}{' '}
                                            Poin
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Points Information */}
                        <div className="p-4 rounded-lg bg-gray-50">
                            <h5 className="mb-3 font-medium text-gray-900">
                                Informasi Poin
                            </h5>
                            <div className="space-y-2 text-sm">
                                <div className="flex justify-between">
                                    <span className="text-gray-600">
                                        Poin Saat Ini:
                                    </span>
                                    <span className="font-semibold text-sky-600">
                                        {userPoints.toLocaleString('id-ID')}{' '}
                                        Poin
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-gray-600">
                                        Biaya Penukaran:
                                    </span>
                                    <span className="font-semibold text-red-600">
                                        -
                                        {merchandise.points_cost.toLocaleString(
                                            'id-ID',
                                        )}{' '}
                                        Poin
                                    </span>
                                </div>
                                <hr className="my-2" />
                                <div className="flex justify-between">
                                    <span className="text-gray-600">
                                        Sisa Poin:
                                    </span>
                                    <span
                                        className={`font-semibold ${remainingPoints >= 0 ? 'text-sky-600' : 'text-red-600'}`}
                                    >
                                        {remainingPoints.toLocaleString(
                                            'id-ID',
                                        )}{' '}
                                        Poin
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* Address Input */}
                        <div className="space-y-2">
                            <label className="flex items-center text-sm font-medium text-gray-700">
                                <MapPin className="w-4 h-4 mr-1" />
                                Alamat Pengiriman *
                            </label>
                            <Textarea
                                placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kelurahan ABC, Kecamatan XYZ, Kota DEF, Jawa Timur 12345"
                                value={address}
                                onChange={(e) => {
                                    setAddress(e.target.value);
                                    setError(''); // Clear error when user types
                                }}
                                rows={4}
                                className="resize-none"
                                disabled={isProcessing}
                            />
                            <div className="flex justify-between">
                                <p className="text-xs text-gray-500">
                                    Pastikan alamat yang Anda masukkan sudah
                                    benar dan lengkap
                                </p>
                                <p
                                    className={`text-xs ${address.length >= 10 ? 'text-green-600' : 'text-gray-400'}`}
                                >
                                    {address.length}/10 minimum
                                </p>
                            </div>
                        </div>

                        {/* Insufficient Points Warning */}
                        {!hasEnoughPoints && (
                            <div className="p-3 border border-red-200 rounded-lg bg-red-50">
                                <div className="flex items-start gap-2">
                                    <AlertCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-red-600" />
                                    <p className="text-sm text-red-700">
                                        Poin Anda tidak mencukupi untuk
                                        melakukan penukaran ini. Anda
                                        membutuhkan{' '}
                                        {(
                                            merchandise.points_cost - userPoints
                                        ).toLocaleString('id-ID')}{' '}
                                        poin lagi.
                                    </p>
                                </div>
                            </div>
                        )}

                        {/* Success Conditions */}
                        {hasEnoughPoints && isAddressValid && (
                            <div className="p-3 border border-green-200 rounded-lg bg-green-50">
                                <div className="flex items-start gap-2">
                                    <CheckCircle className="mt-0.5 h-4 w-4 flex-shrink-0 text-green-600" />
                                    <p className="text-sm text-green-700">
                                        Siap untuk ditukar! Pastikan alamat
                                        sudah benar sebelum melanjutkan.
                                    </p>
                                </div>
                            </div>
                        )}
                    </div>
                )}

                <DialogFooter className="gap-2">
                    <Button
                        variant="outline"
                        onClick={handleClose}
                        disabled={isProcessing}
                    >
                        Batal
                    </Button>
                    <Button
                        onClick={handleConfirm}
                        disabled={
                            !isAddressValid || !hasEnoughPoints || isProcessing
                        }
                        className="bg-sky-600 hover:bg-sky-700 disabled:bg-gray-400"
                    >
                        {isProcessing ? (
                            <>
                                <RefreshCcw className="w-4 h-4 mr-2 animate-spin" />
                                Memproses...
                            </>
                        ) : (
                            <>
                                <ShoppingBag className="w-4 h-4 mr-2" />
                                Konfirmasi Tukar
                            </>
                        )}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
};

export default ModalRedeemMerchandise;
