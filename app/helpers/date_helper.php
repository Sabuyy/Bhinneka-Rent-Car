<?php

if (!function_exists('next_month')) {
    function next_month($date = null)
    {
        $date = is_null($date) ? date('Y-m-d') : date($date);
        $dateObject = new DateTime($date);

        return date_add($dateObject, new DateInterval('P1M'))->format('Y-m-d');
    }
}

if (!function_exists('previous_month')) {
    function previous_month($date = null)
    {
        $date = is_null($date) ? date('Y-m-d') : date($date);
        $dateObject = new DateTime($date);

        return date_sub($dateObject, new DateInterval('P1M'))->format('Y-m-d');
    }
}

if (!function_exists('hari')) {
    function hari($day)
    {
        $hari = $day;

        switch ($hari) {
            case "Sun":
                $hari = "Minggu";
                break;
            case "Mon":
                $hari = "Senin";
                break;
            case "Tue":
                $hari = "Selasa";
                break;
            case "Wed":
                $hari = "Rabu";
                break;
            case "Thu":
                $hari = "Kamis";
                break;
            case "Fri":
                $hari = "Jum'at";
                break;
            case "Sat":
                $hari = "Sabtu";
                break;
        }
        return $hari;
    }
}

if (!function_exists('hariIndo')) {
    function hariIndo($date)
    {
        if ($date != '0000-00-00') {
            $data = hari(date('D', strtotime($date)));
        } else {
            $data = '-';
        }

        return $data;
    }
}

if (!function_exists('month_indo')) {
    function month_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $bulan[(int)$pecahkan[1]];
    }
}

if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }
}

if (!function_exists('monthShort')) {
    function monthShort($bulan)
    {
        if ($bulan == 1) $bulan = "Jan";
        else if ($bulan == 2) $bulan = "Feb";
        else if ($bulan == 3) $bulan = "Mar";
        else if ($bulan == 4) $bulan = "Apr";
        else if ($bulan == 5) $bulan = "Mei";
        else if ($bulan == 6) $bulan = "Jun";
        else if ($bulan == 7) $bulan = "Jul";
        else if ($bulan == 8) $bulan = "Agu";
        else if ($bulan == 9) $bulan = "Sep";
        else if ($bulan == 10) $bulan = "Okt";
        else if ($bulan == 11) $bulan = "Nov";
        else if ($bulan == 12) $bulan = "Des";
        return $bulan;
    }
}

if (!function_exists('previous_period')) {
    function previous_period($period = null)
    {
        $date = is_null($period) ? date_create() : date_create_from_format('Ym', $period);
        return date_sub($date, new DateInterval('P1M'))->format('Ym');
    }
}

if (!function_exists('next_period')) {
    function next_period($period = null)
    {
        $date = is_null($period) ? date_create() : date_create_from_format('Ym', $period);
        return date_add($date, new DateInterval('P1M'))->format('Ym');
    }
}

if (!function_exists('DateIndo')) {
    function DateIndo($tanggal)
    {
        $date = date_create($tanggal);
        return date_format($date, 'd-m-Y');
        // return date_format($date, 'd-'.numToBulan('m').'-Y');
    }
}

if (!function_exists('indonesian_date')) {
    function indonesian_date($format, $timestamp = null)
    {
        $raw = date($format, $timestamp ?? time());

        static $localeMap = null;
        if ($localeMap === null) {
            $localeMap = [
                'January'   => 'Januari',
                'February'  => 'Februari',
                'March'     => 'Maret',
                'April'     => 'April',
                'May'       => 'Mei',
                'June'      => 'Juni',
                'Jule'      => 'Juli',
                'August'    => 'Agustus',
                'September' => 'September',
                'October'   => 'Oktober',
                'November'  => 'November',
                'December'  => 'Desember',

                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
                'Sunday'    => 'Minggu',

                'Aug' => 'Agu',
                'Oct' => 'Okt',
                'Dec' => 'Des',
                'Mon' => 'Sen',
                'Tue' => 'Sel',
                'Wed' => 'Rab',
                'Thu' => 'Kam',
                'Fri' => 'Jum',
                'Sat' => 'Sab',
                'Sun' => 'Min',
            ];

            uksort($localeMap, function ($a, $b) {
                return strlen($b) <=> strlen($a);
            });
        }

        return str_replace(array_keys($localeMap), array_values($localeMap), $raw);
    }
}

