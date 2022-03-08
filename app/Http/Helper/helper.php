<?php  

function getDireksi($npk){
   return \DB::table("Ms_Direksi")->where("Npk", $npk)->first();
}

function getInfoKaryawan($npk){
	$data = App\Domain\karyawan\Entities\KaryawanLeader::where('PERSONNEL_NUMBER', $npk)->first();
 
	return $data;
}

function decimal($number){
	$number = str_replace('.', '', $number);
	return str_replace(',', '.', $number);
}

function currency($number) {
	$number = str_replace('.', ',', $number);
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1.$2', $number);
        if ($replaced != $number) {
            $number = $replaced;
        } else {
            break;
        }
    }
    return $number;
} 

function toFixed($number, $decimals) {
  return number_format($number, $decimals, '.', "");
}

function title_case($string)
{
    return $string;   
}

function encodex( $string, $url_safe=TRUE) {
	    // you may change these values to your own
	    $secret_key = 'performance_pkt';
	    $secret_iv = 'performance';

	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $key = hash( 'sha256', $secret_key );
	    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	    $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	    
	    if ($url_safe)
	    {
	        $output = strtr(
	                $output,
	                array(
	                    '+' => '.',
	                    '=' => '-',
	                    '/' => '~'
	                )
	            );
	    }

	    return $output;
}

function decodex( $string, $url_safe=TRUE) {
	    // you may change these values to your own
	    $secret_key = 'performance_pkt';
	    $secret_iv = 'performance';

	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $key = hash( 'sha256', $secret_key );
	    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
	    $string = strtr(
	            $string,
	            array(
	                '.' => '+',
	                '-' => '=',
	                '~' => '/'
	            )
	        );
	    $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );

	    return $output;
}
?>