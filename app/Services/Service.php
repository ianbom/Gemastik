<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class Service
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

     protected function applyFilters(Builder $query, array $filters, array $allowedFilters): Builder
    {
        foreach ($filters as $field => $value) {
            if (array_key_exists($field, $allowedFilters)) {
                $type = $allowedFilters[$field];

                switch ($type) {
                    case 'date':
                        if (is_array($value) && isset($value['start'], $value['end'])) {
                            $query = $query->whereBetween($field, [$value['start'], $value['end']]);
                        }
                        break;

                    case 'range':
                        if (is_array($value) && isset($value['min'], $value['max'])) {
                            $query = $query->whereBetween($field, [$value['min'], $value['max']]);
                        }
                        break;

                    case 'value':
                        $query = $query->where($field, $value);
                        break;

                    case 'like':
                        $query = $query->where($field, 'LIKE', "%{$value}%");
                        break;
                }
            }
        }

        return $query;
    }
}
