import { ForgotPasswordForm } from '@/components/forgot-password-form';

export default function ForgotPasswordPage() {
    return (
        <div className="flex flex-col items-center justify-center p-6 min-h-svh bg-muted md:p-10">
            <div className="w-full max-w-sm md:max-w-3xl">
                <ForgotPasswordForm />
            </div>
        </div>
    );
}
