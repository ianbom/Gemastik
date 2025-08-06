import InputField from '@/components/form/InputField';
import SelectField from '@/components/form/SelectField';
import { City, District, Province } from '@/types/area/interface';
import { Head, useForm, usePage } from '@inertiajs/react';
import { UsersRound } from 'lucide-react';
import { FormEventHandler, useEffect, useMemo, useState } from 'react';
export default function CompleteProfile() {
    const page = usePage();
    const provinces = page.props.provinces as Province[];
    const auth = page.props.auth as {
        user: {
            id: number;
            name: string;
            email: string;
            phone: string | null;
            province_id: number | null;
            city_id: number | null;
            district_id: number | null;
            address: string | null;
            community?: {
                name: string;
                member_count: number;
                description: string;
            };
        };
    };
    const { data, setData, post, processing, errors } = useForm({
        province_id: auth.user.province_id?.toString() ?? '',
        city_id: auth.user.city_id?.toString() ?? '',
        district_id: auth.user.district_id?.toString() ?? '',
        address: auth.user.address ?? '',
        phone: auth.user.phone ?? '',
        name: auth.user.name ?? '',
        email: auth.user.email ?? '',
        community: {
            name: auth.user?.community?.name || '',
            member_count: auth.user?.community?.member_count?.toString() || '',
            description: auth.user?.community?.description || '',
        },
    });

    const cities = useMemo(
        () => provinces.flatMap((p) => p.cities ?? []),
        [provinces],
    );
    const districts = useMemo(
        () => cities.flatMap((c) => c.districts ?? []),
        [cities],
    );

    const [filteredCities, setFilteredCities] = useState<City[]>([]);
    const [filteredDistricts, setFilteredDistricts] = useState<District[]>([]);

    console.log(data);

    useEffect(() => {
        const filtered = cities.filter(
            (city) => city.province_id === parseInt(data.province_id),
        );
        setFilteredCities(filtered);
        setData((prev) => ({ ...prev, city_id: '', district_id: '' }));
        setFilteredDistricts([]);
    }, [data.province_id]);

    useEffect(() => {
        const filtered = districts.filter(
            (district) => district.city_id === parseInt(data.city_id),
        );
        setFilteredDistricts(filtered);
        setData((prev) => ({ ...prev, district_id: '' }));
    }, [data.city_id]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('profile.complete.update'));
    };
    return (
        <>
            <Head title="Lengkapi Profile" />
            <div className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 px-4 py-8 sm:px-6 lg:px-8">
                <div className="relative mx-auto max-w-4xl">
                    {/* Main Card */}
                    <div className="overflow-hidden rounded-2xl border border-white/50 bg-white/80 shadow-2xl backdrop-blur-sm">
                        <div className="relative overflow-hidden bg-gradient-to-r from-emerald-600 to-green-600 px-8 py-12 text-center">
                            <div className="relative z-10">
                                <div className="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full border-2 border-white/30 bg-white/30 shadow-lg backdrop-blur-sm">
                                    <svg
                                        className="h-12 w-12 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                                <div className="space-y-2">
                                    <h1 className="text-4xl font-bold tracking-tight text-white">
                                        Lengkapi Data Anda
                                    </h1>
                                    <p className="mx-auto max-w-md text-lg text-blue-100">
                                        Silahkan lengkapi informasi pribadi Anda
                                        untuk melanjutkan
                                    </p>
                                </div>
                            </div>
                        </div>
                        <form
                            onSubmit={submit}
                            className="space-y-8 px-8 py-10"
                        >
                            <div className="space-y-6">
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500">
                                        <svg
                                            className="h-5 w-5 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 className="text-xl font-semibold text-gray-900">
                                            Informasi Pribadi
                                        </h2>
                                        <p className="text-sm text-gray-500">
                                            Data pribadi dan kontak Anda
                                        </p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <InputField
                                        label="Nama Lengkap"
                                        isRequired
                                        id="name"
                                        value={data.name}
                                        onChange={(e) =>
                                            setData('name', e.target.value)
                                        }
                                        error={errors.name}
                                    />
                                    <InputField
                                        label="Email"
                                        id="email"
                                        type="email"
                                        value={data.email}
                                        onChange={(e) =>
                                            setData('email', e.target.value)
                                        }
                                        error={errors.email}
                                        disabled
                                    />
                                </div>

                                <InputField
                                    label="Nomor Telepon"
                                    id="phone"
                                    value={data.phone}
                                    onChange={(e) =>
                                        setData('phone', e.target.value)
                                    }
                                    error={errors.phone}
                                    type="tel"
                                    isRequired
                                />
                            </div>
                            <div className="space-y-6">
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500">
                                        <svg
                                            className="h-5 w-5 text-white"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            />
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 className="text-xl font-semibold text-gray-900">
                                            Informasi Alamat
                                        </h2>
                                        <p className="text-sm text-gray-500">
                                            Lokasi tempat tinggal Anda
                                        </p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <SelectField
                                        label="Provinsi"
                                        id="province_id"
                                        value={data.province_id}
                                        onChange={(e) =>
                                            setData(
                                                'province_id',
                                                e.target.value,
                                            )
                                        }
                                        error={errors.province_id}
                                    >
                                        <option value="">Pilih Provinsi</option>
                                        {provinces.map((prov) => (
                                            <option
                                                key={prov.id}
                                                value={prov.id}
                                            >
                                                {prov.name}
                                            </option>
                                        ))}
                                    </SelectField>

                                    <SelectField
                                        label="Kota/Kabupaten *"
                                        id="city_id"
                                        value={data.city_id}
                                        onChange={(e) =>
                                            setData('city_id', e.target.value)
                                        }
                                        error={errors.city_id}
                                        disabled={!filteredCities.length}
                                    >
                                        <option value="">
                                            Pilih Kota/Kabupaten
                                        </option>
                                        {filteredCities.map((city) => (
                                            <option
                                                key={city.id}
                                                value={city.id}
                                            >
                                                {city.name}
                                            </option>
                                        ))}
                                    </SelectField>

                                    <SelectField
                                        label="Kecamatan"
                                        id="district_id"
                                        value={data.district_id}
                                        onChange={(e) =>
                                            setData(
                                                'district_id',
                                                e.target.value,
                                            )
                                        }
                                        error={errors.district_id}
                                        disabled={!filteredDistricts.length}
                                    >
                                        <option value="">
                                            Pilih Kecamatan
                                        </option>
                                        {filteredDistricts.map((d) => (
                                            <option key={d.id} value={d.id}>
                                                {d.name}
                                            </option>
                                        ))}
                                    </SelectField>
                                </div>

                                <div>
                                    <label
                                        htmlFor="address"
                                        className="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Alamat Lengkap{' '}
                                        <span className="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        id="address"
                                        value={data.address}
                                        onChange={(e) =>
                                            setData('address', e.target.value)
                                        }
                                        rows={4}
                                        className={`w-full resize-none rounded-xl border-2 px-4 py-3 text-sm transition-all duration-200 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ${
                                            errors.address
                                                ? 'border-red-300 bg-red-50'
                                                : 'border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white'
                                        }`}
                                        placeholder="Masukkan alamat lengkap (nama jalan, RT/RW, dll)"
                                    />
                                    {errors.address && (
                                        <p className="mt-2 flex items-center text-sm text-red-600">
                                            <svg
                                                className="mr-1 h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth={2}
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            {errors.address}
                                        </p>
                                    )}
                                </div>
                            </div>
                            <div className="space-y-6">
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-r from-emerald-500 to-teal-500">
                                        <UsersRound className="h-5 w-5 text-white" />
                                    </div>
                                    <div>
                                        <h2 className="text-xl font-semibold text-gray-900">
                                            Informasi Komunitas
                                        </h2>
                                        <p className="text-sm text-gray-500">
                                            Data komunitas Anda
                                        </p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <InputField
                                        label="Nama Komunitas"
                                        isRequired
                                        id="community_name"
                                        value={data.community.name}
                                        onChange={(e) =>
                                            setData('community', {
                                                ...data.community,
                                                name: e.target.value,
                                            })
                                        }
                                        error={errors['community.name']}
                                    />
                                    <InputField
                                        label="Jumlah Anggota"
                                        id="member_count"
                                        type="number"
                                        value={data.community.member_count}
                                        onChange={(e) =>
                                            setData('community', {
                                                ...data.community,
                                                member_count: e.target.value,
                                            })
                                        }
                                        error={errors['community.member_count']}
                                    />
                                </div>
                                <div>
                                    <label
                                        htmlFor="address"
                                        className="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Deskripsi Komunitas{' '}
                                        <span className="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        id="description"
                                        value={data.community.description}
                                        onChange={(e) =>
                                            setData('community', {
                                                ...data.community,
                                                description: e.target.value,
                                            })
                                        }
                                        rows={4}
                                        className={`w-full resize-none rounded-xl border-2 px-4 py-3 text-sm transition-all duration-200 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ${
                                            errors['community.description']
                                                ? 'border-red-300 bg-red-50'
                                                : 'border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white'
                                        }`}
                                        placeholder="Masukkan deskripsi komunitas Anda"
                                    />
                                    {errors['community.description'] && (
                                        <p className="mt-2 flex items-center text-sm text-red-600">
                                            <svg
                                                className="mr-1 h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth={2}
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            {errors['community.description']}
                                        </p>
                                    )}
                                </div>
                            </div>

                            {/* Submit Button */}
                            <div className="pt-6">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="group relative w-full transform rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 font-semibold text-white shadow-lg transition-all duration-200 hover:scale-[1.02] hover:from-emerald-700 hover:to-teal-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <span className="relative z-10 flex items-center justify-center">
                                        {processing ? (
                                            <>
                                                <svg
                                                    className="-ml-1 mr-3 h-5 w-5 animate-spin text-white"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <circle
                                                        className="opacity-25"
                                                        cx="12"
                                                        cy="12"
                                                        r="10"
                                                        stroke="currentColor"
                                                        strokeWidth="4"
                                                    ></circle>
                                                    <path
                                                        className="opacity-75"
                                                        fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                    ></path>
                                                </svg>
                                                Menyimpan...
                                            </>
                                        ) : (
                                            <>
                                                <svg
                                                    className="mr-2 h-5 w-5 transition-transform duration-200 group-hover:scale-110"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M5 13l4 4L19 7"
                                                    />
                                                </svg>
                                                Simpan Datasssssssssss
                                            </>
                                        )}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
}
