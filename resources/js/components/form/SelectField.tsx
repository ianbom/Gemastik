// components/SelectField.tsx
import { ReactNode, SelectHTMLAttributes } from 'react';

interface SelectFieldProps extends SelectHTMLAttributes<HTMLSelectElement> {
    label: string;
    error?: string;
    children: ReactNode;
}

export default function SelectField({
    label,
    error,
    children,
    ...props
}: SelectFieldProps) {
    return (
        <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">
                {label}
            </label>
            <select
                {...props}
                className={`w-full rounded-lg border px-3 py-2 transition-colors focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 ${
                    error ? 'border-red-500' : 'border-gray-300'
                } ${props.disabled ? 'cursor-not-allowed bg-gray-100' : ''}`}
            >
                {children}
            </select>
            {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
        </div>
    );
}
