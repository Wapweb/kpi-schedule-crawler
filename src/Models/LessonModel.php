<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/9/2017
 * Time: 12:46 AM
 */

namespace Wapweb\KpiScheduleCrawler\Models;

/**
 * Class LessonModel
 * @package Wapweb\KpiScheduleCrawler\Models
 */
class LessonModel extends ModelAbstract
{
    /**
     * @var int|null
     */
    protected $_lessonId;

    /**
     * @var int|null
     */
    protected $_groupId;

    /**
     * @var string|null
     */
    protected $_groupUrl;

    /**
     * @var int
     */
    protected $_dayNumber;

    /**
     * @var string
     */
    protected $_dayName;

    /**
     * @var int
     */
    protected $_lessonNumber;

    /**
     * @var string
     */
    protected $_lessonName;

    /**
     * @var string
     */
    protected $_lessonFullName;

    /**
     * @var string|null
     */
    protected $_lessonRoom;

    /**
     * @var string|null
     */
    protected $_lessonType;

    /**
     * @var string|null
     */
    protected $_teacherName;

    /**
     * @var int
     */
    protected $_lessonWeek;

    /**
     * @var string
     */
    protected $_timeStart;

    /**
     * @var string
     */
    protected $_timeEnd;

    /**
     * @var float
     */
    protected $_rate;

    /**
     * @var string|null
     */
    protected $_lessonRoomType;

    /**
     * @var array
     */
    protected $teachers = [];

    /**
     * @var array
     */
    protected $rooms = [];

    public function getGroupId(): ?int
    {
        return $this->_groupId;
    }

    public function setGroupId(?int $_groupId): self
    {
        $this->_groupId = $_groupId;
        return $this;
    }

    public function getGroupUrl(): ?string
    {
        return $this->_groupUrl;
    }

    public function setGroupUrl(?string $_groupUrl): self
    {
        $this->_groupUrl = $_groupUrl;
        return $this;
    }

    public function getDayNumber(): int
    {
        return $this->_dayNumber;
    }

    public function setDayNumber(int $_dayNumber): self
    {
        $this->_dayNumber = $_dayNumber;
        return $this;
    }

    public function getDayName(): string
    {
        return $this->_dayName;
    }

    public function setDayName(string $_dayName): self
    {
        $this->_dayName = $_dayName;
        return $this;
    }

    public function getLessonNumber(): int
    {
        return $this->_lessonNumber;
    }

    public function setLessonNumber(int $_lessonNumber): self
    {
        $this->_lessonNumber = $_lessonNumber;
        return $this;
    }

    public function getLessonName(): string
    {
        return $this->_lessonName;
    }

    public function setLessonName(string $_lessonName): self
    {
        $this->_lessonName = $_lessonName;
        return $this;
    }

    public function getLessonFullName(): string
    {
        return $this->_lessonFullName;
    }

    public function setLessonFullName(string $_lessonFullName): self
    {
        $this->_lessonFullName = $_lessonFullName;
        return $this;
    }

    public function getLessonRoom():?string
    {
        return $this->_lessonRoom;
    }

    public function setLessonRoom(string $_lessonRoom): self
    {
        $this->_lessonRoom = $_lessonRoom;
        return $this;
    }

    public function getLessonType():?string
    {
        return $this->_lessonType;
    }

    public function setLessonType(string $_lessonType): self
    {
        $this->_lessonType = $_lessonType;
        return $this;
    }

    public function getTeacherName():?string
    {
        return $this->_teacherName;
    }

    public function setTeacherName(string $_teacherName): self
    {
        $this->_teacherName = $_teacherName;
        return $this;
    }

    public function getLessonWeek(): int
    {
        return $this->_lessonWeek;
    }

    public function setLessonWeek(int $_lessonWeek): self
    {
        $this->_lessonWeek = $_lessonWeek;
        return $this;
    }

    public function getTimeStart(): string
    {
        return $this->_timeStart;
    }

    public function setTimeStart(string $_timeStart): self
    {
        $this->_timeStart = $_timeStart;
        return $this;
    }

    public function getTimeEnd(): string
    {
        return $this->_timeEnd;
    }

    public function setTimeEnd(string $_timeEnd): self
    {
        $this->_timeEnd = $_timeEnd;
        return $this;
    }

    public function getRate():?float
    {
        return $this->_rate;
    }

    public function setRate(float $_rate): self
    {
        $this->_rate = $_rate;
        return $this;
    }

    public function getLessonRoomType():?string
    {
        return $this->_lessonRoomType;
    }

    public function setLessonRoomType(string $_lessonRoomType): self
    {
        $this->_lessonRoomType = $_lessonRoomType;
        return $this;
    }

    public function getTeachers(): array
    {
        return $this->teachers;
    }

    public function addTeacher(array $teacher): self
    {
        $this->teachers[] = $teacher;
        return $this;
    }

    public function setTeachers(array $teachers): self
    {
        $this->teachers = $teachers;
        return $this;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function addRoom(array $room): self
    {
        $this->rooms[] = $room;
        return $this;
    }

    public function setRooms(array $rooms): self
    {
        $this->rooms = $rooms;
        return $this;
    }

    public function getLessonId():?int
    {
        return $this->_lessonId;
    }

    public function setLessonId(int $lessonId): self
    {
        $this->_lessonId = $lessonId;
        return $this;
    }
}