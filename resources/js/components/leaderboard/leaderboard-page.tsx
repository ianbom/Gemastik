'use client';

import {
    Award,
    FileText,
    Heart,
    Medal,
    Shield,
    Target,
    Trophy,
} from 'lucide-react';
import { useState } from 'react';
// import Image from "next/image"

// TypeScript interfaces
interface LeaderboardUser {
    id: number;
    name: string;
    profile_url: string | null;
    reports_count?: number;
    missions_count?: number;
    total_donation?: number;
}

interface LeaderboardPageProps {
    // Data for the Top 3 Podiums
    top3Reporters: LeaderboardUser[];
    top3MissionVolunteers: LeaderboardUser[];
    top3Donors: LeaderboardUser[];

    // Data for the Top 10 Lists
    top10Reporters: LeaderboardUser[];
    top10MissionVolunteers: LeaderboardUser[];
    top10Donors: LeaderboardUser[];
}

// Mock data for demonstration
const mockProps: LeaderboardPageProps = {
    top3Reporters: [
        {
            id: 1,
            name: 'Sari Wijaya',
            profile_url: '/placeholder.svg?height=80&width=80',
            reports_count: 58,
        },
        {
            id: 2,
            name: 'Budi Santoso',
            profile_url: '/placeholder.svg?height=80&width=80',
            reports_count: 45,
        },
        {
            id: 3,
            name: 'Maya Putri',
            profile_url: '/placeholder.svg?height=80&width=80',
            reports_count: 38,
        },
    ],
    top3MissionVolunteers: [
        {
            id: 4,
            name: 'Ahmad Rahman',
            profile_url: '/placeholder.svg?height=80&width=80',
            missions_count: 12,
        },
        {
            id: 5,
            name: 'Dewi Lestari',
            profile_url: '/placeholder.svg?height=80&width=80',
            missions_count: 10,
        },
        {
            id: 6,
            name: 'Rizki Pratama',
            profile_url: '/placeholder.svg?height=80&width=80',
            missions_count: 8,
        },
    ],
    top3Donors: [
        {
            id: 7,
            name: 'Indira Sari',
            profile_url: '/placeholder.svg?height=80&width=80',
            total_donation: 5000000,
        },
        {
            id: 8,
            name: 'Fajar Nugroho',
            profile_url: '/placeholder.svg?height=80&width=80',
            total_donation: 3500000,
        },
        {
            id: 9,
            name: 'Lia Permata',
            profile_url: '/placeholder.svg?height=80&width=80',
            total_donation: 2800000,
        },
    ],
    top10Reporters: [
        {
            id: 1,
            name: 'Sari Wijaya',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 58,
        },
        {
            id: 2,
            name: 'Budi Santoso',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 45,
        },
        {
            id: 3,
            name: 'Maya Putri',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 38,
        },
        {
            id: 10,
            name: 'Andi Wijaya',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 32,
        },
        {
            id: 11,
            name: 'Nina Sari',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 28,
        },
        {
            id: 12,
            name: 'Doni Pratama',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 25,
        },
        {
            id: 13,
            name: 'Rini Lestari',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 22,
        },
        {
            id: 14,
            name: 'Hadi Kusuma',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 19,
        },
        {
            id: 15,
            name: 'Sinta Dewi',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 16,
        },
        {
            id: 16,
            name: 'Yoga Pratama',
            profile_url: '/placeholder.svg?height=40&width=40',
            reports_count: 14,
        },
    ],
    top10MissionVolunteers: [
        {
            id: 4,
            name: 'Ahmad Rahman',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 12,
        },
        {
            id: 5,
            name: 'Dewi Lestari',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 10,
        },
        {
            id: 6,
            name: 'Rizki Pratama',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 8,
        },
        {
            id: 17,
            name: 'Lina Sari',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 7,
        },
        {
            id: 18,
            name: 'Bayu Adi',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 6,
        },
        {
            id: 19,
            name: 'Citra Dewi',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 5,
        },
        {
            id: 20,
            name: 'Eko Susanto',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 5,
        },
        {
            id: 21,
            name: 'Fitri Handayani',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 4,
        },
        {
            id: 22,
            name: 'Gilang Ramadan',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 4,
        },
        {
            id: 23,
            name: 'Hesti Pratiwi',
            profile_url: '/placeholder.svg?height=40&width=40',
            missions_count: 3,
        },
    ],
    top10Donors: [
        {
            id: 7,
            name: 'Indira Sari',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 5000000,
        },
        {
            id: 8,
            name: 'Fajar Nugroho',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 3500000,
        },
        {
            id: 9,
            name: 'Lia Permata',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 2800000,
        },
        {
            id: 24,
            name: 'Irwan Setiawan',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 2500000,
        },
        {
            id: 25,
            name: 'Jihan Aulia',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 2200000,
        },
        {
            id: 26,
            name: 'Krisna Wijaya',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 1800000,
        },
        {
            id: 27,
            name: 'Laras Sari',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 1500000,
        },
        {
            id: 28,
            name: 'Maulana Yusuf',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 1200000,
        },
        {
            id: 29,
            name: 'Nadia Putri',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 1000000,
        },
        {
            id: 30,
            name: 'Omar Faruq',
            profile_url: '/placeholder.svg?height=40&width=40',
            total_donation: 800000,
        },
    ],
};

