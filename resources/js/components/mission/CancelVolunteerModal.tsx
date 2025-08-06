'use client';

import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
} from '@/components/ui/dialog';

interface CancelVolunteerModalProps {
    open: boolean;
    onClose: () => void;
    onConfirm: () => void;
    is_leader: boolean;
}

export default function CancelVolunteerModal({
    open,
    onClose,
    onConfirm,
    is_leader,
}: CancelVolunteerModalProps) {
    return (
        <Dialog open={open} onOpenChange={onClose}>
            <DialogContent>
                <DialogHeader>
                    <h2 className="text-lg font-semibold text-gray-800">
                        Konfirmasi Pendaftaran
                    </h2>
                </DialogHeader>
                <div className="py-4 text-gray-700">
                    {is_leader ? (
                        <p>
                            Apakah Anda yakin ingin batal mendaftar sebagai{' '}
                            <strong>Ketua Tim</strong>?
                        </p>
                    ) : (
                        <p>
                            Apakah Anda yakin ingin batal mendaftar sebagai{' '}
                            <strong>Anggota Tim</strong>?
                        </p>
                    )}
                </div>
                <DialogFooter>
                    <Button variant="outline" onClick={onClose}>
                        Batal
                    </Button>
                    <Button
                        onClick={onConfirm} // ini yang penting
                        className="bg-emerald-600 hover:bg-emerald-700"
                    >
                        Ya, Saya Yakin
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
