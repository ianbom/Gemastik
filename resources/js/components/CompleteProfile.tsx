import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { PageProps } from '@/types';
import { router, useForm, usePage } from '@inertiajs/react';
import axios from 'axios';
import { Leaf, Mail, MapPin, Phone, UserIcon } from 'lucide-react';
import type React from 'react';
import { useEffect, useState } from 'react';
// Type definitions
interface Province {
    id: number;
    name: string;
}

interface City {
    id: number;
    name: string;
    province_id: number;
}

interface District {
    id: number;
    name: string;
    city_id: number;
}

interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    province_id: number | null;
    city_id: number | null;
    district_id: number | null;
    address: string | null;
}

// interface CompleteProfilePageProps {
//     user: User;
//     provinces: Province[];
//     initialCities?: City[];
//     initialDistricts?: District[];
// }
interface CompleteProfilePageProps extends Record<string, unknown> {
    user: User;
    provinces: Province[];
    initialCities: City[];
    initialDistricts: District[];
}

// declare global {
//     function route(name: string, params?: any): string;
// }

export default function CompleteProfile() {
    // Get props from Inertia
    const { props } = usePage<PageProps<CompleteProfilePageProps>>();
    const {
        user,
        provinces,
        initialCities = [],
        initialDistricts = [],
    } = props;

    // Form state management with Inertia
    const { data, setData, patch, processing, errors } = useForm({
        phone: user.phone || '',
        province_id: user.province_id || null,
        city_id: user.city_id || null,
        district_id: user.district_id || null,
        address: user.address || '',
    });

    // State for dynamic location data
    const [cities, setCities] = useState<City[]>(initialCities);
    const [districts, setDistricts] = useState<District[]>(initialDistricts);

    // Loading states for cascading selects
    const [citiesLoading, setCitiesLoading] = useState(false);
    const [districtsLoading, setDistrictsLoading] = useState(false);

    // Handler for regular input changes
    const handleInputChange = (
        field: keyof typeof data,
        value: string | number | null,
    ) => {
        setData(field, value);
    };

    // Load cities when province changes or on initial load
    useEffect(() => {
        if (
            data.province_id &&
            cities.length === 0 &&
            !citiesLoading &&
            initialCities.length === 0
        ) {
            setCitiesLoading(true);
            axios
                .get<City[]>(
                    route('api.cities', { province_id: data.province_id }),
                )
                .then((response) => setCities(response.data))
                .catch((error) =>
                    console.error('Error fetching cities:', error),
                )
                .finally(() => setCitiesLoading(false));
        }
    }, [data.province_id, cities.length, citiesLoading, initialCities.length]);

    // Load districts when city changes or on initial load
    useEffect(() => {
        if (
            data.city_id &&
            districts.length === 0 &&
            !districtsLoading &&
            initialDistricts.length === 0
        ) {
            setDistrictsLoading(true);
            axios
                .get<District[]>(
                    route('api.districts', { city_id: data.city_id }),
                )
                .then((response) => setDistricts(response.data))
                .catch((error) =>
                    console.error('Error fetching districts:', error),
                )
                .finally(() => setDistrictsLoading(false));
        }
    }, [
        data.city_id,
        districts.length,
        districtsLoading,
        initialDistricts.length,
    ]);

    // Handle province change
    const handleProvinceChange = async (provinceId: string) => {
        const id = Number.parseInt(provinceId);
        setData((prev) => ({
            ...prev,
            province_id: id,
            city_id: null,
            district_id: null,
        }));

        setCities([]);
        setDistricts([]);
        setCitiesLoading(true);

        try {
            const response = await axios.get<City[]>(
                route('api.cities', { province_id: id }),
            );
            setCities(response.data);
        } catch (error) {
            console.error('Error fetching cities:', error);
        } finally {
            setCitiesLoading(false);
        }
    };

    // Handle city change
    const handleCityChange = async (cityId: string) => {
        const id = Number.parseInt(cityId);
        setData((prev) => ({
            ...prev,
            city_id: id,
            district_id: null,
        }));

        setDistricts([]);
        setDistrictsLoading(true);

        try {
            const response = await axios.get<District[]>(
                route('api.districts', { city_id: id }),
            );
            setDistricts(response.data);
        } catch (error) {
            console.error('Error fetching districts:', error);
        } finally {
            setDistrictsLoading(false);
        }
    };

    // Handle form submission
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(route('profile.update'), {
            onSuccess: () => {
                router.visit(route('dashboard'));
            },
            onError: (errors) => {
                console.error('Submission errors:', errors);
            },
        });
    };

    return (
        <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-green-50 to-emerald-100 p-4">
            <Card className="w-full max-w-2xl border-0 shadow-xl">
                <CardHeader className="pb-8 text-center">
                    <div className="mb-4 flex items-center justify-center">
                        <div className="rounded-full bg-green-600 p-3">
                            <Leaf className="h-8 w-8 text-white" />
                        </div>
                        <h1 className="ml-3 text-3xl font-bold text-green-800">
                            EcoTrack
                        </h1>
                    </div>
                    <CardTitle className="text-2xl text-gray-800">
                        Lengkapi Profil Anda
                    </CardTitle>
                    <CardDescription className="mt-2 text-gray-600">
                        Silakan lengkapi informasi profil Anda untuk melanjutkan
                        menggunakan platform EcoTrack
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Name Field (Read-only) */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="name"
                                className="flex items-center text-sm font-medium text-gray-700"
                            >
                                <UserIcon className="mr-2 h-4 w-4 text-green-600" />
                                Nama Lengkap
                            </Label>
                            <Input
                                id="name"
                                type="text"
                                value={user.name || ''}
                                readOnly
                                className="cursor-not-allowed bg-gray-50"
                            />
                        </div>

                        {/* Email Field (Read-only) */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="email"
                                className="flex items-center text-sm font-medium text-gray-700"
                            >
                                <Mail className="mr-2 h-4 w-4 text-green-600" />
                                Email
                            </Label>
                            <Input
                                id="email"
                                type="email"
                                value={user.email || ''}
                                readOnly
                                className="cursor-not-allowed bg-gray-50"
                            />
                        </div>

                        {/* Phone Field */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="phone"
                                className="flex items-center text-sm font-medium text-gray-700"
                            >
                                <Phone className="mr-2 h-4 w-4 text-green-600" />
                                Nomor Telepon
                            </Label>
                            <Input
                                id="phone"
                                type="tel"
                                placeholder="Masukkan nomor telepon"
                                value={data.phone}
                                onChange={(e) =>
                                    handleInputChange('phone', e.target.value)
                                }
                                className="focus:border-green-500 focus:ring-green-500"
                            />
                            {errors.phone && (
                                <p className="text-sm text-red-600">
                                    {errors.phone}
                                </p>
                            )}
                        </div>

                        {/* Province Field */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="province"
                                className="flex items-center text-sm font-medium text-gray-700"
                            >
                                <MapPin className="mr-2 h-4 w-4 text-green-600" />
                                Provinsi
                            </Label>
                            <Select
                                value={data.province_id?.toString() || ''}
                                onValueChange={handleProvinceChange}
                            >
                                <SelectTrigger className="focus:border-green-500 focus:ring-green-500">
                                    <SelectValue placeholder="Pilih provinsi" />
                                </SelectTrigger>
                                <SelectContent>
                                    {provinces.map((province) => (
                                        <SelectItem
                                            key={province.id}
                                            value={province.id.toString()}
                                        >
                                            {province.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {errors.province_id && (
                                <p className="text-sm text-red-600">
                                    {errors.province_id}
                                </p>
                            )}
                        </div>

                        {/* City Field */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="city"
                                className="text-sm font-medium text-gray-700"
                            >
                                Kota/Kabupaten
                            </Label>
                            <Select
                                value={data.city_id?.toString() || ''}
                                onValueChange={handleCityChange}
                                disabled={!data.province_id || citiesLoading}
                            >
                                <SelectTrigger className="focus:border-green-500 focus:ring-green-500">
                                    <SelectValue
                                        placeholder={
                                            citiesLoading
                                                ? 'Memuat kota...'
                                                : !data.province_id
                                                  ? 'Pilih provinsi terlebih dahulu'
                                                  : 'Pilih kota/kabupaten'
                                        }
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    {cities.map((city) => (
                                        <SelectItem
                                            key={city.id}
                                            value={city.id.toString()}
                                        >
                                            {city.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {errors.city_id && (
                                <p className="text-sm text-red-600">
                                    {errors.city_id}
                                </p>
                            )}
                        </div>

                        {/* District Field */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="district"
                                className="text-sm font-medium text-gray-700"
                            >
                                Kecamatan/Kelurahan
                            </Label>
                            <Select
                                value={data.district_id?.toString() || ''}
                                onValueChange={(value) =>
                                    handleInputChange(
                                        'district_id',
                                        Number.parseInt(value),
                                    )
                                }
                                disabled={!data.city_id || districtsLoading}
                            >
                                <SelectTrigger className="focus:border-green-500 focus:ring-green-500">
                                    <SelectValue
                                        placeholder={
                                            districtsLoading
                                                ? 'Memuat kecamatan...'
                                                : !data.city_id
                                                  ? 'Pilih kota terlebih dahulu'
                                                  : 'Pilih kecamatan/kelurahan'
                                        }
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    {districts.map((district) => (
                                        <SelectItem
                                            key={district.id}
                                            value={district.id.toString()}
                                        >
                                            {district.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {errors.district_id && (
                                <p className="text-sm text-red-600">
                                    {errors.district_id}
                                </p>
                            )}
                        </div>

                        {/* Address Field */}
                        <div className="space-y-2">
                            <Label
                                htmlFor="address"
                                className="text-sm font-medium text-gray-700"
                            >
                                Alamat Lengkap
                            </Label>
                            <Textarea
                                id="address"
                                placeholder="Masukkan alamat lengkap (nama jalan, nomor rumah, RT/RW, dll.)"
                                value={data.address}
                                onChange={(e) =>
                                    handleInputChange('address', e.target.value)
                                }
                                className="min-h-[100px] focus:border-green-500 focus:ring-green-500"
                                rows={4}
                            />
                            {errors.address && (
                                <p className="text-sm text-red-600">
                                    {errors.address}
                                </p>
                            )}
                        </div>

                        {/* Submit Button */}
                        <Button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-green-600 py-3 text-lg font-medium text-white transition-colors duration-200 hover:bg-green-700"
                        >
                            {processing ? (
                                <div className="flex items-center">
                                    <Leaf className="mr-2 h-4 w-4 animate-spin" />
                                    Menyimpan...
                                </div>
                            ) : (
                                'Lanjutkan'
                            )}
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </div>
    );
}
