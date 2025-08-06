import React from 'react';

interface RenderHTMLProps {
    htmlString: string;
    className?: string;
}

const RenderHTML: React.FC<RenderHTMLProps> = ({ htmlString, className }) => {
    const createMarkup = () => {
        return { __html: htmlString };
    };

    return (
        <div className={className} dangerouslySetInnerHTML={createMarkup()} />
    );
};

export default RenderHTML;
