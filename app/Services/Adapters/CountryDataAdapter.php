<?php

namespace App\Services\Adapters;

class CountryDataAdapter
{
    public function adaptee(array $result): array
    {
        $data = [];

        foreach ($result as $row) {
            $countryId = $row['country_id'];
            $regionId = $row['region_id'];
            $city = [
                'id' => $row['city_id'],
                'name' => $row['city_name'],
                'desc' => $row['city_desc'],
            ];

            if (!isset($data[$countryId])) {
                $data[$countryId] = [
                    'id' => $row['country_id'],
                    'name' => $row['country_name'],
                    'desc' => $row['country_desc'],
                    'cities' => [],
                    'regions' => [],
                ];
            }

            if ($regionId) {
                if (!isset($data[$countryId]['regions'][$regionId])) {
                    $data[$countryId]['regions'][$regionId] = [
                        'id' => $row['region_id'],
                        'name' => $row['region_name'],
                        'desc' => $row['region_desc'],
                        'cities' => [],
                    ];
                }
                $data[$countryId]['regions'][$regionId]['cities'][] = $city;
            } else {
                $data[$countryId]['cities'][] = $city;
            }
        }

        foreach ($data as $country) {
            $country['regions'] = array_values($country['regions']);
        }

        return array_values($data);
    }
}
