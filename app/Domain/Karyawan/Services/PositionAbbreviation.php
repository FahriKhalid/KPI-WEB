<?php
namespace App\Domain\Karyawan\Services;

use App\Exceptions\DomainException;
use App\Domain\karyawan\Entities\KaryawanLeader;
class PositionAbbreviation
{
    const POSTFIX_STAFF = 'LF';
    const POSTFIX_DIREKTORAT_STRUCTURAL = 'DS';
    const POSTFIX_DIREKTORAT_FUNCTIONAL = 'DF';
    const POSTFIX_PARTNER = 'MLS';

    /**
     * position abbreviation atasan langsung
     *
     * @var mixed
     */
    protected $positionAbbreviationAtasanLangsung = null;

    /**
     * position abbreviation atasan tak langsung
     *
     * @var mixed
     */
    protected $positionAbbreviationAtasanTakLangsung = null;

    /**
     * @var string
     */
    protected $parentPosition;

    /**
     * @var string
     */
    protected $postfixPosition;

    /**
     * @var string
     */
    protected $codeShift;

    /**
     * build and determine position abbreviation for bawahan
     *
     * @param string $posAbbreviationCode position abbreviation karyawan
     * @throws DomainException
     * @return $this
     */
    public function position($posAbbreviationCode)
    {
        if (empty($posAbbreviationCode)) {
            throw new DomainException('position abbreviation is required');
        }

        $this->buildPostfix($posAbbreviationCode);

        // find first position zero value in pos abbreviation Code
        $zeroPos = strpos($posAbbreviationCode, '0');

        $this->parentPosition = substr($posAbbreviationCode, 0, $zeroPos);

        $this->parseAtasanLangsung($posAbbreviationCode, $zeroPos);
        $this->parseAtasanTakLangsung($posAbbreviationCode, $zeroPos);
        return $this;
    }

