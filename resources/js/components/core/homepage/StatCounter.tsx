import { StatItem } from '@/types/homepage/statItem';
import { useEffect, useRef, useState } from 'react';

interface StatCounterProps {
    stat: StatItem;
    index: number;
}

const useCountUp = (end: number, duration = 2000, delay = 0) => {
    const [count, setCount] = useState(0);
    const [isVisible, setIsVisible] = useState(false);
    const elementRef = useRef<HTMLDivElement | null>(null);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting && !isVisible) {
                    setIsVisible(true);
                }
            },
            { threshold: 0.3 },
        );

        if (elementRef.current) {
            observer.observe(elementRef.current);
        }

        return () => observer.disconnect();
    }, [isVisible]);

    useEffect(() => {
        if (!isVisible) return;

        const timer = setTimeout(() => {
            let startTime: number;
            const animate = (timestamp: number) => {
                if (!startTime) startTime = timestamp;
                const progress = Math.min(
                    (timestamp - startTime) / duration,
                    1,
                );

                const easeOutExpo =
                    progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                setCount(Math.floor(easeOutExpo * end));

                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            requestAnimationFrame(animate);
        }, delay);

        return () => clearTimeout(timer);
    }, [end, duration, delay, isVisible]);

    return { count, elementRef };
};

const StatCounter = ({ stat, index }: StatCounterProps) => {
    const numericValue = parseInt(stat.value.replace(/[^0-9]/g, '')) || 0;
    const { count, elementRef } = useCountUp(numericValue, 2000, index * 200);

    const formatCount = (num: number) => {
        const original = stat.value;
        if (original.includes('+')) return `${num}+`;
        if (original.includes('K')) return `${num}K`;
        if (original.includes('M')) return `${num}M`;
        if (original.includes('%')) return `${num}%`;
        return num.toLocaleString(); // format dengan koma otomatis
    };

    return (
        <div
            ref={elementRef}
            className="group cursor-pointer text-center transition-all duration-300 hover:scale-105"
        >
            <div className="rounded-2xl border border-gray-100/50 bg-white/80 p-6 shadow-sm backdrop-blur-sm transition-all duration-300 hover:shadow-lg">
                <div
                    className={`text-4xl font-bold md:text-5xl ${stat.color} mb-3 transition-transform group-hover:scale-110`}
                >
                    {formatCount(count)}
                </div>
                <div className="text-sm font-medium leading-relaxed text-gray-600 md:text-base">
                    {stat.label}
                </div>
            </div>
        </div>
    );
};

export default StatCounter;
