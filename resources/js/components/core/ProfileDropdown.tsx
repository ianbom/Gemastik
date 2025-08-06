import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { User } from '@/types/user/interface';

interface Props {
    user?: User;
    menuItems: React.ReactNode;
}

const ProfileMenuDropdown = ({ user, menuItems }: Props) => {
    const userName = user?.name?.trim() || 'Guest';
    const userInitial = userName ? userName.charAt(0).toUpperCase() : 'G';
    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <button className="flex h-10 w-10 items-center justify-center rounded-full transition-shadow hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <Avatar className="h-10 w-10">
                        <AvatarImage
                            src={
                                user?.profile_url
                                    ? `/storage/${user.profile_url}`
                                    : undefined
                            }
                            alt={userName}
                        />
                        <AvatarFallback className="bg-emerald-100 text-sm font-semibold text-emerald-700">
                            {userInitial}
                        </AvatarFallback>
                    </Avatar>
                </button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
                {menuItems}
            </DropdownMenuContent>
        </DropdownMenu>
    );
};

export default ProfileMenuDropdown;