    /**
     * @param $codeShift
     * @return $this
     */
    public function codeShift($codeShift)
    {
        $this->codeShift = $codeShift;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getPositionAbbreviationAtasanLangsung()
    {
        return $this->positionAbbreviationAtasanLangsung;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getPositionAbbreviationAtasanTakLangsung()
    {
        return $this->positionAbbreviationAtasanTakLangsung;
    }

    /**
     * @return string
     */
    public function getParentPosition()
    {
        return $this->parentPosition;
    }

    /**
     * Check if karyawan as manager based on position abbreviation
     * @return bool
     */
    public function isManager()
    {
        return (strlen($this->parentPosition) === 3 && $this->isStructuralPosition());
    }

    /**
     * Check if karyawan as general manager based on position abbreviation
     * @return bool
     */
    public function isGeneralManager()
    {
        return (strlen($this->parentPosition) === 2 && $this->isStructuralPosition());
    }

    /**
     * Check if karyawan as either general manager or manager based on position abbreviation
     * @return bool
     */
    public function isUnitKerja()
    {
        return $this->isGeneralManager() or $this->isManager();
    }

    /**
     * @return bool
     */
    public function isAssistent()
    {
        return strpos($this->parentPosition, 'W') !== false;
    }

    /**
     * @return bool
     */
    public function isDirut()
    {
        return $this->parentPosition == '1';
    }

    /**
     * @return bool
     */
    public function isDirektorat()
    {
        return ($this->parentPosition >= 1 && $this->parentPosition <=6);
    }

    /**
     * @return bool
     */
    public function isStructuralPosition()
    {
        return $this->postfixPosition == self::POSTFIX_DIREKTORAT_STRUCTURAL;
    }

    /**
     * @return string
     */
    public function getCodeShift()
    {
        return $this->codeShift;
    }

    /**
     * @param $parentPositionAbbreviation
     * @param $levelDepth
     * @return string
     */
    public static function getRemainingLevelBawahanCode($parentPositionAbbreviation, $levelDepth = 2)
    {
        $length = strlen($parentPositionAbbreviation);
        $remainingLength = (12 - ($length + 2 + $levelDepth)); // ($length + 2) 2 = end format of PA -> DS, DF, etc
        return sprintf("%'.0".$remainingLength."d", null); //
    }

    /**
     * Parsing position abbreviation for atasan langsung
     *
     * @param string $posAbbreviationCode
     * @param integer $indexPositionZeroValue
     * @return void
     */
    protected function parseAtasanLangsung($posAbbreviationCode, $indexPositionZeroValue)
    {
        // $atasanLangsungZeroIndexPosition = $this->buildAtasanParentPosition($indexPositionZeroValue);
        // $substrAtasanLangsung = $this->buildPosition($posAbbreviationCode, $atasanLangsungZeroIndexPosition);
        // if (! is_null($substrAtasanLangsung)) {
        //     $this->positionAbbreviationAtasanLangsung = str_pad($substrAtasanLangsung, 10, "0", STR_PAD_RIGHT).'DS';
        // }

        $substrAtasanLangsung = $this->buildPosition($posAbbreviationCode, $indexPositionZeroValue);
        if (! is_null($substrAtasanLangsung)) {
            $this->positionAbbreviationAtasanLangsung = $substrAtasanLangsung;
        }
    }

    /**
     * Parsing position abbreviation for atasan tak langsung
     *
     * @param string $posAbbreviationCode
     * @param integer $indexPositionZeroValue
     * @return void
     */
    protected function parseAtasanTakLangsung($posAbbreviationCode, $indexPositionZeroValue)
    {
        // $atasanTakLangsungZeroIndexPosition = $this->buildAtasanParentPosition($indexPositionZeroValue, 'taklangsung');
        // $substrAtasanTakLangsung = $this->buildPosition($posAbbreviationCode, $atasanTakLangsungZeroIndexPosition);
        // if (! is_null($substrAtasanTakLangsung)) {
        //     $this->positionAbbreviationAtasanTakLangsung = str_pad($substrAtasanTakLangsung, 10, "0", STR_PAD_RIGHT).'DS';
        // }

        $substrAtasanTakLangsung = $this->buildPosition($posAbbreviationCode, $indexPositionZeroValue, 'taklangsung');
        if (! is_null($substrAtasanTakLangsung)) {
            $this->positionAbbreviationAtasanTakLangsung = $substrAtasanTakLangsung;
        }
    }

    /**
     * Build position abbreviation non zero number
     *
     * @param string $posAbbreviationCode positiom abbreviation karyawan
     * @param integer $atasanIndexZeroPosition string non zero position in atasan position abbreviation
     * @return mixed
     */
    protected function buildPosition($posAbbreviationCode, $indexPositionZeroValue, $option = null)
    {
        // $parentCodeAtasan = null;
        // if ($this->isZeroPositionDetermineNonDireksi($atasanIndexZeroPosition)) {
        //     // if there are more 0 value, then get first value of pos abbreviation until next 0 value
        //     $parentCodeAtasan = substr($posAbbreviationCode, 0, $atasanIndexZeroPosition);
        // }
        // if ($this->isZeroPositionDetermineDireksi($atasanIndexZeroPosition)) {
        //     $parentCodeAtasan = $this->parseParentPositionDirektorat();
        // }
        // return $parentCodeAtasan; 
  
        $number_digits = 8; 
        $jabatan = substr($posAbbreviationCode, 12, -1);   
        $hirarki = substr($posAbbreviationCode, 0, 8);
        $search = strrpos($hirarki, '0', -1);

        if($jabatan == 'F'){  

            if($search !== false) // jika hirarki memiliki string 0
            { 

                $zero_count = substr_count($posAbbreviationCode, 0); 
                $zero_repeat = str_repeat(0, $zero_count);

                if($option == null) // atasan langsung
                {
                    $hirarki = rtrim(substr($posAbbreviationCode, 0, 8), 0).$zero_repeat;    
                }
                else // atasan tidak langsung
                {
                    $hirarki = substr(rtrim(substr($posAbbreviationCode, 0, 8), 0), 0, -1).$zero_repeat;  
                }   
            }
            else
            {
                if($option == null) // atasan langsung
                {
                    $hirarki = substr(rtrim(substr($posAbbreviationCode, 0, 8), 0), 0, -1).'0'; 
                }
                else // atasan tak langsung
                {
                    $hirarki = substr(rtrim(substr($posAbbreviationCode, 0, 8), 0), 0, -2).'00'; 
                } 
            }

            $atasan = $hirarki.'%DS%'; 
 
        }else{

            if($option == null) // atasan langsung
            {   
                $hirarki = substr(rtrim(substr($posAbbreviationCode, 0, 8), 0), 0, -1).'0';   
            }
            else // atasan tak langsung
            {  
                $hirarki = substr(rtrim(substr($posAbbreviationCode, 0, 8), 0), 0, -2).'00'; 
            } 

            $atasan = $hirarki.'%DS%';   
        }  

        return $atasan; 
    }

    /**
     * @param $positionAbbreviationCode
     */
    protected function buildPostfix($positionAbbreviationCode)
    {
        $this->postfixPosition = substr($positionAbbreviationCode, -2);
    }

    /**
     * @param $indexPositionZeroValue
     * @param string $atasanType
     * @return int
     */
    protected function buildAtasanParentPosition($indexPositionZeroValue, $atasanType = 'langsung')
    {
        //check digit & direktorat first
        $digits = strlen($this->getParentPosition());
        $direktorat = substr($this->getParentPosition(), 0, 2);

        // special case for direktorat 2
        if (($direktorat == 21 || $direktorat == 22 || $direktorat = 24) && $digits > 6) {
            if ($atasanType == 'langsung') {
                return $indexPositionZeroValue - 2;
            }
            return $indexPositionZeroValue - 3;
        } else {
            if ($atasanType == 'langsung') {
                return $indexPositionZeroValue - 1;
            }
            return $indexPositionZeroValue - 2;
        }
    }

    /**
     * DEPRECATED
     * 
     * @param $positionAbbreviationCode
     * @return null|string
     */
    protected function nonStructuralArranger($positionAbbreviationCode)
    {
        if ($this->postfixPosition == self::POSTFIX_STAFF) {
            // find 1 backwards from postfix position. If found, then the code before 1 is posisi atasan
            // convert to array and reverse value for loop preparation
            $toArr = array_reverse(str_split($positionAbbreviationCode));
            foreach ($toArr as $key => $value) {
                if ($value == '1') {
                    // change 1 to 0 for building atasan langsung
                    $toArr[$key] = '0';
                }
                // change postfix
                if ($value == 'L') {
                    $toArr[$key] = 'D';
                }
                if ($value == 'F') {
                    $toArr[$key] = 'S';
                }
            }
            return implode('', array_reverse($toArr));
        }
        return null;
    }

    /**
     * @param $indexZeroPositionAtasan
     * @return bool
     */
    protected function isZeroPositionDetermineNonDireksi($indexZeroPositionAtasan)
    {
        return $indexZeroPositionAtasan > 0;
    }

    /**
     * @param $indexZeroPositionAtasan
     * @return bool
     */
    protected function isZeroPositionDetermineDireksi($indexZeroPositionAtasan)
    {
        return $indexZeroPositionAtasan === 0;
    }

    /**
     * @return null|string
     */
    protected function parseParentPositionDirektorat()
    {
        // if there is no 0 value, it means level direktorat. means, check if dirut level or not
        return ($this->isDirut()) ? null : '1';
    }
}
