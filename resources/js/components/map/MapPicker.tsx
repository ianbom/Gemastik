'use client';

import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { useEffect, useState } from 'react';
import {
    MapContainer,
    Marker,
    TileLayer,
    useMap,
    useMapEvents,
} from 'react-leaflet';

delete (L.Icon.Default.prototype as L.Icon.Default & { _getIconUrl?: unknown })
    ._getIconUrl;

L.Icon.Default.mergeOptions({
    iconUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon.png',
    iconRetinaUrl:
        'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon-2x.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
});

interface Position {
    lat: number;
    lng: number;
}

interface MapPickerProps {
    onChange: (position: { latitude: number; longitude: number }) => void;
    defaultPosition?: [number, number];
    location?: { latitude: number; longitude: number } | null;
    disabled?: boolean;
}

function ClickHandler({
    onMapClick,
    disabled,
}: {
    onMapClick: (pos: Position) => void;
    disabled: boolean;
}) {
    useMapEvents({
        click(e) {
            if (disabled) return;
            const pos = { lat: e.latlng.lat, lng: e.latlng.lng };
            onMapClick(pos);
        },
    });
    return null;
}

function MapAutoCenter({ location }: { location: Position }) {
    const map = useMap();
    useEffect(() => {
        map.setView([location.lat, location.lng], map.getZoom());
    }, [location, map]);
    return null;
}

export default function MapPicker({
    onChange,
    defaultPosition = [-7.2575, 112.7521],
    location,
    disabled = false,
}: MapPickerProps) {
    const [marker, setMarker] = useState<Position | null>(null);
    useEffect(() => {
        if (location) {
            const gpsPos = { lat: location.latitude, lng: location.longitude };
            setMarker(gpsPos);
        }
    }, [location]);

    const handlePositionChange = (pos: Position) => {
        setMarker(pos);
        onChange({ latitude: pos.lat, longitude: pos.lng });
    };

    const center = marker ?? {
        lat: defaultPosition[0],
        lng: defaultPosition[1],
    };

    return (
        <div className="h-64 w-full overflow-hidden rounded-lg border">
            <MapContainer
                center={[center.lat, center.lng]}
                zoom={13}
                scrollWheelZoom={!disabled}
                style={{ height: '100%', width: '100%', zIndex: 0 }}
            >
                <TileLayer
                    attribution='&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
                    url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                />

                <ClickHandler
                    onMapClick={handlePositionChange}
                    disabled={disabled}
                />

                {marker && (
                    <>
                        <Marker position={[marker.lat, marker.lng]} />
                        <MapAutoCenter location={marker} />
                    </>
                )}
            </MapContainer>
        </div>
    );
}
