import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import type React from 'react';

export function LoginForm({
    className,
    ...props
}: React.ComponentProps<'div'>) {
    // const { data, setData, post, processing, errors, reset } = useForm({
    //     email: '',
    //     password: '',
    //     remember: false,
    // });
    const { data, setData, post, processing, errors, reset } = useForm<{
        email: string;
        password: string;
        remember: boolean;
    }>({
        email: '',
        password: '',
        remember: false,
    });

    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setData(
            e.target.name as 'email' | 'password' | 'remember',
            e.target.type === 'checkbox' ? e.target.checked : e.target.value,
        );
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route('login'), {
            onSuccess: () => reset('password'),
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
                                    Selamat Datang
                                </h1>
                                <p className="font-bold text-green-700 text-balance">
                                    Masuk ke Akun KawanBumi
                                </p>
                            </div>
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    placeholder="user@example.com"
                                    required
                                    autoFocus
                                    value={data.email}
                                    onChange={handleInputChange}
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-500">
                                        {errors.email}
                                    </p>
                                )}
                            </div>
                            <div className="grid gap-2">
                                <div className="flex items-center">
                                    <Label htmlFor="password">Password</Label>
                                    <Link
                                        href={route('password.request')}
                                        className="ml-auto text-sm underline-offset-2 hover:underline"
                                    >
                                        Lupa password?
                                    </Link>
                                </div>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="********"
                                    value={data.password}
                                    onChange={handleInputChange}
                                />
                                {errors.password && (
                                    <p className="mt-1 text-sm text-red-500">
                                        {errors.password}
                                    </p>
                                )}
                            </div>

                            {/* <div className="flex items-center">
                  <input
                      type="checkbox"
                      id="remember"
                      name="remember"
                      checked={data.remember}
                      onChange={(e) => setData('remember', e.target.checked)}
                      className="w-4 h-4 border-gray-300 rounded text-emerald-600 focus:ring-emerald-500"
                  />
                  <Label htmlFor="remember" className="block ml-2 text-sm text-gray-900">Remember me</Label>
              </div> */}

                            <Button
                                type="submit"
                                className="w-full text-white bg-green-800 hover:bg-green-700"
                                disabled={processing}
                            >
                                {processing ? 'Masuk...' : 'Masuk'}
                            </Button>

                            <div className="relative text-sm text-center after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
                                <span className="relative z-10 px-2 bg-background text-muted-foreground">
                                    Atau
                                </span>
                            </div>

                            {/* <div className="grid grid-cols-1 gap-4">
                                <a
                                    href={route('auth.google.redirect')}
                                    className={cn(
                                        'inline-flex h-9 w-full items-center justify-center whitespace-nowrap rounded-md border border-input bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50',
                                    )}
                                >
                                    <svg
                                        className="w-4 h-4 mr-2"
                                        xmlns="http://www.w.org/2000/svg"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"
                                            fill="currentColor"
                                        />
                                    </svg>
                                    <span>Lanjutkan dengan Google</span>
                                </a>
                            </div> */}

                            <div className="text-sm text-center">
                                Belum memiliki akun?{' '}
                                <Link
                                    href={route('register')}
                                    className="underline underline-offset-4"
                                >
                                    Daftar
                                </Link>
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
