<?php

namespace App\Services;

use App\Models\Badge;

class BadgeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createBadge($data){

        if ($data['icon_url']) {
            $fileName = time() . '_' . uniqid() . '.' . $data['icon_url']->getClientOriginalExtension();
            $filePath = $data['icon_url']->storeAs('badge', $fileName, 'public');
            $data['icon_url'] = $filePath;
        }
        $badge = Badge::create($data);
        return $badge;
    }

    public function updateBadge($data, $badge){

        if (isset($data['icon_url']) && $data['icon_url']) {
            $fileName = time() . '_' . uniqid() . '.' . $data['icon_url']->getClientOriginalExtension();
            $filePath = $data['icon_url']->storeAs('badge', $fileName, 'public');
            $data['icon_url'] = $filePath;
        } else {
           $data['icon_url'] = $badge->icon_url;
        }

        $badge->update($data);
        return $badge;
    }
}
