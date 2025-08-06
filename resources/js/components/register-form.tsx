import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

type UserType = 'citizen' | 'community';
export function RegisterForm({
    className,
    ...props
}: React.ComponentProps<'div'>) {
    const [userType, setUserType] = useState<UserType>('citizen');
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        user_type: 'citizen' as UserType,
    });

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;

        if (name === 'name') {
            setData('name', value);
        } else if (name === 'email') {
            setData('email', value);
        } else if (name === 'password') {
            setData('password', value);
        } else if (name === 'password_confirmation') {
            setData('password_confirmation', value);
        }
    };

    const handleUserTypeSelect = (type: UserType) => {
        setUserType(type);
        setData('user_type', type);
    };
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route('register'), {
            onSuccess: () => {
                reset('password', 'password_confirmation');
            },
            onError: () => {},
        });
    };
    return (
        <div className={cn('flex flex-col gap-6', className)} {...props}>
            <Card className="overflow-hidden">
                <CardContent className="grid p-0 md:grid-cols-2">
                    <form className="p-6 md:p-8" onSubmit={handleSubmit}>
                        <div className="flex flex-col gap-6">
                            <div className="flex flex-col items-center text-center">
                                <h1 className="text-2xl font-bold text-green-900">
                                    Buat Akun
                                </h1>
                                <p className="text-balance font-bold text-green-700">
                                    Daftar untuk akun SobatBumi anda
                                </p>
                            </div>

                            {/* User Type Selection */}
                            <div className="grid gap-2">
                                <Label>Pilih Jenis Akun</Label>
                                <div className="grid grid-cols-2 gap-2">
                                    <Button
                                        type="button"
                                        variant={
                                            userType === 'citizen'
                                                ? 'default'
                                                : 'outline'
                                        }
                                        className={cn(
                                            'w-full',
                                            userType === 'citizen'
                                                ? 'bg-green-800 text-white hover:bg-green-700'
                                                : 'border-green-300 text-green-700 hover:bg-green-50',
                                        )}
                                        onClick={() =>
                                            handleUserTypeSelect('citizen')
                                        }
                                    >
                                        Warga
                                    </Button>
                                    <Button
                                        type="button"
                                        variant={
                                            userType === 'community'
                                                ? 'default'
                                                : 'outline'
                                        }
                                        className={cn(
                                            'w-full',
                                            userType === 'community'
                                                ? 'bg-green-800 text-white hover:bg-green-700'
                                                : 'border-green-300 text-green-700 hover:bg-green-50',
                                        )}
                                        onClick={() =>
                                            handleUserTypeSelect('community')
                                        }
                                    >
                                        Komunitas
                                    </Button>
                                </div>
                            </div>

                            {/* Name Field */}
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nama</Label>
                                <Input
                                    id="name"
                                    type="text"
                                    name="name"
                                    placeholder="Sobat Bumi"
                                    required
                                    value={data.name}
                                    onChange={handleInputChange}
                                    className="focus:border-green-500 focus:ring-green-500"
                                />
                                {errors.name && (
                                    <p className="mt-1 text-sm text-red-500">
                                        {errors.name}
                                    </p>
                                )}
                            </div>

                            {/* Email Field */}
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="user@example.com"
                                    required
                                    value={data.email}
                                    onChange={handleInputChange}
                                    className="focus:border-green-500 focus:ring-green-500"
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-500">
                                        {errors.email}
                                    </p>
                                )}
                            </div>

                            {/* Password Field */}
                            <div className="grid gap-2">
                                <Label htmlFor="password">Password</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Masukkan password"
                                    required
                                    value={data.password}
                                    onChange={handleInputChange}
                                    className="focus:border-green-500 focus:ring-green-500"
                                />
                                {errors.password && (
                                    <p className="mt-1 text-sm text-red-500">
                                        {errors.password}
                                    </p>
                                )}
                            </div>

                            {/* Confirm Password Field */}
                            <div className="grid gap-2">
                                <Label htmlFor="password_confirmation">
                                    Konfirmasi Password
                                </Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    placeholder="Masukkan konfirmasi password"
                                    value={data.password_confirmation}
                                    onChange={handleInputChange}
                                    className="focus:border-green-500 focus:ring-green-500"
                                />
                                {errors.password_confirmation && (
                                    <p className="mt-1 text-sm text-red-500">
                                        {errors.password_confirmation}
                                    </p>
                                )}
                            </div>

                            {/* Submit Button */}
                            <Button
                                type="submit"
                                className="w-full bg-green-800 text-white hover:bg-green-700 disabled:opacity-50"
                                disabled={processing}
                            >
                                {processing
                                    ? 'Membuat akun...'
                                    : `Daftar sebagai ${userType === 'citizen' ? 'Warga' : 'Komunitas'}`}
                            </Button>

                            <div className="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
                                <span className="relative z-10 bg-background px-2 text-muted-foreground">
                                    Atau
                                </span>
                            </div>

                            {/* Social login buttons */}
                            {/* <div className="grid grid-cols-3 gap-4">
                                <Button
                                    variant="outline"
                                    className="w-full hover:bg-green-50"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        className="h-4 w-4"
                                    >
                                        <path
                                            d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701"
                                            fill="currentColor"
                                        />
                                    </svg>
                                    <span className="sr-only">
                                        Sign up with Apple
                                    </span>
                                </Button>
                                <Button
                                    variant="outline"
                                    className="w-full hover:bg-green-50"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        className="h-4 w-4"
                                    >
                                        <path
                                            d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                    <span className="sr-only">
                                        Sign up with Google
                                    </span>
                                </Button>
                                <Button
                                    variant="outline"
                                    className="w-full hover:bg-green-50"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        className="h-4 w-4"
                                    >
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                    <span className="sr-only">
                                        Sign up with Facebook
                                    </span>
                                </Button>
                            </div> */}

                            <div className="text-center text-sm">
                                Sudah memiliki akun?{' '}
                                <Link
                                    href={route('login')}
                                    className="text-green-700 underline underline-offset-4 hover:text-green-900"
                                >
                                    Masuk
                                </Link>
                            </div>
                        </div>
                    </form>
                    <div className="relative hidden bg-muted md:block">
                        <img
                            src="/Sobumi-reg.png"
                            alt="SobatBumi Registration"
                            className="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                        />
                    </div>
                </CardContent>
            </Card>
            <div className="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
                By clicking continue, you agree to our{' '}
                <a href="#" className="text-green-700 hover:text-green-900">
                    Terms of Service
                </a>{' '}
                and{' '}
                <a href="#" className="text-green-700 hover:text-green-900">
                    Privacy Policy
                </a>
                .
            </div>
        </div>
    );
}
