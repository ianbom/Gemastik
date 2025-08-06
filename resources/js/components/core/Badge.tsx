import React from 'react';

interface BadgeProps extends React.HTMLAttributes<HTMLSpanElement> {
    children: React.ReactNode;
    className?: string;
}

const Badge: React.FC<BadgeProps> = ({ children, className, ...props }) => {
    return (
        <span
            className={`inline-block rounded-full px-3 py-1 text-sm font-medium ${className}`}
            {...props}
        >
            {children}
        </span>
    );
};

export default Badge;
