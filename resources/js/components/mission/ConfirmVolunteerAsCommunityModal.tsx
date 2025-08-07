('use client');

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
} from '@/components/ui/dialog';

interface ConfirmVolunteerAsCommunityModalProps {
    open: boolean;
    onClose: () => void;
    onConfirm: () => void;
}

export default function ConfirmVolunteerAsCommunityModal({
    open,
    onClose,
    onConfirm,
}: ConfirmVolunteerAsCommunityModalProps) {
    return (
        <Dialog open={open} onOpenChange={onClose}>
            <DialogContent>
                <DialogHeader>
                    <h2 className="text-lg font-semibold text-gray-800">
                        Konfirmasi Pendaftaran
                    </h2>
                </DialogHeader>
                <div className="py-4 text-gray-700">
                    <p>
                        Apakah Anda yakin ingin mendaftar sebagai{' '}
                        <strong>Komunitas</strong>? Jika Anda terpilih, Anda
                        akan otomatis menjadi ketua tim.
                    </p>
                </div>
                <DialogFooter>
                    <Button variant="outline" onClick={onClose}>
                        Batal
                    </Button>
                    <Button
                        onClick={onConfirm}
                        className="bg-sky-600 hover:bg-sky-700"
                    >
                        Ya, Saya Yakin
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
