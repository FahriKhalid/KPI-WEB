<?php
namespace App\Domain\Karyawan\Services;

class ParseAtasan
{
    /**
     * @var \App\Domain\Karyawan\Entities\Karyawan
     */
    protected $atasanLangsung;

    /**
     * @var \App\Domain\Karyawan\Entities\Karyawan
     */
    protected $atasanTakLangsung;

    /**
     * @var \App\Domain\Karyawan\Entities\Karyawan
     */
    protected $karyawan;

    /**
     * @param \App\Domain\Karyawan\Entities\Karyawan
     * @param Collection
     */
    public function find($karyawan, $atasans)
    {
        foreach ($atasans as $atasan) {
            $karyawanAbbreviation = $karyawan->organization->position->PositionAbbreviation;
            $positionAbbreviation = $atasan->organization->position->PositionAbbreviation;
            $strpos = strpos($karyawanAbbreviation, '0');
            if (strpos($positionAbbreviation, "0") === $strpos - 1) {
                $this->atasanLangsung = $atasan;
            } elseif (strpos($positionAbbreviation, "0") === $strpos - 2) {
                $this->atasanTakLangsung = $atasan;
            }
        }
    }

    /**
     * @return \App\Domain\Karyawan\Entities\Karyawan
     */
    public function getAtasanLangsung()
    {
        return $this->atasanLangsung;
    }

    /**
     * @return \App\Domain\Karyawan\Entities\Karyawan
     */
    public function getAtasanTakLangsung()
    {
        return $this->atasanTakLangsung;
    }
}
