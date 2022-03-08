<?php

/**
 * @param $uri
 * @return string
 */
function checkActiveMenu($uri)
{
    $class = '';
    if (request()->is($uri)) {
        $class = 'active';
    }
    return $class;
}

/**
 * @param $convertion
 * @return string
 */
function rowLabelRealization($convertion)
{
    if ($convertion <= 1) {
        $class = 'class="danger"';
    } elseif ($convertion <= 2) {
        $class = 'class="warning"';
    } else {
        $class = 'class="success"';
    }
    return $class;
}

/**
 * @param $uncollected
 * @param $collected
 * @return string
 */
function rowLabelReportRencanaUnitKerja($uncollected, $collected)
{
    $class = 'class="success"';
    if ($collected == 0 && $uncollected > 0) {
        $class = 'class="danger"';
    } elseif ($collected > 0 && $uncollected > 0) {
        $class = 'class="warning"';
    }
    return $class;
}

/**
 * @param $headerRencana
 * @return string
 */
function rowLabelReportRencanaIndividu($headerRencana)
{
    $class = 'class="danger"';
    if ($headerRencana->count() > 0) {
        if ($headerRencana[0]->IDStatusDokumen == 4) {
            $class = 'class="success"';
        } else {
            $class = 'class="warning"';
        }
    }
    return $class;
}

/**
 * @param $headerRealisasi
 * @return string
 */
function rowLabelReportRealisasiIndvidu($headerRealisasi)
{
    $class = 'class="danger"';
    if ($headerRealisasi->count() > 0) {
        if ($headerRealisasi[0]->IDStatusDokumen == 4) {
            $class = 'class="success"';
        } else {
            $class = 'class="warning"';
        }
    }
    return $class;
}

/**
 * @param $percentage
 * @return string
 */
function rowLabelCascadingIsComplete($penurunan)
{
    if ($penurunan->count() > 0) {
        if ($penurunan->sum('PersentaseKRA') == 100) {
            return 'success';
        }
        return 'warning';
    }
    return 'warning';
}

/**
 * Perform basic percentage calculation
 *
 * @param $terbagi
 * @param $pembagi
 * @return string
 */
function calculatePercentage($terbagi, $pembagi)
{
    $result = ($terbagi / $pembagi) * 100;
    return number_format($result, 3);
}

/**
 * Helper function for numbering table data of pagination model
 *
 * @param  Integer $dataCountToDisplay  number of data display in pagination
 * @return Integer
 */
function numberingPagination($dataCountToDisplay)
{
    $page = 1;
    if (request()->has('page') && request('page') > 1) {
        $page += (request('page') - 1) * $dataCountToDisplay;
    }
    return $page;
}

/**
 * @param $number
 * @return string
 */
function numberDisplay($number)
{
    return number_format($number, 2, ',', '.');
}
