<?php
namespace Wapweb\KpiScheduleCrawler\Models;

class ExamModel extends ModelAbstract
{
    /** @var  string */
    protected $_lessonName;

    /** @var  string */
    protected $_lessonShortName;

    /** @var array  */
    protected $_teachers = [];

    /** @var  array|null */
    protected $_room;

    /** @var  string */
    protected $_plainTeachers;

    /**
     * @var int in seconds
     */
    protected $_timestamp;

    /**
     * @return string
     */
    public function getLessonName(): string
    {
        return $this->_lessonName;
    }

    /**
     * @param string $lessonName
     * @return ExamModel
     */
    public function setLessonName(string $lessonName): ExamModel
    {
        $this->_lessonName = $lessonName;
        return $this;
    }

    /**
     * @return array
     */
    public function getTeachers(): array
    {
        return $this->_teachers;
    }

    /**
     * @param array $teachers
     * @return ExamModel
     */
    public function setTeachers(array $teachers): ExamModel
    {
        $this->_teachers = $teachers;
        return $this;
    }

    /**
     * @param array $teacher
     * @return ExamModel
     */
    public function addTeacher(array $teacher): ExamModel
    {
        $this->_teachers[] = $teacher;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getRoom()
    {
        return $this->_room;
    }

    /**
     * @param array|null $room
     * @return ExamModel
     */
    public function setRoom($room)
    {
        $this->_room = $room;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->_timestamp;
    }

    /**
     * @param int $timestamp
     * @return ExamModel
     */
    public function setTimestamp(int $timestamp): ExamModel
    {
        $this->_timestamp = $timestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getLessonShortName(): string
    {
        return $this->_lessonShortName;
    }

    /**
     * @param string $lessonShortName
     * @return ExamModel
     */
    public function setLessonShortName(string $lessonShortName): ExamModel
    {
        $this->_lessonShortName = $lessonShortName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainTeachers(): string
    {
        return $this->_plainTeachers;
    }

    /**
     * @param string $plainTeachers
     * @return ExamModel
     */
    public function setPlainTeachers(string $plainTeachers): ExamModel
    {
        $this->_plainTeachers = $plainTeachers;
        return $this;
    }
}