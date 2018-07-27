<?php

namespace Logistio\Symmetry\Geo\Query\Postgis\Geohash;

/**
 * Trait LatLngToGeoHashSqlStatements
 * @package Logistio\Symmetry\Geo\Query\Postgis\Geohash
 *
 * https://en.wikipedia.org/wiki/Geohash
 */
trait LatLngToGeoHashSqlStatements
{
    /**
     * An SQL select snippet to consume a latitude column and a longitude column
     * and to produce a GeoHash with the given $precision.
     *
     * Example function call:
     *
     * selectLatLngColumnsToGeoHash("place.latitude", "place.longitude", 12, 4326);
     *
     * @param $latitudeColumn
     * @param $longitudeColumn
     * @param int $precision
     * @param int $srid
     * @return string
     */
    private function selectLatLngColumnsToGeoHash($latitudeColumn, $longitudeColumn, $precision = 12, $srid = 4326)
    {
        $sql = "
            ST_GeoHash(ST_SetSRID(ST_MakePoint({$longitudeColumn}, {$latitudeColumn}), {$srid}), {$precision})
        ";

        return trim($sql);
    }

    /**
     * The following SQL select snippet allows the client to convert a latitude column and a longitude column to a GeoHash with the given $precision,
     * after which the latitude of the CENTER of the GeoHash polygon is extracted. This SQL select snippet
     * (along with it's sibling method to get the latitude) is useful for grouping many lat/lng columns
     * into GeoHash polygons and using the center of the GeoHash polygon as an index.
     *
     * Note that the LOWER the $precision, the LARGER the GeoHash polygon will be.
     * The default $precision of 12 creates a GeoHash with the highest precision.
     *
     *
     * @param $latitudeColumn
     * @param $longitudeColumn
     * @param int $precision
     * @param int $srid
     * @return string
     */
    private function selectGeoHashLatitudeCenterFromLatLngColumns($latitudeColumn, $longitudeColumn, $precision = 12, $srid = 4326)
    {
        $latLngColumnToGeohashSql = $this->selectLatLngColumnsToGeoHash($latitudeColumn, $longitudeColumn, $precision, $srid);

        $sql = "
            ST_X(
                ST_Centroid(
                    ST_GeomFromGeoHash($latLngColumnToGeohashSql, $precision)
                )
            )    
        ";

        return trim($sql);
    }

    /**
     * This function performs a similar task as it's sibling `selectGeoHashLatitudeCenterFromLatLngColumns`
     * function, the difference being that the GeoHash Polygon's Center Longitude is being extracted.
     *
     * @param $latitudeColumn
     * @param $longitudeColumn
     * @param int $precision
     * @param int $srid
     * @return string
     */
    private function selectGeoHashLongitudeCenterFromLatLngColumns($latitudeColumn, $longitudeColumn, $precision = 12, $srid = 4326)
    {
        $latLngColumnToGeohashSql = $this->selectLatLngColumnsToGeoHash($latitudeColumn, $longitudeColumn, $precision, $srid);

        $sql = "
            ST_Y(
                ST_Centroid(
                    ST_GeomFromGeoHash($latLngColumnToGeohashSql, $precision)
                )
            )    
        ";

        return trim($sql);
    }
}