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
                <button className="flex items-center justify-center w-10 h-10 transition-shadow rounded-full hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <Avatar className="w-10 h-10">
                        <AvatarImage
                            src={
                                user?.profile_url
                                    ? `/storage/${user.profile_url}`
                                    : undefined
                            }
                            alt={userName}
                        />
                        <AvatarFallback className="text-sm font-semibold bg-sky-100 text-sky-700">
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
