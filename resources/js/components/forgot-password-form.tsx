import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { PageProps } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/react';
import type React from 'react';

export function ForgotPasswordForm({
    className,
    ...props
}: React.ComponentProps<'div'>) {
    const { props: pageProps } = usePage<PageProps>();
    const status = pageProps.flash?.status;
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    // Handler untuk submit form
    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <div className={cn('flex flex-col gap-6', className)} {...props}>
            <Card className="overflow-hidden">
                <CardContent className="grid p-0 md:grid-cols-2">
                    <form className="p-6 md:p-8" onSubmit={handleSubmit}>
                        <div className="flex flex-col gap-6">
                            <div className="flex flex-col items-center text-center">
                                <h1 className="text-2xl font-bold text-green-900">
                                    Lupa Password?
                                </h1>
                                <p className="text-balance text-sm text-green-700">
                                    Jangan khawatir. Masukkan email Anda dan
                                    kami akan mengirimkan link untuk mereset
                                    password Anda.
                                </p>
                            </div>

                            {/* Tampilkan pesan sukses jika ada (logika ini sekarang aman) */}
                            {status && (
                                <div className="mb-4 rounded-md bg-green-50 p-3 text-sm font-medium text-green-600">
                                    {status}
                                </div>
                            )}

                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="m@example.com"
                                    required
                                    autoFocus
                                    value={data.email}
                                    onChange={(e) =>
                                        setData('email', e.target.value)
                                    }
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-500">
                                        {errors.email}
                                    </p>
                                )}
                            </div>

                            <div className="flex items-center gap-4">
                                <Button
                                    asChild
                                    variant="link"
                                    className="h-auto p-0 text-sm"
                                >
                                    <Link href={route('login')}>
                                        Kembali ke Login
                                    </Link>
                                </Button>
                                <Button
                                    type="submit"
                                    className="w-full bg-green-800 text-white hover:bg-green-700"
                                    disabled={processing}
                                >
                                    {processing
                                        ? 'Mengirim...'
                                        : 'Kirim Link Reset'}
                                </Button>
                            </div>
                        </div>
                    </form>
                    <div className="relative hidden bg-muted md:block">
                        <img
                            src="/SOBUMI.png"
                            alt="Image"
                            className="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                        />
                    </div>
                </CardContent>
            </Card>
            <div className="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
                By clicking continue, you agree to our{' '}
                <a href="#">Terms of Service</a> and{' '}
                <a href="#">Privacy Policy</a>.
            </div>
        </div>
    );
}