if (!function_exists('numToBulan')) {
    function numToBulan($bulan)
    {
        if ($bulan == 1) $bulan = 'Januari';
        else if ($bulan == 2) $bulan = 'Februari';
        else if ($bulan == 3) $bulan = 'Maret';
        else if ($bulan == 4) $bulan = 'April';
        else if ($bulan == 5) $bulan = 'Mei';
        else if ($bulan == 6) $bulan = 'Juni';
        else if ($bulan == 7) $bulan = 'Juli';
        else if ($bulan == 8) $bulan = 'Agustus';
        else if ($bulan == 9) $bulan = 'September';
        else if ($bulan == 10) $bulan = 'Oktober';
        else if ($bulan == 11) $bulan = 'November';
        else if ($bulan == 12) $bulan = 'Desember';
        return $bulan;
    }
}

if (!function_exists('bulanToNum')) {
    function bulanToNum($bulan)
    {
        $bulan = strtolower($bulan);

        $arrayMonth = array(
            "januari" => 1,
            "februari" => 2,
            "maret" => 3,
            "april" => 4,
            "mei" => 5,
            "juni" => 6,
            "juli" => 7,
            "agustus" => 8,
            "september" => 9,
            "oktober" => 10,
            "november" => 11,
            "desember" => 12
        );

        $result  = $arrayMonth[$bulan];
        return $result;
    }
}


if (!function_exists('bulanToNumWithZeros')) {
    function bulanToNumWithZeros($bulan)
    {
        $bulan = strtolower($bulan);

        $arrayMonth = array(
            "januari" => 01,
            "februari" => 02,
            "maret" => 03,
            "april" => 04,
            "mei" => 05,
            "juni" => 06,
            "juli" => 07,
            "agustus" => 8,
            "september" => 9,
            "oktober" => 10,
            "november" => 11,
            "desember" => 12
        );

        $result  = $arrayMonth[$bulan];
        return $result;
    }
}
if (!function_exists('format_date_ind')) {
    /*****************************************	
	Format Tanggal Indonesia Lengkap
	from 	: 2015-02-12
	to 		: 12 Februari 2015
     *****************************************/
    function format_date_ind($tgl, $param = 'long')
    {
        if (trim($tgl) != '' and $tgl != '0000-00-00') {
            $d = substr($tgl, 8, 2);
            $m = substr($tgl, 5, 2);
            $y = substr($tgl, 0, 4);
            $getbulan = array();
            $getbulan[1] = (($param == 'short') ? 'Jan' : 'Januari');
            $getbulan[2] = (($param == 'short') ? 'Feb' : 'Februari');
            $getbulan[3] = (($param == 'short') ? 'Mart' : 'Maret');
            $getbulan[4] = (($param == 'short') ? 'Apr' : 'April');
            $getbulan[5] = (($param == 'short') ? 'Mei' : 'Mei');
            $getbulan[6] = (($param == 'short') ? 'Jun' : 'Juni');
            $getbulan[7] = (($param == 'short') ? 'Jul' : 'Juli');
            $getbulan[8] = (($param == 'short') ? 'Agst' : 'Agustus');
            $getbulan[9] = (($param == 'short') ? 'Sept' : 'September');
            $getbulan[10] = (($param == 'short') ? 'Okt' : 'Oktober');
            $getbulan[11] = (($param == 'short') ? 'Nov' : 'November');
            $getbulan[12] = (($param == 'short') ? 'Des' : 'Desember');
            $tanggal = $d . " " . $getbulan[(int)$m] . " " . $y;
            return $tanggal;
        }
    }
}

if (!function_exists('dateUSAconvert')) {
    function dateUSAconvert($tanggal)
    {
        if (empty($tanggal)) {
            return null;
        } else {
            $date = date("Y-m-d", strtotime($tanggal));
            return $date;
        }
    }
}

if (!function_exists('dateUSA')) {
    function dateUSA($tanggal)
    {
        if (empty($tanggal)) {
            return null;
        } else {
            $date = date("d-m-Y", strtotime($tanggal));
            return $date;
        }
    }
}

if (!function_exists('dateINA')) {
    function dateINA($tanggal)
    {
        if (empty($tanggal)) {
            return null;
        } else {
            $date = date("d-m-Y", strtotime($tanggal));
            return $date;
        }
    }
}

if (!function_exists('datetimeUSA')) {
    function datetimeUSA($tanggal)
    {
        $date = date("Y-m-d H:i:s", strtotime($tanggal));
        return $date;
    }
}

if (!function_exists('datetimeINA')) {
    function datetimeINA($tanggal)
    {
        $date = date("d-m-Y H:i:s", strtotime($tanggal));
        return $date;
    }
}

if (!function_exists('generate_months')) {
    function generate_months($callback = null)
    {
        $months = [
            1 =>
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        if (!is_callable($callback)) return $months;

        $result = [];
        foreach ($months as $num => $month) {
            $result[$num] = $callback($num, $month);
        }

        return $result;
    }
}

if (!function_exists('batasKar')) {
    function batasKar($nilai, $batas)
    {
        $string = substr($nilai, 0, $batas);
        return $string;
    }
}
