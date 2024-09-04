<?php

namespace App\Repositories;

use App\Repositories\Repository;
use App\Models\Country;
use App\Services\Adapters\CountryDataAdapter;

class CountryRepository extends Repository
{
    protected Country $model;

    protected function getModelClass(): string
    {
        return Country::class;
    }

    public function getWithChildRegionsAndCities($language): array
    {
        $query = "SELECT
                    co.id AS country_id,
                    co.c_name_{$language} AS country_name,
                    co.c_descr_{$language} AS country_desc,
                    r.id AS region_id,
                    r.r_name_{$language} AS region_name,
                    r.r_descr_{$language} AS region_desc,
                    c.id AS city_id,
                    c.c_name_{$language} AS city_name,
                    c.c_descr_{$language} AS city_desc
                FROM
                    country co
                LEFT JOIN
                    city c ON co.id = c.c_country_id
                LEFT JOIN
                    region r ON c.c_region_id = r.id
                LEFT JOIN
                    glob_region gr ON co.glob_region_id = gr.id
                WHERE
                    gr.gr_name_rus = 'Европа'
                ORDER BY
                    co.id, r.id, c.id;";

        $result =  $this->model->getByRaw($query);

        return (new CountryDataAdapter())->adaptee($result);
    }

    /**
     * На больших наборах данных это может значительно повлиять на время выполнения. Поэтому я решил выбрать более простой сценарий
     */
    public function getWithRegionsAndCities(string $language): array
    {
        $query = "WITH CityData AS (
                        SELECT
                            c.id AS city_id,
                            c.c_name_{$language} AS city_name,
                            co.id AS country_id,
                            co.c_name_{$language} AS country_name,
                            r.id AS region_id,
           l                 r.r_name_{$language} AS region_name
                        FROM
                            city c
                            LEFT JOIN country co ON c.c_country_id = co.id
                            LEFT JOIN region r ON c.c_region_id = r.id
                            LEFT JOIN glob_region gr ON co.glob_region_id = gr.id
                        WHERE
                            gr.gr_name_{$language} = 'Европа'
                    ),
                    RegionCities AS (
                        SELECT
                            country_id,
                            country_name,
                            region_id,
                            region_name,
                            JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'city_id', city_id,
                                    'city_name', city_name
                                )
                            ) AS cities
                        FROM
                            CityData
                        WHERE
                            region_id IS NOT NULL
                        GROUP BY
                            country_id,
                            country_name,
                            region_id,
                            region_name
                    ),
                    CountryRegions AS (
                        SELECT
                            country_id,
                            country_name,
                            JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'region_id', region_id,
                                    'region_name', region_name,
                                    'cities', cities
                                )
                            ) AS regions
                        FROM
                            RegionCities
                        GROUP BY
                            country_id,
                            country_name
                    ),
                    CountryCities AS (
                        SELECT
                            country_id,
                            country_name,
                            JSON_ARRAYAGG(
                                JSON_OBJECT(
                                    'city_id', city_id,
                                    'city_name', city_name
                                )
                            ) AS cities
                        FROM
                            CityData
                        WHERE
                            region_id IS NULL
                        GROUP BY
                            country_id,
                            country_name
                    )
                    SELECT
                        co.country_id,
                        co.country_name,
                        COALESCE(cr.regions, JSON_ARRAY()) AS regions,
                        COALESCE(cc.cities, JSON_ARRAY()) AS cities
                    FROM
                        (
                            SELECT DISTINCT
                                country_id,
                                country_name
                            FROM
                                CityData
                        ) co
                        LEFT JOIN CountryRegions cr ON co.country_id = cr.country_id
                        LEFT JOIN CountryCities cc ON co.country_id = cc.country_id;";

        return $this->model->getByRaw($query);
    }
}
