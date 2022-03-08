<?php
namespace App\Domain\Rencana\Services;

class TargetPeriodeService
{
    const ID_TAHUNAN = [1];
    const ID_SEMESTERAN = [2, 3];
    const ID_TRIWULAN = [4, 5, 6, 7];
    const ID_BULANAN = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
    const TARGET_PERIODEID_MAPPING = [
        '1' => '12',
        '2' => '6',
        '3' => '12',
        '4' => '3',
        '5' => '6',
        '6' => '9',
        '7' => '12',
        '8' => '1',
        '9' => '2',
        '10' => '3',
        '11' => '4',
        '12' => '5',
        '13' => '6',
        '14' => '7',
        '15' => '8',
        '16' => '9',
        '17' => '10',
        '18' => '11',
        '19' => '12',
    ];

    /**
     * @var int
     */
    protected $periode;

    /**
     * @var int
     */
    protected $targetCount = 12;

    /**
     * @param $periodeID
     * @param int $isUnitKerja
     * @return $this
     */
    public function periodeID($periodeID, $isUnitKerja = 0)
    {
        if ($isUnitKerja == 0) {
            if (in_array($periodeID, self::ID_TAHUNAN)) { // tahunan
                $this->targetCount = 1;
            } elseif (in_array($periodeID, self::ID_SEMESTERAN)) { // semester
                $this->targetCount = 2;
            } elseif (in_array($periodeID, self::ID_TRIWULAN)) { // triwulan
                $this->targetCount = 4;
            } else {
                $this->targetCount = 12;
            }
        } else {
            $this->targetCount = 4;
        }
        return $this;
    }

    /**
     * @param $periodeID
     * @param int $isUnitKerja
     * @return $this
     */
    public function periodeTarget($periodeID, $isUnitKerja = 0)
    {
        if ($isUnitKerja == 0) {
            if (in_array($periodeID, self::ID_TAHUNAN)) { // Tahun (1)
                $this->targetCount = 'THN';
            } elseif (in_array($periodeID, self::ID_SEMESTERAN)) { // Semester(2)
                $this->targetCount = 'SEM';
            } elseif (in_array($periodeID, self::ID_TRIWULAN)) { // Triwulan(3)
                $this->targetCount = 'TW';
            } else {
                $this->targetCount = 'BLN';
            }
        } else {
            $this->targetCount = 'TW';
        }
        return $this;
    }

    /**
     * @param $periodeId
     * @return mixed
     */
    public function targetParser($periodeId)
    {
        return self::TARGET_PERIODEID_MAPPING[$periodeId];
    }

    /**
     * @return mixed
     */
    public function getTargetCount()
    {
        return $this->targetCount;
    }
}
