import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { formatCurrency } from '@/utils/formatCurrency';
import { FileText, Heart, Target } from 'lucide-react';
import React, { useState } from 'react';

interface LeaderboardUser {
    id: number;
    name: string;
    profile_url: string | null;
    reports_count?: number;
    missions_count?: number;
    total_donation?: number;
}

interface LeaderboardPageProps {
    top3Reporters?: LeaderboardUser[];
    top3MissionVolunteers?: LeaderboardUser[];
    top3Donors?: LeaderboardUser[];

    // Data for the Top 10 Lists
    top10Reporters?: LeaderboardUser[];
    top10MissionVolunteers?: LeaderboardUser[];
    top10Donors?: LeaderboardUser[];
}

const LeaderBoard: React.FC<LeaderboardPageProps> = (props = {}) => {
    const [mobileActiveTab, setMobileActiveTab] = useState<
        'reports' | 'missions' | 'donations'
    >('reports');
    const data = {
        top3Reporters: props.top3Reporters || [],
        top3MissionVolunteers: props.top3MissionVolunteers || [],
        top3Donors: props.top3Donors || [],
        top10Reporters: props.top10Reporters || [],
        top10MissionVolunteers: props.top10MissionVolunteers || [],
        top10Donors: props.top10Donors || [],
    };

    const PodiumCard: React.FC<{
        user: LeaderboardUser;
        rank: number;
        metric: string;
        category: 'reports' | 'missions' | 'donations';
        isMobile?: boolean;
    }> = ({ user, rank, metric, isMobile = false }) => {
        const getCardStyle = (rank: number) => {
            switch (rank) {
                case 1:
                    return {
                        height: isMobile ? '120px' : '400px',
                        className:
                            'bg-gradient-to-b from-yellow-400 to-yellow-600 border-yellow-500',
                    };
                case 2:
                    return {
                        height: isMobile ? '120px' : '320px',
                        className:
                            'bg-gradient-to-b from-gray-300 to-gray-500 border-gray-400',
                    };
                case 3:
                    return {
                        height: isMobile ? '120px' : '280px',
                        className:
                            'bg-gradient-to-b from-orange-400 to-orange-600 border-orange-500',
                    };
                default:
                    return {
                        height: isMobile ? '120px' : '320px',
                        className:
                            'bg-gradient-to-b from-gray-300 to-gray-500 border-gray-400',
                    };
            }
        };

        const cardStyle = getCardStyle(rank);

        if (isMobile) {
            return (
                <div
                    className={`relative flex transform items-center rounded-xl border-2 p-4 shadow-lg transition-all duration-300 ${cardStyle.className}`}
                    style={{ height: cardStyle.height }}
                >
                    <div className="flex items-center justify-center mr-4">
                        <div className="flex items-center justify-center w-12 h-12 border-2 rounded-full border-white/50 bg-white/30">
                            <span className="text-xl font-bold text-white">
                                {rank}
                            </span>
                        </div>
                    </div>
                    <div className="flex items-center flex-1">
                        <div className="flex items-center justify-center w-16 h-16 mr-4 bg-white border-4 border-white rounded-full shadow-lg">
                            <span className="text-xl text-gray-600">👤</span>
                        </div>
                        <div className="text-left">
                            <h3 className="mb-1 text-lg font-bold text-white">
                                {user.name}
                            </h3>
                            <p className="text-sm font-semibold text-white/90">
                                {metric}
                            </p>
                        </div>
                    </div>
                </div>
            );
        }

        return (
            <div
                className={`relative flex transform flex-col justify-between rounded-xl border-2 p-6 shadow-lg transition-all duration-300 hover:scale-105 ${cardStyle.className}`}
                style={{ height: cardStyle.height }}
            >
                <div className="flex justify-center">
                    <div className="flex items-center justify-center w-10 h-10 border-2 rounded-full border-white/50 bg-white/30">
                        <span className="text-xl font-bold text-white">
                            {rank}
                        </span>
                    </div>
                </div>
                <div className="flex flex-col justify-center flex-1 text-center">
                    <div className="relative mb-4">
                        <div className="flex items-center justify-center w-20 h-20 mx-auto bg-white border-4 border-white rounded-full shadow-lg">
                            <span className="text-2xl text-gray-600">👤</span>
                        </div>
                    </div>
                    <h3 className="mb-2 text-lg font-bold text-white">
                        {user.name}
                    </h3>
                </div>
                <div className="text-center">
                    <p className="font-semibold text-white/90">{metric}</p>
                </div>
            </div>
        );
    };

    const LeaderboardRow: React.FC<{
        user: LeaderboardUser;
        rank: number;
        category: 'reports' | 'missions' | 'donations';
    }> = ({ user, rank, category }) => {
        let metric = '';
        switch (category) {
            case 'reports':
                metric = `${user.reports_count || 0} Laporan`;
                break;
            case 'missions':
                metric = `${user.missions_count || 0} Misi`;
                break;
            case 'donations':
                metric = formatCurrency(user.total_donation || 0);
                break;
        }
        return (
            <div
                className="flex items-center justify-between p-4 transition-shadow duration-200 border border-green-100 rounded-lg shadow-sm hover:shadow-md"
                style={{
                    backgroundImage:
                        'linear-gradient(to right, #60a5fa, #38bdf8, #22d3ee)', // blue-400 → sky-400 → cyan-400
                }}
            >
                <div className="flex items-center space-x-4">
                    <div className="flex items-center justify-center w-10 h-10 border-2 rounded-full border-white/50 bg-white/30">
                        <span className="text-xl font-bold text-white">
                            {rank}
                        </span>
                    </div>
                    <div className="flex items-center justify-center w-12 h-12 bg-gray-200 border-2 rounded-full border-emerald-200">
                        <span className="text-sm text-gray-600">👤</span>
                    </div>
                    <div>
                        <h4 className="font-semibold text-white">
                            {user.name}
                        </h4>
                    </div>
                </div>
                <div className="text-right">
                    <p className="font-extrabold text-white">{metric}</p>
                </div>
            </div>
        );
    };

    const TabContent: React.FC<{
        top3Users: LeaderboardUser[];
        top10Users: LeaderboardUser[];
        category: 'reports' | 'missions' | 'donations';
        metricFormatter: (user: LeaderboardUser) => string;
    }> = ({ top3Users, top10Users, category, metricFormatter }) => (
        <div>
            <div className="hidden mb-12 md:block">
                <div className="flex items-end justify-center max-w-5xl gap-6 mx-auto">
                    {top3Users[1] && (
                        <div className="flex-1 max-w-xs">
                            <PodiumCard
                                user={top3Users[1]}
                                rank={2}
                                metric={metricFormatter(top3Users[1])}
                                category={category}
                            />
                        </div>
                    )}
                    {top3Users[0] && (
                        <div className="flex-1 max-w-xs">
                            <PodiumCard
                                user={top3Users[0]}
                                rank={1}
                                metric={metricFormatter(top3Users[0])}
                                category={category}
                            />
                        </div>
                    )}
                    {top3Users[2] && (
                        <div className="flex-1 max-w-xs">
                            <PodiumCard
                                user={top3Users[2]}
                                rank={3}
                                metric={metricFormatter(top3Users[2])}
                                category={category}
                            />
                        </div>
                    )}
                </div>
            </div>
            <div className="mb-8 space-y-4 md:hidden">
                {top3Users.map((user, index) => (
                    <PodiumCard
                        key={user.id}
                        user={user}
                        rank={index + 1}
                        metric={metricFormatter(user)}
                        category={category}
                        isMobile={true}
                    />
                ))}
            </div>
            <div className="space-y-4">
                <h3 className="mb-4 text-xl font-bold text-gray-800">
                    Peringkat Lainnya
                </h3>
                {top10Users.slice(3).map((user, index) => (
                    <LeaderboardRow
                        key={user.id}
                        user={user}
                        rank={index + 4}
                        category={category}
                    />
                ))}
            </div>
        </div>
    );

    const tabOptions = [
        { value: 'reports', label: 'Laporan Terbanyak', icon: FileText },
        { value: 'missions', label: 'Misi Terbanyak', icon: Target },
        { value: 'donations', label: 'Donasi Terbanyak', icon: Heart },
    ];

    return (
        // <div className="min-h-screen px-4 py-8 bg-gradient-to-br from-emerald-50 to-green-100">
        <div className="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div className="mx-auto max-w-7xl">
                <div className="mb-8 md:mb-12">
                    <h1 className="mb-4 text-3xl font-bold text-sky-800 md:text-4xl lg:text-5xl">
                        🌍 Pahlawan Bumi
                    </h1>
                    <p className="max-w-2xl text-lg text-sky-700 md:text-xl">
                        Apresiasi untuk para kontributor paling berpengaruh di
                        KawanBumi.
                    </p>
                </div>

                <div className="bg-transparent rounded-2xl">
                    <div className="hidden md:block">
                        <Tabs defaultValue="reports" className="w-full">
                            <TabsList className="w-full grid-cols-3 mb-8 bg-transparent md:mb-16 md:grid">
                                {tabOptions.map((tab) => {
                                    const Icon = tab.icon;
                                    return (
                                        <TabsTrigger
                                            key={tab.value}
                                            value={tab.value}
                                            className="mr-5 flex items-center gap-2 rounded-md bg-white py-3 text-base transition-all duration-200 hover:text-sky-600 data-[state=active]:bg-sky-600 data-[state=active]:font-bold data-[state=active]:text-white md:text-lg"
                                        >
                                            <Icon className="w-4 h-4" />
                                            <span className="hidden sm:inline">
                                                {tab.label}
                                            </span>
                                            <span className="sm:hidden">
                                                {tab.value === 'reports'
                                                    ? 'Laporan'
                                                    : tab.value === 'missions'
                                                      ? 'Misi'
                                                      : 'Donasi'}
                                            </span>
                                        </TabsTrigger>
                                    );
                                })}
                            </TabsList>

                            <TabsContent value="reports" className="mt-6">
                                <TabContent
                                    top3Users={data.top3Reporters}
                                    top10Users={data.top10Reporters}
                                    category="reports"
                                    metricFormatter={(user) =>
                                        `${user.reports_count || 0} Laporan Terverifikasi`
                                    }
                                />
                            </TabsContent>

                            <TabsContent value="missions" className="mt-6">
                                <TabContent
                                    top3Users={data.top3MissionVolunteers}
                                    top10Users={data.top10MissionVolunteers}
                                    category="missions"
                                    metricFormatter={(user) =>
                                        `${user.missions_count || 0} Misi Diikuti`
                                    }
                                />
                            </TabsContent>

                            <TabsContent value="donations" className="mt-6">
                                <TabContent
                                    top3Users={data.top3Donors}
                                    top10Users={data.top10Donors}
                                    category="donations"
                                    metricFormatter={(user) =>
                                        `${formatCurrency(user.total_donation || 0)} Terkumpul`
                                    }
                                />
                            </TabsContent>
                        </Tabs>
                    </div>

                    <div className="md:hidden">
                        <div className="mb-8">
                            <Select
                                value={mobileActiveTab}
                                onValueChange={(value) =>
                                    setMobileActiveTab(
                                        value as
                                            | 'reports'
                                            | 'missions'
                                            | 'donations',
                                    )
                                }
                            >
                                <SelectTrigger className="w-full py-6 text-lg bg-white">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    {tabOptions.map((tab) => {
                                        const Icon = tab.icon;
                                        return (
                                            <SelectItem
                                                key={tab.value}
                                                value={tab.value}
                                            >
                                                <div className="flex items-center gap-2">
                                                    <Icon className="w-4 h-4" />
                                                    {tab.label}
                                                </div>
                                            </SelectItem>
                                        );
                                    })}
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="mt-6">
                            {mobileActiveTab === 'reports' && (
                                <TabContent
                                    top3Users={data.top3Reporters}
                                    top10Users={data.top10Reporters}
                                    category="reports"
                                    metricFormatter={(user) =>
                                        `${user.reports_count || 0} Laporan Terverifikasi`
                                    }
                                />
                            )}
                            {mobileActiveTab === 'missions' && (
                                <TabContent
                                    top3Users={data.top3MissionVolunteers}
                                    top10Users={data.top10MissionVolunteers}
                                    category="missions"
                                    metricFormatter={(user) =>
                                        `${user.missions_count || 0} Misi Diikuti`
                                    }
                                />
                            )}
                            {mobileActiveTab === 'donations' && (
                                <TabContent
                                    top3Users={data.top3Donors}
                                    top10Users={data.top10Donors}
                                    category="donations"
                                    metricFormatter={(user) =>
                                        `${formatCurrency(user.total_donation || 0)} Terkumpul`
                                    }
                                />
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default LeaderBoard;
