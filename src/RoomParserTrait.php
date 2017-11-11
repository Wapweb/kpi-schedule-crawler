<?php
namespace Wapweb\KpiScheduleCrawler;


trait RoomParserTrait
{
    /**
     * @param string $linkValue
     * @param string $linkUrl
     * @return array
     */
    protected function _parseRoom(string $linkValue, string $linkUrl): array
    {
        if($linkValue == '337-A-18 Прак') {
            $a = 1;
        }
        // room, lesson type
        $lessonRoom = '';
        $lessonType = '';
        $rate = 1;

        $linkValue = trim($linkValue);

        $res = [
            'room_name' => '',
            'lesson_type' => $lessonType,
            'lesson_rate' => $rate,
            'lesson_room' => $lessonRoom,
            'room_latitude' => '',
            'room_longitude' => '',
            'source' => $linkValue
        ];

        if (!empty($linkValue)) {
            $linkValue = str_replace(['янг.-Пол.', 'янг-Пол.', '000-Пол.', '-Пол.'], '', $linkValue);
            $linkValue = preg_replace('/\s+/u', ' ', $linkValue);
            $linkValue = trim(trim($linkValue, ','));

            preg_match('`[\dA-zА-Юа-ю\/]+(-[А-Юа-юA-z]+)?-(\d)+`iu', $linkValue, $roomMatches);
            preg_match('`\d[\.|,]\d`u', $linkValue, $rateMatches);
            preg_match('`Лек|Прак|Лаб|Конс`iu', $linkValue, $typeMatches);
            if (isset($roomMatches[0])) {
                $lessonRoom = $roomMatches[0];
            }
            if (isset($rateMatches[0])) {
                $rate = (float)str_replace(',', '.', $rateMatches[0]);
            }
            if (isset($typeMatches[0])) {
                $lessonType = $typeMatches[0];
            }

            $res['room_name'] = trim(str_replace([',', '.', 'Прак', 'Лек', 'Лаб', 'Конс', ' '], '', preg_replace('`\d[\.|,]\d`', '', $linkValue)));
            $res['lesson_type'] = $lessonType;
            $res['lesson_rate'] = $rate;
            $res['lesson_room'] = $lessonRoom;
        }

        //parse room latitude and longitude
        $query = parse_url($linkUrl, PHP_URL_QUERY);
        if ($query) {
            $query = str_replace('q=', '', $query);
            list($roomLatitude, $roomLongitude) = explode(',', $query);
            $res['room_latitude'] = $roomLatitude;
            $res['room_longitude'] = $roomLongitude;
        }

        return $res;
    }
}