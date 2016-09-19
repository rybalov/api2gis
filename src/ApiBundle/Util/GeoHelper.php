<?php

namespace ApiBundle\Util;

/**
 * Вспомогательный класс для работы с геометрическими/картографическими данными.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class GeoHelper
{
    /**
     * Километров в 1 градусе широты - величина постоянная.
     */
    const ON_DEGREE = 111;

    /**
     * Количество сторон вписываемого в окружность многоугольника (значение по умолчанию).
     */
    const EDGES = 36;

    /**
     * Создание полигона, вписанного в окружность заданного радиуса с указанным количеством сторон.
     *
     * @param float $lat    Градусов широты
     * @param float $lon    Градусов долготы
     * @param float $radius Радиус окружности, в который вписывается многоугольник (в метрах)
     * @param int $edges    Количество сторон многоугольниа
     * @return array
     */
    static public function createInscribedPolygon(float $lat, float $lon, float $radius, int $edges = self::EDGES)
    {
        $radius  = $radius / 1000;
        $polygon = [];

        for ($i = 0; $i <= $edges; $i++) {
            $degree = (2 * M_PI) / $edges;

            $x = $radius * cos($i * $degree);
            $y = $radius * sin($i * $degree);

            $nLat = round($lat + $x / self::ON_DEGREE, 6);
            $nLon = round($lon + $y / abs(cos($lat * M_PI / 180) * self::ON_DEGREE), 6);

            $polygon[] = [$nLat, $nLon];
        }

        return $polygon;
    }

    /**
     * Преобразует массив точек в полигон формата WKT.
     *
     * @param $polygon array
     *
     * @return string
     */
    static public function convertPolygon2WKT(array $polygon)
    {
        $points = [];

        foreach ($polygon as $point) {
            $points[] = $point[0].' '.$point[1];
        }

        $points = implode(', ', $points);

        return "POLYGON(($points))";
    }
}