export default function LeaderboardPage(
    props: LeaderboardPageProps = mockProps,
) {
    const [activeTab, setActiveTab] = useState<
        'reports' | 'missions' | 'donations'
    >('reports');

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(amount);
    };

    const getRankIcon = (rank: number) => {
        switch (rank) {
            case 1:
                return <Trophy className="h-8 w-8 text-yellow-500" />;
            case 2:
                return <Medal className="h-8 w-8 text-gray-400" />;
            case 3:
                return <Award className="h-8 w-8 text-amber-600" />;
            default:
                return (
                    <span className="text-2xl font-bold text-emerald-600">
                        #{rank}
                    </span>
                );
        }
    };

    const getRankEmoji = (rank: number) => {
        switch (rank) {
            case 1:
                return '🥇';
            case 2:
                return '🥈';
            case 3:
                return '🥉';
            default:
                return `#${rank}`;
        }
    };

    const getPodiumCardStyle = (rank: number) => {
        switch (rank) {
            case 1:
                return 'bg-gradient-to-br from-yellow-400 to-yellow-600 border-yellow-500';
            case 2:
                return 'bg-gradient-to-br from-gray-300 to-gray-500 border-gray-400';
            case 3:
                return 'bg-gradient-to-br from-amber-500 to-amber-700 border-amber-600';
            default:
                return 'bg-white border-gray-200';
        }
    };

    const PodiumCard = ({
        user,
        rank,
        metric,
    }: {
        user: LeaderboardUser;
        rank: number;
        metric: string;
        category: 'reports' | 'missions' | 'donations';
    }) => (
        <div
            className={`relative transform rounded-xl border-2 p-6 shadow-lg transition-all duration-300 hover:scale-105 ${getPodiumCardStyle(rank)}`}
        >
            <div className="text-center">
                <div className="mb-3 text-4xl">{getRankEmoji(rank)}</div>
                <div className="relative mb-4"></div>
                <h3 className="mb-2 text-lg font-bold text-white">
                    {user.name}
                </h3>
                <p className="font-semibold text-white/90">{metric}</p>
            </div>
        </div>
    );

    const LeaderboardRow = ({
        user,
        rank,
        category,
    }: {
        user: LeaderboardUser;
        rank: number;
        category: 'reports' | 'missions' | 'donations';
    }) => {
        let metric = '';
        switch (category) {
            case 'reports':
                metric = `${user.reports_count} Laporan`;
                break;
            case 'missions':
                metric = `${user.missions_count} Misi`;
                break;
            case 'donations':
                metric = formatCurrency(user.total_donation || 0);
                break;
        }

        return (
            <div className="flex items-center justify-between rounded-lg border border-gray-100 bg-white p-4 shadow-sm transition-shadow duration-200 hover:shadow-md">
                <div className="flex items-center space-x-4">
                    <div className="flex h-10 w-10 items-center justify-center">
                        {getRankIcon(rank)}
                    </div>
                    {/* <Image
            src={user.profile_url || "/placeholder.svg?height=40&width=40&query=avatar"}
            alt={user.name}
            width={40}
            height={40}
            className="border-2 rounded-full border-emerald-200"
          /> */}
                    <div>
                        <h4 className="font-semibold text-gray-900">
                            {user.name}
                        </h4>
                    </div>
                </div>
                <div className="text-right">
                    <p className="font-bold text-emerald-600">{metric}</p>
                </div>
            </div>
        );
    };

    return (
        <div className="min-h-screen bg-gradient-to-br from-emerald-50 to-green-100 px-4 py-8">
            <div className="mx-auto max-w-7xl">
                {/* Main Header */}
                <div className="mb-12 text-center">
                    <h1 className="mb-4 text-4xl font-bold text-emerald-800 md:text-5xl">
                        🌍 Pahlawan Bumi
                    </h1>
                    <p className="mx-auto max-w-2xl text-xl text-emerald-600">
                        Apresiasi untuk para kontributor paling berpengaruh di
                        SobatBumi.
                    </p>
                </div>

                {/* Section 1: Podium Pahlawan */}
                <div className="mb-16">
                    <h2 className="mb-8 text-center text-3xl font-bold text-emerald-800">
                        🏆 Podium Pahlawan
                    </h2>

                    {/* Top 3 Reporters */}
                    <div className="mb-12">
                        <div className="mb-6 flex items-center justify-center">
                            <FileText className="mr-2 h-6 w-6 text-emerald-600" />
                            <h3 className="text-2xl font-semibold text-emerald-700">
                                Top 3 Pengguna dengan Laporan Terbanyak
                            </h3>
                        </div>
                        <div className="mx-auto grid max-w-4xl grid-cols-1 gap-6 md:grid-cols-3">
                            {props.top3Reporters.map((user, index) => (
                                <PodiumCard
                                    key={user.id}
                                    user={user}
                                    rank={index + 1}
                                    metric={`${user.reports_count} Laporan Terverifikasi`}
                                    category="reports"
                                />
                            ))}
                        </div>
                    </div>

                    {/* Top 3 Mission Volunteers */}
                    <div className="mb-12">
                        <div className="mb-6 flex items-center justify-center">
                            <Target className="mr-2 h-6 w-6 text-emerald-600" />
                            <h3 className="text-2xl font-semibold text-emerald-700">
                                Top 3 Pengguna dengan Misi Terbanyak
                            </h3>
                        </div>
                        <div className="mx-auto grid max-w-4xl grid-cols-1 gap-6 md:grid-cols-3">
                            {props.top3MissionVolunteers.map((user, index) => (
                                <PodiumCard
                                    key={user.id}
                                    user={user}
                                    rank={index + 1}
                                    metric={`${user.missions_count} Misi Diikuti`}
                                    category="missions"
                                />
                            ))}
                        </div>
                    </div>

                    {/* Top 3 Donors */}
                    <div className="mb-12">
                        <div className="mb-6 flex items-center justify-center">
                            <Heart className="mr-2 h-6 w-6 text-emerald-600" />
                            <h3 className="text-2xl font-semibold text-emerald-700">
                                Top 3 Pengguna dengan Donasi Terbanyak
                            </h3>
                        </div>
                        <div className="mx-auto grid max-w-4xl grid-cols-1 gap-6 md:grid-cols-3">
                            {props.top3Donors.map((user, index) => (
                                <PodiumCard
                                    key={user.id}
                                    user={user}
                                    rank={index + 1}
                                    metric={`${formatCurrency(user.total_donation || 0)} Terkumpul`}
                                    category="donations"
                                />
                            ))}
                        </div>
                    </div>
                </div>

                {/* Section 2: Daftar Peringkat */}
                <div className="rounded-2xl bg-white p-8 shadow-xl">
                    <h2 className="mb-8 text-center text-3xl font-bold text-emerald-800">
                        📊 Daftar Peringkat
                    </h2>

                    {/* Tabs */}
                    <div className="mb-8 flex flex-wrap justify-center border-b border-gray-200">
                        <button
                            onClick={() => setActiveTab('reports')}
                            className={`flex items-center border-b-2 px-6 py-3 font-semibold transition-colors duration-200 ${
                                activeTab === 'reports'
                                    ? 'border-emerald-600 text-emerald-600'
                                    : 'border-transparent text-gray-500 hover:text-emerald-500'
                            }`}
                        >
                            <FileText className="mr-2 h-5 w-5" />
                            Laporan Terbanyak
                        </button>
                        <button
                            onClick={() => setActiveTab('missions')}
                            className={`flex items-center border-b-2 px-6 py-3 font-semibold transition-colors duration-200 ${
                                activeTab === 'missions'
                                    ? 'border-emerald-600 text-emerald-600'
                                    : 'border-transparent text-gray-500 hover:text-emerald-500'
                            }`}
                        >
                            <Shield className="mr-2 h-5 w-5" />
                            Misi Terbanyak
                        </button>
                        <button
                            onClick={() => setActiveTab('donations')}
                            className={`flex items-center border-b-2 px-6 py-3 font-semibold transition-colors duration-200 ${
                                activeTab === 'donations'
                                    ? 'border-emerald-600 text-emerald-600'
                                    : 'border-transparent text-gray-500 hover:text-emerald-500'
                            }`}
                        >
                            <Heart className="mr-2 h-5 w-5" />
                            Donasi Terbanyak
                        </button>
                    </div>

                    {/* Tab Content */}
                    <div className="space-y-4">
                        {activeTab === 'reports' && (
                            <div>
                                {props.top10Reporters.map((user, index) => (
                                    <LeaderboardRow
                                        key={user.id}
                                        user={user}
                                        rank={index + 1}
                                        category="reports"
                                    />
                                ))}
                            </div>
                        )}
                        {activeTab === 'missions' && (
                            <div>
                                {props.top10MissionVolunteers.map(
                                    (user, index) => (
                                        <LeaderboardRow
                                            key={user.id}
                                            user={user}
                                            rank={index + 1}
                                            category="missions"
                                        />
                                    ),
                                )}
                            </div>
                        )}
                        {activeTab === 'donations' && (
                            <div>
                                {props.top10Donors.map((user, index) => (
                                    <LeaderboardRow
                                        key={user.id}
                                        user={user}
                                        rank={index + 1}
                                        category="donations"
                                    />
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
