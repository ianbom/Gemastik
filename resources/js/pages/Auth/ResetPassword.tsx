import { ResetPasswordForm } from '@/components/reset-password-form';

interface ResetPasswordPageProps {
    token: string;
    email: string;
}

export default function ResetPasswordPage({ token, email }: ResetPasswordPageProps) {
    return (
        <div className="flex flex-col items-center justify-center p-6 min-h-svh bg-muted md:p-10">
            <div className="w-full max-w-sm md:max-w-3xl">
                <ResetPasswordForm token={token} email={email} />
            </div>
        </div>
    );
}
