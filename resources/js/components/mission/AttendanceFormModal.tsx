'use client';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ScrollArea } from '@/components/ui/scroll-area';
import { router as Inertia } from '@inertiajs/react';
import { useEffect, useState } from 'react';

interface AttendanceFormModalProps {
    open: boolean;
    onClose: () => void;
    teamLeader: string;
    members: { id: number; name: string; status: string }[];
    missionId: number;
}

export default function AttendanceFormModal({
    open,
    onClose,
    teamLeader,
    members,
    missionId,
}: AttendanceFormModalProps) {
    const [attendance, setAttendance] = useState<{ [id: number]: boolean }>({});
    useEffect(() => {
        if (open && members.length > 0) {
            const initialAttendance: Record<number, boolean> = {};
            members.forEach((member) => {
                console.log(
                    `✅ ${member.name} (${member.id}): ${member.status}`,
                );
                if (member.status === 'attended') {
                    initialAttendance[member.id] = true;
                }
            });
            setAttendance(initialAttendance);
        }
    }, [open, members]);

    const handleCheckboxChange = (id: number) => {
        setAttendance((prev) => ({
            ...prev,
            [id]: !prev[id],
        }));
    };

    const totalPresent = Object.values(attendance).filter(Boolean).length;

    const handleSave = () => {
        const hadirIds = Object.entries(attendance)
            .filter(([, value]) => value)
            .map(([id]) => parseInt(id));

        Inertia.post(
            '/attendance-members',
            {
                user_ids: hadirIds,
                mission_id: missionId,
            },
            {
                onSuccess: () => {
                    onClose();
                },
                onError: (errors) => {
                    console.error('Presensi gagal:', errors);
                },
            },
        );
    };

    return (
        <Dialog open={open} onOpenChange={onClose}>
            <DialogContent className="max-w-lg">
                <DialogHeader>
                    <DialogTitle>Presensi Kehadiran Anggota</DialogTitle>
                </DialogHeader>

                {/* Ketua Tim */}
                <div className="mb-4 space-y-1">
                    <Label htmlFor="leader">Ketua Tim</Label>
                    <Input
                        id="leader"
                        value={teamLeader}
                        disabled
                        className="border-gray-400 bg-gray-100 text-gray-900"
                    />
                </div>

                {/* Daftar Anggota */}
                <Label>Anggota Tim</Label>
                <ScrollArea className="mb-4 h-60 pr-2">
                    {members.length > 0 ? (
                        <div className="space-y-4">
                            {members.map((member) => (
                                <div
                                    key={member.id}
                                    className="flex items-center gap-2"
                                >
                                    <Checkbox
                                        id={`member-${member.id}`}
                                        checked={attendance[member.id] || false}
                                        onCheckedChange={() =>
                                            handleCheckboxChange(member.id)
                                        }
                                    />
                                    <Label htmlFor={`member-${member.id}`}>
                                        {member.name}
                                    </Label>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <p className="text-sm italic text-gray-500">
                            - Tidak ada anggota -
                        </p>
                    )}
                </ScrollArea>

                {/* Info Jumlah Hadir */}
                <div className="mb-4 text-sm text-gray-600">
                    Jumlah Anggota Volunteer:{' '}
                    <span className="font-semibold">{members.length}</span>
                    <br />
                    Jumlah Anggota Hadir:{' '}
                    <span className="font-semibold">{totalPresent}</span>
                </div>

                {/* Tombol Aksi */}
                <div className="flex justify-end gap-2">
                    <Button variant="outline" onClick={onClose}>
                        Batal
                    </Button>
                    <Button
                        className="bg-emerald-600 hover:bg-emerald-700"
                        onClick={handleSave}
                    >
                        Simpan
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    );
}
