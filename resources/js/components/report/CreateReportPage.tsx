import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
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
import { Province } from '@/types/area/interface';
import { router as Inertia } from '@inertiajs/react';
import {
    ArrowLeft,
    Camera,
    FileVideo,
    Info,
    MapPin,
    Upload,
    X,
} from 'lucide-react';

import { lazy, useState } from 'react';

const MapPicker = lazy(() => import('../map/MapPicker'));
interface PageProps {
    provinces: Province[];
    onBack: () => void;
}

const CreateReportPage = ({ provinces, onBack }: PageProps) => {
    const [location, setLocation] = useState<{
        latitude: number;
        longitude: number;
    } | null>(null);
    const [loading, setLoading] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const [formData, setFormData] = useState({
        title: '',
        description: '',
        category: '',
        address: '',
        province_id: '',
        city_id: '',
        district_id: '',
    });

    const [uploadedFiles, setUploadedFiles] = useState<File[]>([]);

    const handleUseCurrentLocation = () => {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung geolokasi');
            return;
        }

        setLoading(true);

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const { latitude, longitude } = position.coords;
                setLocation({ latitude, longitude });
                setLoading(false);
            },
            (error) => {
                console.error('Gagal dapat lokasi:', error);
                alert(
                    'Tidak bisa mendapatkan lokasi. Pastikan izin lokasi aktif.',
                );
                setLoading(false);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000,
            },
        );
    };

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
        }
        // kalau kota berubah, kosongkan district
        else if (field === 'city_id') {
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
                // Validasi ukuran file (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar. Maksimal 10MB.`);
                    return false;
                }
                return true;
            });
            setUploadedFiles((prev) => [...prev, ...newFiles]);
        }
    };

    const removeFile = (index: number) => {
        setUploadedFiles((prev) => prev.filter((_, i) => i !== index));
    };

    const validateForm = () => {
        const requiredFields = [
            { field: 'title', name: 'Judul Laporan' },
            { field: 'description', name: 'Deskripsi' },
            { field: 'category', name: 'Kategori' },
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

        if (!location) {
            alert(
                'Lokasi wajib dipilih. Silakan gunakan GPS atau pilih di peta.',
            );
            return false;
        }

        return true;
    };

    // const handleSubmit = async (e: React.FormEvent) => {
    //     e.preventDefault();

    //     if (!validateForm()) {
    //         return;
    //     }

    //     setIsSubmitting(true);

    //     const data = new FormData();
    //     data.append('title', formData.title);
    //     data.append('description', formData.description);
    //     data.append('category', formData.category);
    //     data.append('address', formData.address);
    //     data.append('province_id', formData.province_id);
    //     data.append('city_id', formData.city_id);
    //     data.append('district_id', formData.district_id);

    //     if (location) {
    //         data.append('latitude', location.latitude.toString());
    //         data.append('longitude', location.longitude.toString());
    //     }

    //     uploadedFiles.forEach((file) => {
    //         data.append('media[]', file);
    //     });

    //     try {
    //         Inertia.post('/reports', data, {
    //             forceFormData: true,
    //             onSuccess: () => {
    //                 setFormData({
    //                     title: '',
    //                     description: '',
    //                     category: '',
    //                     address: '',
    //                     province_id: '',
    //                     city_id: '',
    //                     district_id: '',
    //                 });
    //                 setLocation(null);
    //                 setUploadedFiles([]);
    //                 // Inertia.visit('/report');
    //             },
    //             onError: (errors) => {
    //                 console.error('Form errors:', errors);
    //             },
    //         });
    //     } catch (error: any) {
    //         console.error('Error submitting report:', error);

    //         if (error.response) {
    //             const { status, data } = error.response;

    //             if (status === 422) {
    //                 const errors = data.errors || {};
    //                 const errorMessages = Object.values(errors).flat();
    //                 alert('Validasi gagal:\n' + errorMessages.join('\n'));
    //             } else if (status === 401) {
    //                 alert('Anda belum login. Silakan login terlebih dahulu.');
    //             } else if (status === 413) {
    //                 alert(
    //                     'File yang diunggah terlalu besar. Maksimal 10MB per file.',
    //                 );
    //             } else if (status === 500) {
    //                 alert(
    //                     'Terjadi kesalahan pada server. Silakan coba lagi nanti.',
    //                 );
    //             } else {
    //                 alert(
    //                     'Terjadi kesalahan: ' +
    //                         (data.message || 'Unknown error'),
    //                 );
    //             }
    //         } else if (error.request) {
    //             alert(
    //                 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.',
    //             );
    //         } else {
    //             alert(
    //                 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi.',
    //             );
    //         }
    //     } finally {
    //         setIsSubmitting(false);
    //     }
    // };
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        setIsSubmitting(true);

        const data = new FormData();
        data.append('title', formData.title);
        data.append('description', formData.description);
        data.append('category', formData.category);
        data.append('address', formData.address);
        data.append('province_id', formData.province_id);
        data.append('city_id', formData.city_id);
        data.append('district_id', formData.district_id);

        if (location) {
            data.append('latitude', location.latitude.toString());
            data.append('longitude', location.longitude.toString());
        }

        uploadedFiles.forEach((file) => {
            data.append('media[]', file);
        });

        Inertia.post('/reports', data, {
            forceFormData: true,
            onStart: () => setIsSubmitting(true),
            onFinish: () => setIsSubmitting(false),
            onSuccess: () => {
                setFormData({
                    title: '',
                    description: '',
                    category: '',
                    address: '',
                    province_id: '',
                    city_id: '',
                    district_id: '',
                });
                setLocation(null);
                setUploadedFiles([]);
            },
            onError: (errors) => {
                console.error('Form errors:', errors);
            },
        });
    };
    return (
        <div className="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <div className="mb-8 flex items-center justify-between">
                {' '}
                <Button
                    variant="ghost"
                    onClick={onBack}
                    className="text-gray-600 hover:text-emerald-600"
                    disabled={isSubmitting}
                >
                    <ArrowLeft size={20} className="mr-2" />
                    Kembali ke Daftar Laporan
                </Button>
                <div className="space-x-1 text-sm text-gray-500">
                    <span className="cursor-pointer hover:underline">Home</span>
                    <span className="cursor-pointer hover:underline">
                        / Laporan
                    </span>{' '}
                    /
                    <span className="font-medium text-gray-700">
                        Buat Laporan
                    </span>
                </div>
            </div>

            <form onSubmit={handleSubmit} className="space-y-8">
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-4">
                    <div className="space-y-6 lg:col-span-2">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center">
                                    <Info
                                        size={20}
                                        className="mr-2 text-emerald-600"
                                    />
                                    Informasi Dasar
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div className="space-y-2">
                                    <Label htmlFor="title">
                                        Judul Laporan{' '}
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <Input
                                        id="title"
                                        placeholder="Masukkan judul laporan yang jelas dan deskriptif"
                                        value={formData.title}
                                        onChange={(e) =>
                                            handleInputChange(
                                                'title',
                                                e.target.value,
                                            )
                                        }
                                        required
                                        disabled={isSubmitting}
                                    />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="description">
                                        Deskripsi Masalah Detail{' '}
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <Textarea
                                        id="description"
                                        placeholder="Jelaskan masalah lingkungan yang Anda temukan secara detail..."
                                        rows={5}
                                        value={formData.description}
                                        onChange={(e) =>
                                            handleInputChange(
                                                'description',
                                                e.target.value,
                                            )
                                        }
                                        required
                                        disabled={isSubmitting}
                                    />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="category">
                                        Kategori Isu{' '}
                                        <span className="text-red-500">*</span>
                                    </Label>
                                    <Select
                                        value={formData.category}
                                        onValueChange={(value) =>
                                            handleInputChange('category', value)
                                        }
                                        disabled={isSubmitting}
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih kategori masalah" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="sampah-plastik">
                                                Sampah Plastik
                                            </SelectItem>
                                            <SelectItem value="pencemaran-air">
                                                Pencemaran Air
                                            </SelectItem>
                                            <SelectItem value="pencemaran-udara">
                                                Pencemaran Udara
                                            </SelectItem>
                                            <SelectItem value="pencemaran-tanah">
                                                Pencemaran Tanah
                                            </SelectItem>
                                            <SelectItem value="limbah-industri">
                                                Limbah Industri
                                            </SelectItem>
                                            <SelectItem value="emisi-gas-rumah-kaca">
                                                Emisi Gas Rumah Kaca
                                            </SelectItem>
                                            <SelectItem value="penggundulan-kebakaran-hutan">
                                                Penggundulan / Kebakaran Hutan
                                            </SelectItem>
                                            <SelectItem value="naiknya-permukaan-air-laut">
                                                Naiknya Permukaan Air Laut
                                            </SelectItem>
                                            <SelectItem value="limbah-pertanian-peternakan">
                                                Limbah Pertanian / Peternakan
                                            </SelectItem>
                                            <SelectItem value="lainnya">
                                                Lainnya
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center">
                                    <Camera
                                        size={20}
                                        className="mr-2 text-emerald-600"
                                    />
                                    Unggah Foto/Video
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div className="rounded-lg border-2 border-dashed border-gray-300 p-8 text-center transition-colors hover:border-emerald-400">
                                    <input
                                        type="file"
                                        multiple
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
                                            PNG, JPG, GIF, MP4 hingga 10MB
                                        </p>
                                    </label>
                                </div>

                                {/* File Previews */}
                                {uploadedFiles.length > 0 && (
                                    <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
                                        {uploadedFiles.map((file, index) => (
                                            <div
                                                key={index}
                                                className="group relative"
                                            >
                                                <div className="flex aspect-square items-center justify-center rounded-lg bg-gray-100">
                                                    {file.type.startsWith(
                                                        'image/',
                                                    ) ? (
                                                        <img
                                                            src={URL.createObjectURL(
                                                                file,
                                                            )}
                                                            alt={file.name}
                                                            className="h-full w-full rounded-lg object-cover"
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
                                                    className="absolute -right-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white opacity-0 transition-opacity group-hover:opacity-100"
                                                    disabled={isSubmitting}
                                                >
                                                    <X size={14} />
                                                </button>
                                                <p className="mt-1 truncate text-xs text-gray-500">
                                                    {file.name}
                                                </p>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </div>

                    <div className="col-span-2 space-y-6">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center">
                                    <MapPin
                                        size={20}
                                        className="mr-2 text-emerald-600"
                                    />
                                    Lokasi Masalah
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                {/* <div className="h-64 bg-gray-100 border-2 border-gray-300 border-dashed rounded-lg"> */}
                                <div className="h-64 overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-100">
                                    <MapPicker
                                        onChange={(pos) => setLocation(pos)}
                                        location={location}
                                        disabled={isSubmitting}
                                    />
                                </div>

                                <div className="text-center">
                                    <p className="text-sm text-gray-600">
                                        Lokasi terpilih:{' '}
                                        {location ? (
                                            <span className="font-medium text-emerald-600">
                                                {location.latitude},{' '}
                                                {location.longitude}
                                            </span>
                                        ) : (
                                            <span className="text-red-500">
                                                Belum dipilih
                                            </span>
                                        )}
                                    </p>
                                </div>
                                <Button
                                    type="button"
                                    variant="outline"
                                    className="w-full border-emerald-200 text-emerald-600 hover:bg-emerald-50"
                                    onClick={handleUseCurrentLocation}
                                    disabled={loading || isSubmitting}
                                >
                                    <MapPin size={16} className="mr-2" />
                                    {loading
                                        ? 'Mengambil lokasi...'
                                        : 'Gunakan Lokasi Saya Sekarang (GPS)'}
                                </Button>

                                <div className="space-y-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="address">
                                            Alamat Lengkap{' '}
                                            <span className="text-red-500">
                                                *
                                            </span>
                                        </Label>
                                        <Textarea
                                            id="address"
                                            placeholder="Masukkan alamat lengkap lokasi masalah"
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
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
                <div className="flex flex-col justify-start gap-4 rounded-lg border border-gray-200 bg-white p-3 shadow-sm sm:flex-row">
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
                        className="bg-emerald-600 hover:bg-emerald-700 sm:w-auto"
                        size="lg"
                        disabled={isSubmitting}
                    >
                        {isSubmitting ? 'Mengirim...' : 'Kirim Laporan'}
                    </Button>
                </div>
            </form>
        </div>
    );
};

export default CreateReportPage;
