import { InputHTMLAttributes } from 'react';
interface InputFieldProps extends InputHTMLAttributes<HTMLInputElement> {
    label: string;
    error?: string;
    isRequired?: boolean;
}

export default function InputField({
    label,
    error,
    isRequired = false,
    ...props
}: InputFieldProps) {
    const showRed = isRequired && (!props.value || error);
    return (
        <div>
            <label
                className={`mb-1 block text-sm font-medium ${
                    showRed ? 'text-red-700' : 'text-gray-700'
                }`}
            >
                {label}{' '}
                {isRequired && (
                    <span className="align-top font-bold text-red-600">*</span>
                )}
            </label>
            <input
                {...props}
                className={`w-full rounded-lg border px-3 py-2 transition-colors focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 ${
                    error || showRed ? 'border-red-500' : 'border-gray-300'
                } ${props.disabled ? 'cursor-not-allowed bg-gray-100' : ''}`}
            />
            {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
        </div>
    );
}
