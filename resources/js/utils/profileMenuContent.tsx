import {
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { User } from '@/types/user/interface';
import {
    FileText,
    Heart,
    Lightbulb,
    LogOut,
    ShoppingBag,
    Star,
    Target,
    User as UserIcon,
} from 'lucide-react';

interface Props {
    user?: User;
    onProfileClick: () => void;
    onLogoutClick: () => void;
}

export function getProfileMenuContent({
    user,
    onProfileClick,
    onLogoutClick,
}: Props): React.ReactNode {
    return (
        <>
            <div className="px-3 py-2.5">
                <p className="text-sm font-semibold text-gray-900">
                    {user?.name || 'Guest'}
                </p>
                <p className="mt-0.5 text-xs text-gray-500">{user?.email}</p>

                <div className="mt-2 inline-flex items-center gap-1.5 rounded-md bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">
                    <Star className="h-4 w-4 text-yellow-500" />
                    {user?.points_balance || 0} Poin
                </div>
            </div>
            <DropdownMenuSeparator />
            <DropdownMenuLabel className="px-3 py-1.5 text-xs font-medium uppercase tracking-wider text-gray-500">
                Profil
            </DropdownMenuLabel>
            <DropdownMenuItem
                onClick={onProfileClick}
                className="flex cursor-pointer items-center gap-2"
            >
                <UserIcon className="h-4 w-4" />
                <span>Profil Saya</span>
            </DropdownMenuItem>
            <DropdownMenuLabel className="px-3 py-1.5 text-xs font-medium uppercase tracking-wider text-gray-500">
                RIWAYAT
            </DropdownMenuLabel>
            <DropdownMenuItem
                onClick={() => (location.href = `/my-report`)}
                className="flex cursor-pointer items-center gap-2"
            >
                <FileText className="h-4 w-4" />
                <span>Laporan Saya</span>
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => (location.href = `/my-mission`)}
                className="flex cursor-pointer items-center gap-2"
            >
                <Target className="h-4 w-4" />
                <span>Misi yang Diikuti</span>
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => (location.href = `/my-donations`)}
                className="flex cursor-pointer items-center gap-2"
            >
                <Heart className="h-4 w-4" />
                <span>Donasi Saya</span>
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => (location.href = `/my-merchandise`)}
                className="flex cursor-pointer items-center gap-2"
            >
                <ShoppingBag className="h-4 w-4" />
                <span>Merchandise Saya</span>
            </DropdownMenuItem>
            <DropdownMenuItem
                onClick={() => (location.href = `/my-quiz-attempt`)}
                className="flex cursor-pointer items-center gap-2"
            >
                <Lightbulb className="h-4 w-4" />
                <span>Kuis yang Dikerjakan</span>
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem
                onClick={onLogoutClick}
                className="flex cursor-pointer items-center gap-2 text-red-600 focus:bg-red-50 focus:text-red-600"
            >
                <LogOut className="h-4 w-4" />
                <span>Logout</span>
            </DropdownMenuItem>
        </>
    );
}
