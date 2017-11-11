<?php
namespace Wapweb\KpiScheduleCrawler;

use Wapweb\KpiScheduleCrawler\Models\LessonModel;

/**
 * Class GroupScheduleCrawler
 * Parses html response into array of LessonModels
 *
 * @package Wapweb\KpiScheduleCrawler
 */
class GroupScheduleCrawler implements CrawlerInterface
{
    use RoomParserTrait;

    private const DELIMITER = '@';

    private const LESSONS_SCHEDULE = [
        1 => ['time_start' => '08:30', 'time_end' => '10:05'],
        2 => ['time_start' => '10:25', 'time_end' => '12:00'],
        3 => ['time_start' => '12:20', 'time_end' => '13:55'],
        4 => ['time_start' => '14:15', 'time_end' => '15:50'],
        5 => ['time_start' => '16:10', 'time_end' => '17:45'],
        6 => ['time_start' => '18:30', 'time_end' => '20:05']
    ];

    private const DAY_NAMES = ['Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П’ятниця', 'Субота'];

    private const ROZKLAD_KPI_HOST = 'http://rozklad.kpi.ua';

    /**
     * @var string
     */
    private $_content;

    /**
     * @var string|null
     */
    private $_groupUrl;

    /**
     * @var int|null
     */
    private $_groupId;

    /**
     * GroupScheduleCrawler constructor.
     * @param string $htmlContent
     * @param string|null $groupUrl
     * @param int|null $groupId
     */
    public function __construct(string $htmlContent, string $groupUrl = null, int $groupId = null)
    {
        $this->_content = $htmlContent;
        $this->_groupUrl = $groupUrl;
        $this->_groupId = $groupId;
    }

    /**
     * @return LessonModel[]
     * @throws Exception
     */
    public function parse(): array
    {
        $this->_content = str_replace(["\t", "\n", "\r"], "", $this->_content);
        preg_match_all('|<table (.*?)>(.*?)</table>|su', $this->_content, $out);
        if (!count($out) || !count($out[0])) {
            return [];
        }

        $lessons = [];
        $htmlTables = [$out[0][0], $out[0][1]];

        foreach ($htmlTables as $weekIndex => $htmlTable) {
            $htmlTable = str_replace(["<br>", "<br/>"], self::DELIMITER, $htmlTable);

            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $htmlTable);

            $tableRows = $dom->getElementsByTagName('tr');

            /** @var \DOMElement $tableRow */
            foreach ($tableRows as $lessonNumber => $tableRow) {
                $tableRowCells = $tableRow->getElementsByTagName('td');

                /** @var \DOMElement $tableRowCell */
                foreach ($tableRowCells as $dayNumber => $tableRowCell) {
                    if ($lessonNumber < 1 || $lessonNumber > 5 || $dayNumber < 1 || $dayNumber > 6) continue;
                    if (empty($tableRowCell->nodeValue)) continue;

                    list($plainLesson, $plainTeachers, $plainRoom) = explode(self::DELIMITER, trim($tableRowCell->textContent));

                    $lessonModel = new LessonModel();
                    $lessonModel
                        ->setGroupId($this->_groupId)
                        ->setGroupUrl($this->_groupUrl)
                        ->setTimeStart(self::LESSONS_SCHEDULE[$lessonNumber]['time_start'])
                        ->setTimeEnd(self::LESSONS_SCHEDULE[$lessonNumber]['time_end'])
                        ->setDayName(self::DAY_NAMES[$dayNumber - 1])
                        ->setDayNumber($dayNumber)
                        ->setLessonNumber($lessonNumber)
                        ->setLessonWeek($weekIndex + 1)
                        ->setTeacherName(trim($plainTeachers))
                        ->setLessonRoomType(trim($plainRoom));

                    $links = $tableRowCell->getElementsByTagName('a');
                    /** @var \DOMElement $link */
                    foreach ($links as $link) {
                        $linkUrl = $link->getAttribute('href');
                        if (preg_match('`(.*?)wiki\.kpi\.ua(.*?)`ui', $linkUrl)) {
                            // lesson
                            $lessonModel->setLessonName(trim($link->textContent));
                            $lessonModel->setLessonFullName(trim($link->getAttribute('title')));
                        } else if (preg_match('`(.*?)Schedules/ViewSchedule\.aspx(.*?)`ui', $linkUrl)) {
                            // teacher
                            $teacherUrl = strpos($linkUrl, 'http') !== 0 ? self::ROZKLAD_KPI_HOST . $linkUrl : $linkUrl;
                            $teacherName = trim($link->textContent);
                            $teacherFullNameWithGrade = trim($link->getAttribute('title'));

                            $lessonModel->addTeacher([
                                'teacher_name' => $teacherFullNameWithGrade,
                                'teacher_full_name_lesson' => $teacherFullNameWithGrade,
                                'teacher_short_name_lesson' => $teacherName,
                                'teacher_url' => $teacherUrl
                            ]);
                        } else if (preg_match('`(.*?)maps\.google\.com(.*?)`ui', $linkUrl)) {
                            // room, lesson type
                            $roomData = $this->_parseRoom($link->textContent, $linkUrl);

                            $lessonModel->setLessonRoom(!$lessonModel->getLessonRoom() ? $roomData['lesson_room'] : $lessonModel->getLessonRoom() . ',' . $roomData['lesson_room']);
                            $lessonModel->setLessonType(!$lessonModel->getLessonType() ? $roomData['lesson_type'] : $lessonModel->getLessonType() . ',' . $roomData['lesson_type']);
                            $lessonModel->setRate(!$lessonModel->getRate() ? $roomData['lesson_rate'] : $lessonModel->getRate() . ',' . $roomData['lesson_rate']);

                            if($roomData['room_latitude'] && $roomData['room_longitude']) {
                                $lessonModel->addRoom([
                                    'room_name' => $roomData['room_name'],
                                    'room_latitude' => $roomData['room_latitude'],
                                    'room_longitude' => $roomData['room_longitude'],
                                    'lesson_type' => $roomData['lesson_type'],
                                    'lesson_rate' => $roomData['lesson_rate']
                                ]);
                            }
                        } else {
                            throw new Exception('Cannot detect type of link');
                        }
                    }

                    if (!$lessonModel->getLessonType()) {
                        if (preg_match('`Лек|Прак|Лаб|Конс`iu', $tableRowCell->textContent, $matches)) {
                            $lessonModel->setLessonType($matches[0]);
                        }
                    }

                    $lessons[] = $lessonModel;
                }
            }
        }

        return $lessons;
    }
}