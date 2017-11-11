<?php
namespace Wapweb\KpiScheduleCrawler;

use Wapweb\KpiScheduleCrawler\Models\ExamModel;

class GroupExamCrawler implements CrawlerInterface
{
    use RoomParserTrait;

    /** @var string  */
    protected $_html;

    protected $_examTableId;

    private const DELIMITER = '@';

    private const ROZKLAD_KPI_HOST = 'http://rozklad.kpi.ua';

    public function __construct(string $html, string $examTableId = 'ctl00_MainContent_ViewSessionScheduleTable')
    {
        $this->_html = $html;
        $this->_examTableId = $examTableId;
    }

    function parse(): array
    {
        $this->_html = str_replace(["\t", "\n", "\r"], "", $this->_html);
        $this->_html = str_replace(["<br>", "<br/>"], self::DELIMITER, $this->_html);

        $dom = new \DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $this->_html);

        $table = $dom->getElementById($this->_examTableId);
        if(!$table) {
            return [];
        }

        $exams = [];

        $rows = $table->getElementsByTagName('tr');
        /** @var \DOMElement $row */
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            if($cells->length < 2) {
                return [];
            }

            if($cells->length > 2) {
                throw new Exception('Invalid number of table row cells:'.$cells->length);
            }

            $date = trim($cells->item(0)->textContent);
            $examString = trim($cells->item(1)->textContent);
            if(!preg_replace('/\s+/', '', $examString)) {
                continue;
            }

            $exam = new ExamModel();
            list($plainLesson, $plainTeachers, $plainRoom, $plainTime) = array_pad(explode(self::DELIMITER, $examString), 4, null);

            $exam->setLessonShortName(trim($plainLesson))
                ->setRoom(['source' => trim($plainRoom)])
                ->setTimestamp($plainTime ? strtotime($date.' '.$plainTime.':00') : 0)
                ->setPlainTeachers(trim($plainTeachers));

            $links = $cells->item(1)->getElementsByTagName('a');
            /** @var \DOMElement $link */
            foreach ($links as $i => $link) {
                $linkUrl = $link->getAttribute('href');
                if (preg_match('`(.*?)wiki\.kpi\.ua(.*?)`ui', $linkUrl) || $i == 0) {
                    // lesson
                    if($link->hasAttribute('title')) {
                        $exam->setLessonName(trim($link->getAttribute('title')));
                    } else {
                        $exam->setLessonName($exam->getLessonShortName());
                    }
                } else if (preg_match('`(.*?)Schedules/ViewSchedule\.aspx(.*?)`ui', $linkUrl)) {
                    // teacher
                    $teacherUrl = strpos($linkUrl, 'http') !== 0 ? self::ROZKLAD_KPI_HOST . $linkUrl : $linkUrl;
                    $teacherName = trim($link->textContent);
                    $teacherFullNameWithGrade = trim($link->getAttribute('title'));

                    $exam->addTeacher([
                        'teacher_name' => $teacherFullNameWithGrade,
                        'teacher_full_name_lesson' => $teacherFullNameWithGrade,
                        'teacher_short_name_lesson' => $teacherName,
                        'teacher_url' => $teacherUrl
                    ]);
                } else if (preg_match('`(.*?)maps\.google\.com(.*?)`ui', $linkUrl)) {
                    // room, lesson type
                    $roomData = $this->_parseRoom($link->textContent, $linkUrl);
                    $exam->setRoom($roomData);
                } else {
                    throw new Exception('Cannot detect type of link');
                }
            }

            $exams[] = $exam;
        }

        return $exams;
    }
}