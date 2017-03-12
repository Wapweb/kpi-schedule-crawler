<?php

use PHPUnit\Framework\TestCase;

class GroupScheduleCrawlerTest extends TestCase
{
    public function groupScheduleProvider() {
        $data = [];
        $dataDir = 'tests/data/';
        $resources = [
            ['source' => 'be-31.html', 'result' => 'be-31.json'],
            ['source' => 'ip-62m.html', 'result' => 'ip-62m.json'],
            ['source' => 'tk-51.html', 'result' => 'tk-51.json']
        ];

        foreach ($resources as $resource) {
            $data[] = [file_get_contents($dataDir.$resource['source']), json_decode(file_get_contents($dataDir.$resource['result']), true)];
        }

        return $data;
    }

    /**
     * @dataProvider groupScheduleProvider
     *
     * @param string $html
     * @param array $expectedResult
     */
    public function testParse(string $html, array $expectedResult) {
        $crawler = new \Wapweb\KpiScheduleCrawler\GroupScheduleCrawler($html);
        /** @var \Wapweb\KpiScheduleCrawler\Models\LessonModel[] $lessons */
        $lessons = $crawler->parse();
        foreach ($lessons as $index => $lesson) {
            $lessonData = $lesson->toArray();
            foreach ($lessonData as $lessonPropertyName => $lessonPropertyValue) {
                if(!in_array($lessonPropertyName, ['group_id', 'group_url', 'lesson_id'])) {
                    $this->assertEquals($expectedResult[$index][$lessonPropertyName], $lessonPropertyValue);
                }
            }
        }
    }
}