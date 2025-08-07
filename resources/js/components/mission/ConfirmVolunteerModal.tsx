'use client';

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
} from '@/components/ui/dialog';

interface ConfirmVolunteerModalProps {
    open: boolean;
    onClose: () => void;
    onConfirm: () => void;
    role: 'ketua' | 'anggota';
}

export default function ConfirmVolunteerModal({
    open,
    onClose,
    onConfirm,
    role,
}: ConfirmVolunteerModalProps) {
    const isLeader = role === 'ketua';

    return (
        <Dialog open={open} onOpenChange={onClose}>
            <DialogContent>
                <DialogHeader>
                    <h2 className="text-lg font-semibold text-gray-800">
                        Konfirmasi Pendaftaran
                    </h2>
                </DialogHeader>
                <div className="py-4 text-gray-700">
                    {isLeader ? (
                        <p>
                            Apakah Anda yakin ingin mendaftar sebagai{' '}
                            <strong>Ketua Tim</strong>? Jika ketua sudah
                            terpilih, Anda akan otomatis menjadi anggota.
                        </p>
                    ) : (
                        <p>
                            Apakah Anda yakin ingin bergabung sebagai{' '}
                            <strong>Anggota Tim</strong>?
                        </p>
                    )}
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
