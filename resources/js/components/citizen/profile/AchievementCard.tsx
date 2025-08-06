import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { UserBadge } from '@/types/user/user-badge';
import { UserCertificate } from '@/types/user/user-certificate';
import { Award, Calendar, Download } from 'lucide-react';
import { useState } from 'react';

// Updated interface to include badge information
interface Badge {
    id: number;
    title: string;
    description?: string;
    icon_url?: string;
    created_at: string;
    updated_at: string;
}

interface UserBadgeWithBadge extends UserBadge {
    badge: Badge;
}

interface AchievementCardProps {
    myBadges: UserBadgeWithBadge[];
    myCertificates: UserCertificate[];
}

const AchievementCard = ({
    myBadges,
    myCertificates,
}: AchievementCardProps) => {
    const [activeTabAchievements, setActiveTabAchievements] =
        useState('certificates');
    const [certificateSearch, setCertificateSearch] = useState('');
    const [badgeSearch, setBadgeSearch] = useState('');
    const [certificateSort, setCertificateSort] = useState('desc');
    const [badgeSort, setBadgeSort] = useState('desc');

    // Function to get badge icon - now uses icon_url from badge data
    const getBadgeIcon = (badge: Badge) => {
        if (badge.icon_url) {
            return (
                <img
                    src={`/storage/${badge.icon_url}`}
                    alt={badge.title}
                    className="mx-auto h-12 w-12 object-contain"
                    onError={(e) => {
                        // Fallback to emoji if image fails to load
                        const target = e.target as HTMLImageElement;
                        target.style.display = 'none';
                        target.nextElementSibling?.classList.remove('hidden');
                    }}
                />
            );
        }
        // Fallback emoji if no icon_url
        return '🏅';
    };

    // Function to get badge rarity based on badge title or description
    const getBadgeRarity = (badge: Badge) => {
        // You can customize this logic based on your badge system
        const title = badge.title.toLowerCase();
        if (title.includes('legendary') || title.includes('master'))
            return 'Legendary';
        if (title.includes('epic') || title.includes('expert')) return 'Epic';
        if (title.includes('rare') || title.includes('advanced')) return 'Rare';
        return '';
    };

    // Function to get rarity color
    const getRarityColor = (rarity: string) => {
        switch (rarity.toLowerCase()) {
            case 'common':
                return 'border-gray-300';
            case 'rare':
                return 'border-blue-400';
            case 'epic':
                return 'border-purple-400';
            case 'legendary':
                return 'border-yellow-400';
            default:
                return 'border-gray-300';
        }
    };

    // Function to format date
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        });
    };

    // Function to handle certificate download
    const handleDownloadCertificate = (
        certificateUrl: string,
        title: string,
    ) => {
        // Create a temporary link element to trigger download
        const link = document.createElement('a');
        link.href = `/storage/${certificateUrl}`; // Adjust path based on your Laravel storage setup
        link.download = `${title}.pdf`;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    // Filter and sort certificates
    const filteredCertificates = myCertificates
        .filter((cert) =>
            cert.title.toLowerCase().includes(certificateSearch.toLowerCase()),
        )
        .sort((a, b) => {
            if (certificateSort === 'desc') {
                return (
                    new Date(b.created_at).getTime() -
                    new Date(a.created_at).getTime()
                );
            } else {
                return (
                    new Date(a.created_at).getTime() -
                    new Date(b.created_at).getTime()
                );
            }
        });

    // Filter and sort badges
    const filteredBadges = myBadges
        .filter((badge) =>
            // Since we don't have badge title in the data, we'll use badge_id for search
            badge.badge_id.toString().includes(badgeSearch),
        )
        .sort((a, b) => {
            if (badgeSort === 'desc') {
                return (
                    new Date(b.created_at).getTime() -
                    new Date(a.created_at).getTime()
                );
            } else {
                return (
                    new Date(a.created_at).getTime() -
                    new Date(b.created_at).getTime()
                );
            }
        });

    return (
        <Card>
            <CardHeader>
                <CardTitle>Riwayat Pencapaian</CardTitle>
            </CardHeader>
            <CardContent>
                <Tabs
                    value={activeTabAchievements}
                    onValueChange={setActiveTabAchievements}
                >
                    <TabsList className="hidden w-full grid-cols-2 md:grid">
                        <TabsTrigger value="certificates">
                            Sertifikat Saya
                        </TabsTrigger>
                        <TabsTrigger value="badges">Koleksi Badges</TabsTrigger>
                    </TabsList>
                    <div className="mb-4 md:hidden">
                        <Select
                            value={activeTabAchievements}
                            onValueChange={setActiveTabAchievements}
                        >
                            <SelectTrigger className="w-full">
                                <SelectValue placeholder="Pilih aktivitas" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="certificates">
                                    Sertifikat Saya
                                </SelectItem>
                                <SelectItem value="badges">
                                    Koleksi Badges
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    {/* Certificates Tab */}
                    <TabsContent value="certificates" className="mt-6">
                        <div className="mb-4 flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center">
                            <div></div>
                            <div className="flex items-center gap-2">
                                <Select
                                    value={certificateSort}
                                    onValueChange={setCertificateSort}
                                >
                                    <SelectTrigger className="w-[140px]">
                                        <SelectValue placeholder="Tampilkan" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="desc">
                                            Terbaru
                                        </SelectItem>
                                        <SelectItem value="asc">
                                            Terlama
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <Input
                                    type="text"
                                    placeholder="Cari sertifikat..."
                                    className="w-40"
                                    value={certificateSearch}
                                    onChange={(e) =>
                                        setCertificateSearch(e.target.value)
                                    }
                                />
                            </div>
                        </div>

                        {filteredCertificates.length === 0 ? (
                            <div className="py-8 text-center">
                                <Award className="mx-auto mb-4 h-12 w-12 text-gray-400" />
                                <p className="text-gray-600">
                                    {certificateSearch
                                        ? 'Tidak ada sertifikat yang cocok dengan pencarian'
                                        : 'Belum ada sertifikat yang diterima'}
                                </p>
                            </div>
                        ) : (
                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                {filteredCertificates.map((certificate) => (
                                    <Card
                                        key={certificate.id}
                                        className="border-2 border-blue-200"
                                    >
                                        <CardContent className="p-4 text-center sm:p-6">
                                            <div className="mb-3 text-3xl sm:text-4xl">
                                                📜
                                            </div>
                                            <h3 className="mb-2 text-sm font-semibold text-gray-900 sm:text-base">
                                                {certificate.title}
                                            </h3>
                                            <div className="mb-3 flex items-center justify-center gap-1 text-xs text-gray-600">
                                                <Calendar className="h-3 w-3" />
                                                <span>
                                                    Diterima:{' '}
                                                    {formatDate(
                                                        certificate.created_at,
                                                    )}
                                                </span>
                                            </div>
                                            <button
                                                onClick={() =>
                                                    handleDownloadCertificate(
                                                        certificate.certificate_url,
                                                        certificate.title,
                                                    )
                                                }
                                                className="flex w-full items-center justify-center gap-2 rounded-md bg-blue-500 px-3 py-2 text-xs text-white transition-colors hover:bg-blue-600"
                                            >
                                                <Download className="h-3 w-3" />
                                                Download
                                            </button>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        )}
                    </TabsContent>

                    {/* Badges Tab */}
                    <TabsContent value="badges" className="mt-6">
                        <div className="mb-4 flex flex-col items-start justify-between gap-2 sm:flex-row sm:items-center">
                            <div></div>
                            <div className="flex items-center gap-2">
                                <Select
                                    value={badgeSort}
                                    onValueChange={setBadgeSort}
                                >
                                    <SelectTrigger className="w-[140px]">
                                        <SelectValue placeholder="Tampilkan" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="desc">
                                            Terbaru
                                        </SelectItem>
                                        <SelectItem value="asc">
                                            Terlama
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <Input
                                    type="text"
                                    placeholder="Cari badge..."
                                    className="w-40"
                                    value={badgeSearch}
                                    onChange={(e) =>
                                        setBadgeSearch(e.target.value)
                                    }
                                />
                            </div>
                        </div>

                        {filteredBadges.length === 0 ? (
                            <div className="py-8 text-center">
                                <Award className="mx-auto mb-4 h-12 w-12 text-gray-400" />
                                <p className="text-gray-600">
                                    {badgeSearch
                                        ? 'Tidak ada badge yang cocok dengan pencarian'
                                        : 'Belum ada badge yang diterima'}
                                </p>
                            </div>
                        ) : (
                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                {filteredBadges.map((userBadge) => {
                                    const rarity = getBadgeRarity(
                                        userBadge.badge,
                                    );
                                    return (
                                        <Card
                                            key={userBadge.id}
                                            className={`border-2 ${getRarityColor(rarity)}`}
                                        >
                                            <CardContent className="p-4 text-center sm:p-6">
                                                <div className="mb-3 flex h-12 items-center justify-center">
                                                    {getBadgeIcon(
                                                        userBadge.badge,
                                                    )}
                                                    <span className="hidden text-3xl sm:text-4xl">
                                                        🏅
                                                    </span>
                                                </div>
                                                <h3 className="mb-2 text-sm font-semibold text-gray-900 sm:text-base">
                                                    {userBadge.badge.title}
                                                </h3>
                                                <p className="mb-3 text-xs text-gray-600 sm:text-sm">
                                                    {userBadge.badge
                                                        .description ||
                                                        'Achievement badge earned through platform activities'}
                                                </p>
                                                <Badge
                                                    variant="outline"
                                                    className="mb-2 text-xs"
                                                >
                                                    {rarity}
                                                </Badge>
                                                <div className="flex items-center justify-center gap-1 text-xs text-gray-500">
                                                    <Calendar className="h-3 w-3" />
                                                    <span>
                                                        Diterima:{' '}
                                                        {formatDate(
                                                            userBadge.created_at,
                                                        )}
                                                    </span>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    );
                                })}
                            </div>
                        )}
                    </TabsContent>
                </Tabs>
            </CardContent>
        </Card>
    );
};

export default AchievementCard;
