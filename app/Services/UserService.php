<?php

namespace App\Services;

class UserService extends Service
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

        public function getUserByFilter($filters)
    {
        // PERBAIKAN: Definisikan filter yang relevan untuk tabel 'users'
        $allowedFilters = [
            'users.name'          => 'like',
            'users.email'         => 'like',
            'users.province_id'   => 'value',
            'users.city_id'       => 'value',
            'users.district_id'   => 'value',
            'users.created_at'    => 'date',
            'users.role'          => 'value'
        ];

        $selectColumns = [
           'users.*',
           'provinces.name as province_name',
           'cities.name as city_name',
           'districts.name as district_name',
        ];

        $query = \App\Models\User::select($selectColumns)
            ->leftJoin('provinces', 'users.province_id', '=', 'provinces.id')
            ->leftJoin('cities', 'users.city_id', '=', 'cities.id')
            ->leftJoin('districts', 'users.district_id', '=', 'districts.id')
            ->orderBy('users.id', 'desc');

        // Menerapkan filter ke query (asumsi method applyFilters sudah ada)
        $query = $this->applyFilters($query, $filters, $allowedFilters);

        // PERBAIKAN: Eager load relasi yang ada di model User
        $query->with(['province', 'city', 'district']);

        // Gunakan get() atau paginate() sesuai kebutuhan
        return $query->get();
    }

    public function buildFilter($request)
    {
        $filters = [];

        if ($request->filled('province_id')) {
            $filters['users.province_id'] = $request->input('province_id');
        }
        if ($request->filled('city_id')) {
            $filters['users.city_id'] = $request->input('city_id');
        }
        if ($request->filled('district_id')) {
            $filters['users.district_id'] = $request->input('district_id');
        }
        if ($request->filled('role')) {
            $filters['users.role'] = $request->input('role');
        }

        if ($request->filled('search')) {
            $filters['users.name'] = $request->input('search');
        }

        if ($request->filled('created_from') && $request->filled('created_to')) {
            $filters['users.created_at'] = [
                'start' => $request->input('created_from'),
                'end'   => $request->input('created_to')
            ];
        }

        return $filters;
    }
}
