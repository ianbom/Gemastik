import React from 'react';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
    children: React.ReactNode;
    variant?: 'ghost' | 'outline';
    size?: 'sm' | 'md';
    onClick?: (event: React.MouseEvent<HTMLButtonElement>) => void;
    className?: string;
}

const Button = ({
    children,
    variant = 'ghost',
    size = 'sm',
    onClick,
    className = '',
    ...props
}: ButtonProps) => {
    const baseStyles =
        'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    const variantStyles: Record<string, string> = {
        ghost: 'hover:bg-gray-100 text-gray-600 hover:text-gray-900',
        outline:
            'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700',
    };

    const sizeStyles: Record<string, string> = {
        sm: 'h-8 px-3 text-xs',
        md: 'h-10 px-4 py-2',
    };

    return (
        <button
            onClick={onClick}
            className={`${baseStyles} ${variantStyles[variant]} ${sizeStyles[size]} ${className}`}
            {...props}
        >
            {children}
        </button>
    );
};

export default Button;
