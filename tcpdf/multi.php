 <?php
 
 ob_end_clean();
$kreator_dokumenta="priprave.izdelal.si - ";


$ucitelj="Miha Kočar";
$razred='6';
$predmet="Tehnika in tehnologija";
$ucni_sklop="Les";
$ucna_enota="Izdelava prvega izdelka";
$kljucne_besede='priprava '.$ucitelj.' '.$ucni_sklop.' '.$ucna_enota.' '.$ucna_vsebina;
$opombe_realizacije="do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16";

$logotip='../krpan_mini.png';
$sola='OŠ Martina Krpana';
$sola_ulica='Gašperšičeva 10';
$sola_kraj='1000 Ljuljana';

$datumD=getdate();
$datum=$datumD['mday'].'. '.$datumD['mon'].'. '.$datumD['year'];

$ura_zaporedna_st='12';
$st_vseh_ur='35';
$st_ure='3';
$stevilo_ur='10';
$ucne_oblike='frontalno, individualno';
$ucne_metode='razgovor, razlaga, prikazovanje, demonstracija, praktični del';
$ucni_pripomocki='Računalnik, projektor, internet';
$potek_ure='https://raw.githubusercontent.com/miharix/miharix-desktop-cam/master/README.md';


/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
require_once('Parsedown.php');
// create new PDF document



$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/slv.php')) {
	require_once(dirname(__FILE__).'/lang/slv.php');
	$pdf->setLanguageArray($l);
}

// set document information
$pdf->SetCreator($kreator_dokumenta.PDF_CREATOR);
$pdf->SetAuthor($ucitelj);
$pdf->SetTitle($predmet.' '.$razred.'r');
$pdf->SetSubject($ucni_sklop.'-'.$ucna_enota);
$pdf->SetKeywords($kljucne_besede);


// set default header data
$pdf->SetHeaderData($logotip, 12 , $ucitelj, $razred. ' razred, '. $predmet."\n".$ucni_sklop." - ".$ucna_enota."\n".$datum.' - Ura št.:'.$ura_zaporedna_st.'/'.$st_vseh_ur.' - Ura sklopa: '.$st_ure.' od '.$stevilo_ur, array(0,0,10), array(0,20,20));
$pdf->setFooterData(array(0,0,10), array(0,20,20));

// set header and footer fonts
$pdf->setHeaderFont(Array('freeserif', '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array('freeserif', '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(15, 30, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(15);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 18);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// ---------------------------------------------------------

// set font
$pdf->SetFont('freeserif', 'I', 10,'',false);

// add a page
$pdf->AddPage();



$pdf->SetFillColor(255, 255, 255);
$polovica=($pdf-> getPageWidth);
echo($polovica);
$pdf->MultiCell($polovica, 5, 'Učne oblike: '.$ucne_oblike, 1, 'L', 1, 1, '', '', true);
//$pdf->Ln(6,false);

//$pdf->SetFillColor(255, 155, 127);
$pdf->MultiCell($pdf-> getPageWidth/2.0, 5, 'Učne metode: '.$ucne_metode, 0, 'L', 1, 1, '', '', true);
$pdf->Ln(6,false);

$pdf->SetFillColor(205, 255, 127);
$pdf->MultiCell($pdf-> getPageWidth, 5, 'Učne pripomočki: '.$ucni_pripomocki, 0, 'L', 1, 1, '', '', true);
$pdf->Ln(8,false);

$pdf->SetFillColor(255, 255, 127);
$pdf->MultiCell($pdf-> getPageWidth, 5, 'Opombe: '.$opombe_realizacije, 0, 'L', 1, 1, '', '', true);
$pdf->Ln(6,false);

$pdf->SetFont('freeserif', '', 10,'',false);

$Parsedown = new Parsedown();
//$html = file_get_contents($potek_ure);
$podrobno = $Parsedown->text(file_get_contents($potek_ure));

// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// get current vertical position
$y = $pdf->getY();

$pdf->SetDrawColorArray(0, 0, 0);

// set color for background
$pdf->SetFillColor(235, 235, 235);

// set color for text
$pdf->SetTextColor(0, 0, 0);

// write the second column
$pdf->writeHTMLCell($pdf-> getPageWidth, '', '', '','<i>Učni cilji in potek ure:</i>'.$podrobno, 1, 1, 1, true, 'J', true);


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------
//ob_end_clean();
//Close and output PDF document
$pdf->Output('priprava.pdf', 'I');

?>