'use client';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Province } from '@/types/area/interface';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { router as Inertia } from '@inertiajs/react';
import {
    ArrowLeft,
    Camera,
    Eye,
    EyeOff,
    FileVideo,
    Info,
    Lock,
    MapPin,
    Upload,
    X,
} from 'lucide-react';

import { useState } from 'react';

interface PageProps {
    provinces: Province[];
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
            phone: string | null;
            province_id: number | null;
            city_id: number | null;
            district_id: number | null;
            address: string | null;
        };
    };
    onBack: () => void;
}

const EditProfilePage = ({ provinces, onBack, auth }: PageProps) => {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    const [showPasswordConfirmation, setShowPasswordConfirmation] =
        useState(false);
    const [showCurrentPassword, setShowCurrentPassword] = useState(false);

    const [formData, setFormData] = useState({
        name: auth.user?.name || '',
        phone: auth.user?.phone || '',
        address: auth.user?.address || '',
        province_id: auth.user?.province_id?.toString() || '',
        city_id: auth.user?.city_id?.toString() || '',
        district_id: auth.user?.district_id?.toString() || '',
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const [uploadedFiles, setUploadedFiles] = useState<File[]>([]);
    const selectedProvince = provinces.find(
        (p) => p.id.toString() === formData.province_id,
    );
    const cities = selectedProvince?.cities ?? [];
    const selectedCity = cities.find(
        (c) => c.id.toString() === formData.city_id,
    );
    const districts = selectedCity?.districts ?? [];
    const handleInputChange = (field: string, value: string) => {
        if (field === 'province_id') {
            setFormData((prev) => ({
                ...prev,
                province_id: value,
                city_id: '',
                district_id: '',
            }));
        } else if (field === 'city_id') {
            setFormData((prev) => ({
                ...prev,
                city_id: value,
                district_id: '',
            }));
        } else {
            setFormData((prev) => ({ ...prev, [field]: value }));
        }
    };

    const handleFileUpload = (files: FileList | null) => {
        if (files) {
            const newFiles = Array.from(files).filter((file) => {
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar. Maksimal 10MB.`);
                    return false;
                }
                return true;
            });
            setUploadedFiles(newFiles);
        }
    };

    const removeFile = (index: number) => {
        setUploadedFiles((prev) => prev.filter((_, i) => i !== index));
    };

    const validateForm = () => {
        const requiredFields = [
            { field: 'name', name: 'Nama Lengkap' },
            { field: 'phone', name: 'Nomor Telepon' },
            { field: 'address', name: 'Alamat' },
            { field: 'province_id', name: 'Provinsi' },
            { field: 'city_id', name: 'Kota/Kabupaten' },
            { field: 'district_id', name: 'Kecamatan' },
        ];

        for (const { field, name } of requiredFields) {
            if (!formData[field as keyof typeof formData]) {
                alert(`${name} wajib diisi.`);
                return false;
            }
        }

        return true;
    };
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        if (!validateForm()) return;

        setIsSubmitting(true);

        const data = new FormData();
        data.append('name', formData.name);
        data.append('phone', formData.phone);
        data.append('address', formData.address);
        data.append('province_id', formData.province_id);
        data.append('city_id', formData.city_id);
        data.append('district_id', formData.district_id);

        if (uploadedFiles.length > 0) {
            data.append('profile_url', uploadedFiles[0]);
        }

        if (formData.password) {
            data.append('current_password', formData.current_password);
            data.append('password', formData.password);
            data.append(
                'password_confirmation',
                formData.password_confirmation,
            );
        }

        Inertia.post('/update-profile', data, {
            forceFormData: true,
            onStart: () => setIsSubmitting(true),
            onFinish: () => setIsSubmitting(false),
            onSuccess: () => {
                setFormData({
                    name: '',
                    phone: '',
                    address: '',
                    province_id: '',
                    city_id: '',
                    district_id: '',
                    current_password: '',
                    password: '',
                    password_confirmation: '',
                });
                setUploadedFiles([]);
            },
            onError: (errors) => {
                console.error('Form errors:', errors);
            },
        });
    };

    return (
        <div className="px-4 py-8 mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div className="flex items-center justify-between mb-8">
                <Button
                    variant="ghost"
                    onClick={onBack}
                    className="text-gray-600 hover:text-sky-600"
                    disabled={isSubmitting}
                >
                    <ArrowLeft size={20} className="mr-2" />
                    Kembali ke Profil Saya
                </Button>
                <div className="space-x-1 text-sm text-gray-500">
                    <span className="cursor-pointer hover:underline">Home</span>
                    <span className="cursor-pointer hover:underline">
                        / Profil
                    </span>{' '}
                    /
                    <span className="font-medium text-gray-700">
                        Edit Profil
                    </span>
                </div>
            </div>

            <form onSubmit={handleSubmit} className="space-y-8">
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div className="flex flex-col space-y-6">
                        <Card className="flex flex-col h-full">
                            <CardHeader>
                                <CardTitle className="flex items-center">
                                    <Info
                                        size={20}
                                        className="mr-2 text-sky-600"
                                    />
                                    Informasi Pengguna
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="flex-1 space-y-6">
                                <div className="space-y-2">
                                    <Label htmlFor="name">
                                        Nama Lengkap{' '}
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <Input
                                        id="name"
                                        placeholder="Masukkan nama lengkap Anda"
                                        value={formData.name}
                                        onChange={(e) =>
                                            handleInputChange(
                                                'name',
                                                e.target.value,
                                            )
                                        }
                                        required
                                        disabled={isSubmitting}
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="phone">
                                        Nomor Telepon{' '}
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <Input
                                        id="phone"
                                        placeholder="Masukkan nomor telepon Anda"
                                        value={formData.phone}
                                        type="phone"
                                        onChange={(e) =>
                                            handleInputChange(
                                                'phone',
                                                e.target.value,
                                            )
                                        }
                                        required
                                        disabled={isSubmitting}
                                    />
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Alamat Pengguna - Kanan */}
                    <div className="flex flex-col space-y-6">
                        <Card className="flex flex-col h-full">
                            <CardHeader>
                                <CardTitle className="flex items-center">
                                    <MapPin
                                        size={20}
                                        className="mr-2 text-sky-600"
                                    />
                                    Alamat Pengguna
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="flex-1 space-y-6">
                                <div className="space-y-4">
                                    <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                                        <div className="space-y-2">
                                            <Label>
                                                Provinsi{' '}
                                                <span className="text-red-500">
                                                    *
                                                </span>
                                            </Label>
                                            <Select
                                                value={formData.province_id}
                                                onValueChange={(v) =>
                                                    handleInputChange(
                                                        'province_id',
                                                        v,
                                                    )
                                                }
                                                disabled={isSubmitting}
                                            >
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih provinsi" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    {provinces.map((prov) => (
                                                        <SelectItem
                                                            key={prov.id}
                                                            value={prov.id.toString()}
                                                        >
                                                            {prov.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectContent>
                                            </Select>
                                        </div>

                                        <div className="space-y-2">
                                            <Label>
                                                Kota/Kabupaten{' '}
                                                <span className="text-red-500">
                                                    *
                                                </span>
                                            </Label>
                                            <Select
                                                value={formData.city_id}
                                                onValueChange={(v) =>
                                                    handleInputChange(
                                                        'city_id',
                                                        v,
                                                    )
                                                }
                                                disabled={
                                                    !cities.length ||
                                                    isSubmitting
                                                }
                                            >
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih kota" />
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
                                        </div>

                                        <div className="space-y-2">
                                            <Label>
                                                Kecamatan{' '}
                                                <span className="text-red-500">
                                                    *
                                                </span>
                                            </Label>
                                            <Select
                                                value={formData.district_id}
                                                onValueChange={(v) =>
                                                    handleInputChange(
                                                        'district_id',
                                                        v,
                                                    )
                                                }
                                                disabled={
                                                    !districts.length ||
                                                    isSubmitting
                                                }
                                            >
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih kecamatan" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    {districts.map((d) => (
                                                        <SelectItem
                                                            key={d.id}
                                                            value={d.id.toString()}
                                                        >
                                                            {d.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectContent>
                                            </Select>
                                        </div>
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="address">
                                            Alamat Lengkap{' '}
                                            <span className="text-red-500">
                                                *
                                            </span>
                                        </Label>
                                        <Textarea
                                            id="address"
                                            placeholder="Masukkan detail alamat Anda"
                                            rows={3}
                                            value={formData.address}
                                            onChange={(e) =>
                                                handleInputChange(
                                                    'address',
                                                    e.target.value,
                                                )
                                            }
                                            required
                                            disabled={isSubmitting}
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div className="flex flex-col space-y-6">
                        <Card className="flex flex-col h-full">
                            <CardHeader>
                                <CardTitle className="flex items-center">
                                    <Lock
                                        size={20}
                                        className="mr-2 text-sky-600"
                                    />
                                    Kata Sandi
                                </CardTitle>
                                <p className="text-sm text-muted-foreground">
                                    Kosongkan jika tidak ingin mengubah password
                                </p>
                            </CardHeader>
                            <CardContent className="flex-1 space-y-6">
                                <div className="space-y-2">
                                    <Label htmlFor="current_password">
                                        Password Saat Ini{' '}
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <div className="relative">
                                        <Input
                                            id="current_password"
                                            type={
                                                showCurrentPassword
                                                    ? 'text'
                                                    : 'password'
                                            }
                                            placeholder="Masukkan password lama Anda"
                                            value={formData.current_password}
                                            onChange={(e) =>
                                                handleInputChange(
                                                    'current_password',
                                                    e.target.value,
                                                )
                                            }
                                            disabled={isSubmitting}
                                            className="pr-10"
                                        />
                                        <button
                                            type="button"
                                            className="absolute inset-y-0 flex items-center text-gray-500 right-2"
                                            onClick={() =>
                                                setShowCurrentPassword(
                                                    !showCurrentPassword,
                                                )
                                            }
                                        >
                                            {showCurrentPassword ? (
                                                <EyeOff size={18} />
                                            ) : (
                                                <Eye size={18} />
                                            )}
                                        </button>
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="password">
                                        Password Baru
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <div className="relative">
                                        <Input
                                            id="password"
                                            type={
                                                showPassword
                                                    ? 'text'
                                                    : 'password'
                                            }
                                            placeholder="Masukkan password baru Anda"
                                            value={formData.password}
                                            onChange={(e) =>
                                                handleInputChange(
                                                    'password',
                                                    e.target.value,
                                                )
                                            }
                                            disabled={isSubmitting}
                                            className="pr-10"
                                        />
                                        <button
                                            type="button"
                                            className="absolute inset-y-0 flex items-center text-gray-500 right-2"
                                            onClick={() =>
                                                setShowPassword(!showPassword)
                                            }
                                        >
                                            {showPassword ? (
                                                <EyeOff size={18} />
                                            ) : (
                                                <Eye size={18} />
                                            )}
                                        </button>
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="password_confirmation">
                                        Konfirmasi Password Baru
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <div className="relative">
                                        <Input
                                            id="password_confirmation"
                                            placeholder="Masukkan ulang password baru"
                                            type={
                                                showPasswordConfirmation
                                                    ? 'text'
                                                    : 'password'
                                            }
                                            value={
                                                formData.password_confirmation
                                            }
                                            onChange={(e) =>
                                                handleInputChange(
                                                    'password_confirmation',
                                                    e.target.value,
                                                )
                                            }
                                            disabled={isSubmitting}
                                            className="pr-10"
                                        />
                                        <button
                                            type="button"
                                            className="absolute inset-y-0 flex items-center text-gray-500 right-2"
                                            onClick={() =>
                                                setShowPasswordConfirmation(
                                                    !showPasswordConfirmation,
                                                )
                                            }
                                        >
                                            {showPasswordConfirmation ? (
                                                <EyeOff size={18} />
                                            ) : (
                                                <Eye size={18} />
                                            )}
                                        </button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                    <div className="flex flex-col space-y-6">
                        <Card className="flex flex-col h-full">
                            <CardHeader>
                                <CardTitle className="flex items-center">
                                    <Camera
                                        size={20}
                                        className="mr-2 text-sky-600"
                                    />
                                    Foto Profil Pengguna
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div className="p-8 text-center transition-colors border-2 border-gray-300 border-dashed rounded-lg hover:border-sky-400">
                                    <input
                                        type="file"
                                        accept="image/*,video/*"
                                        onChange={(e) =>
                                            handleFileUpload(e.target.files)
                                        }
                                        className="hidden"
                                        id="file-upload"
                                        disabled={isSubmitting}
                                    />
                                    <label
                                        htmlFor="file-upload"
                                        className={`cursor-pointer ${isSubmitting ? 'cursor-not-allowed opacity-50' : ''}`}
                                    >
                                        <Upload
                                            size={48}
                                            className="mx-auto mb-4 text-gray-400"
                                        />
                                        <p className="mb-2 text-lg font-medium text-gray-700">
                                            Klik untuk mengunggah atau seret
                                            file ke sini
                                        </p>
                                        <p className="text-sm text-gray-500">
                                            PNG, JPG, JPEG hingga 2MB
                                        </p>
                                    </label>
                                </div>

                                {/* File Previews */}
                                {uploadedFiles.length > 0 && (
                                    <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
                                        {uploadedFiles.map((file, index) => (
                                            <div
                                                key={index}
                                                className="relative group"
                                            >
                                                <div className="flex items-center justify-center bg-gray-100 rounded-lg aspect-square">
                                                    {file.type.startsWith(
                                                        'image/',
                                                    ) ? (
                                                        <img
                                                            src={URL.createObjectURL(
                                                                file,
                                                            )}
                                                            alt={file.name}
                                                            className="object-cover w-full h-full rounded-lg"
                                                        />
                                                    ) : (
                                                        <FileVideo
                                                            size={24}
                                                            className="text-gray-400"
                                                        />
                                                    )}
                                                </div>
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        removeFile(index)
                                                    }
                                                    className="absolute flex items-center justify-center w-6 h-6 text-white transition-opacity bg-red-500 rounded-full opacity-0 -right-2 -top-2 group-hover:opacity-100"
                                                    disabled={isSubmitting}
                                                >
                                                    <X size={14} />
                                                </button>
                                                <p className="mt-1 text-xs text-gray-500 truncate">
                                                    {file.name}
                                                </p>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <div className="flex flex-col justify-start gap-4 p-3 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex-row">
                    <Button
                        type="button"
                        variant="outline"
                        onClick={onBack}
                        className="sm:w-auto"
                        disabled={isSubmitting}
                    >
                        Batal
                    </Button>
                    <Button
                        type="submit"
                        className="bg-sky-600 hover:bg-sky-700 sm:w-auto"
                        size="lg"
                        disabled={isSubmitting}
                    >
                        {isSubmitting ? 'Mengirim...' : 'Update'}
                    </Button>
                </div>
            </form>
        </div>
    );
};

export default EditProfilePage;
