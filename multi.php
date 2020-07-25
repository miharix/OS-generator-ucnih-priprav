 <?php
 //error_reporting(E_ALL);
//ini_set('display_errors', 1);

mb_internal_encoding("UTF-8");

function parse_csv ($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
{
    return array_map(
        function ($line) use ($delimiter, $trim_fields) {
            return array_map(
                function ($field) {
                    return str_replace('!!Q!!', '"', urldecode($field)); //utf8_decode(urldecode($field)));
                },
                $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line)
            );
        },
        preg_split(
            $skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s',
            preg_replace_callback(
                '/"(.*?)"/s',
                function ($field) {
                    return  urlencode($field[1]);//utf8_encode($field[1]));
                },
                $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string)
            )
        )
    );
}
 
 ob_end_clean();
$kreator_dokumenta="priprave.izdelal.si - ";


$ucitelj="Miha Kočar";
$ucitelj_mail="miha.kocar@os-mk.si";
$razred='6';
$predmet="Tehnika in tehnologija";
$predmet_kratica="TIT";
$ucna_skupina="Pile";
$ucni_sklop="Les";
$ucna_enota="Izdelava prvega izdelka";
$kljucne_besede='priprava '.$ucitelj.' '.$ucni_sklop.' '.$ucna_enota;
$opombe_realizacije="do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16do učebenik stran 16";

$logotip='krpan_mini.jpg';
$sola='OŠ Martina Krpana';
$sola_ulica='Gašperšičeva 10';
$sola_kraj='1000 Ljubljana';
$sola_tel='01 520 86 40';
$sola_splet='www.os-mk.si';
$sola_mail='tajnistvo@os-mk.si';

$datumD=getdate();
$datum=$datumD['mday'].'. '.$datumD['mon'].'. '.$datumD['year'];

$ura_zaporedna_st='12';
$st_vseh_ur='35';
$st_ure='3';
$stevilo_ur='10';
$ucne_oblike='frontalno, individualno';
$ucne_metode='razgovor, razlaga, prikazovanje, demonstracija, praktični del';
$ucni_pripomocki='Računalnik, projektor, internet';
$potek_ure='https://raw.githubusercontent.com/jdunmire/kicad-ESP8266/master/README.md';

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
require_once('Parsedown.php');
// create new PDF document

//CSV import------------
$type = explode(".",$_FILES['userfile']['name']);
$mimes = array(
    'text/csv',
    'text/plain',
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    'text/anytext',
    'application/octet-stream',
    'application/txt',
);
if ($_FILES['userfile']['error'] == UPLOAD_ERR_OK               //checks for errors
      && is_uploaded_file($_FILES['userfile']['tmp_name'])
      && in_array($_FILES['userfile']['type'] ,$mimes)
      && (strtolower(end($type)) == 'csv')
      
      ) { //checks that file is uploaded
// echo file_get_contents($_FILES['userfile']['tmp_name']); 
 
 $CsvString = file_get_contents($_FILES['userfile']['tmp_name']);

 $delilec=',';
 if (stristr($CsvString, ';')!=false){$delilec=';';}else{
  if (stristr($CsvString, ',')!=false){$delilec=',';}else{
    if (stristr($CsvString, ':')!=false){$delilec=':';}
  }
 }
 
//$lines = preg_split('/\r\n|\r|\n/',$CsvString);//explode(PHP_EOL, $CsvString);
//$Data = array();

$Data=parse_csv($CsvString,$delilec,false,false);

/*foreach ($lines as $line) {
    $Data[] = str_getcsv($line,$delilec);
}*/
$kolicina= count($Data)-1;

if ($kolicina>1){

//echo $kolicina;

  
  $predmet_kratica = $Data[7][1];
  $razred = $Data[8][1];
  if($Data[9][1]!=''){
   $ucna_skupina = ' - Skupina: '.$Data[9][1];
  }else{
    $ucna_skupina ='';
  }
  
  if($Data[10][1]!=''){
  $predmet=$Data[10][1].'('.$predmet_kratica.')';
  }else{
  $predmet=$predmet_kratica;
  }
  
   $st_vseh_ur = $Data[$kolicina-1][0];
   
   
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
$pdf->SetSubject('Učna priprava');
$pdf->SetKeywords($kljucne_besede);

//od do strani
$odStr=$_POST['odstr'];
$doStr=$_POST['dostr'];
if($doStr>0){
    $kolicina=$doStr+12;
}
   
   //-------------------branje vrstic
   
  //for ($x=12;$x<15;$x++){
  for ($x=$odStr+12;$x<$kolicina;$x++){
   // echo '<br>'.$Data[$x][1];
  
  
  $ura_zaporedna_st = $Data[$x][0];
  $ucna_enota=$Data[$x][1];
  $opombe_realizacije=$Data[$x][2];
  $datum = $Data[$x][3];
  $ucni_sklop = $Data[$x][4];
  $stevilo_ur=$Data[$x][5];
  $st_ure=$Data[$x][6];
  
  $ucni_pripomocki=$Data[$x][8];
  $ucne_oblike=$Data[$x][9];
  $ucne_metode=$Data[$x][10];
  
  $prilagoditve_otork=$Data[$x][11];;
  
  $legenda_prilagoditev="1. Jasne, konkretno postavljene meje.  2. Konkretni ukrepi ob kršitvi konkretnih pravil.  3. Količinsko prilagojene domače naloge  
4. Kopiranje učne vsebine, ki je učenec med poukom zaradi specifičnih primanjkljajev ne uspe prepisati  5. Podaljšan čas pisanja; v razredu ali individualno
6. Učenec sedi tam, kjer najbolj zbrano sledi pouku (v prvi vrsti, v zadnji vrsti, ob sošolcu, ki mu lahko pomaga itd.).  7. ocenjevanje znanja izven razreda
8. Možnost umika iz razreda v dogovorjeni prostor, kjer se lahko učenec umiri in pripravi na nadaljnji pouk.  9. Pomoč učitelja pri branju navodil in besedil 
10. Jasna pisna in ustna navodila.  11. Toleranca specifičnih napak pri pisnih nalogah, narekih (zamenjevanje podobnih črk, številk, vrivanje/izpuščanje znakov črk)
12. Pohvaliti najmanjši napredek glede na zastavljene cilje.  13. opravljanje pisnega ali ustnega ocenjevanja znanja v dveh delih; individualno ali v razredu
14. Poudarek na ustnih odgovorih učenca. Upoštevanje dodatne ustne razlage naloge neposredno po pisnem ocenjevanju znanja.  15. pisno ali ustno ocenjevanje znanja
16. Preglednice in tabele za računanje  17. ustrezna konstrukcija vprašanj  18. Več časa za razmislek, tvorbo odgovora  19. Neporavnan desni rob
20. Uporaba papirja pastelne barve (rumene)  21. Okrepljene, podčrtane ključne, pomembne besede oz. informacije v navodilih  22. dodatna ustna razlaga pisnih navodil
23. Povečava črk  24. Povečava pisnega preizkusa znanja z A4 na A3.  25. Separirane, razdeljene naloge na več strani  26. Ustrezni tip pisave (Arial, Tahoma ali Trebuchet)
27. Razmik med vrsticami 1,5 ali 2  28. Kompleksno navodilo razdeljeno na več manjših delov.  29. Vnaprej napovedano preverjanje ter pisno ali ustno ocenjevanje znanja";
  
  $potek_ure=$Data[$x][12].$Data[$x][7];

 $glava1= $ucitelj.' - Učna priprava';
 $glava2=$razred. ' razred, '. $predmet.$ucna_skupina;
 $glava3=$ucni_sklop." - ".$ucna_enota;
 $glava4=$datum.' - Ura št.: '.$ura_zaporedna_st.'/'.$st_vseh_ur.' - Ura sklopa: '.$st_ure.' od '.$stevilo_ur;
  // set default header data
  $pdf->setPrintHeader(false);
//$pdf->SetHeaderData($logotip, 12 , $sola.' - '.$ucitelj.' - Učna priprava', $razred. ' razred, '. $predmet.$ucna_skupina."\n".$ucni_sklop." - ".$ucna_enota."\n".$datum.' - Ura št.:'.$ura_zaporedna_st.'/'.$st_vseh_ur.' - Ura sklopa: '.$st_ure.' od '.$stevilo_ur, array(0,0,10), array(0,20,20));
$pdf->setFooterData(array(0,0,10), array(0,20,20));

// set header and footer fonts
//$pdf->setHeaderFont(Array('freeserif', '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array('freeserif', '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(15, 10, 10);
//$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(15);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 18);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// set font
$pdf->SetFont('freeserif', 'I', 10,'',false);
  

//------------










// add a page
$pdf->AddPage();
$pdf->SetFillColor(255, 255, 255);
$pdf->setCellPaddings(0, 0, 0, 0);
$pdf->setCellMargins(0, 0, 0, 0);
$pdf->SetFont('freeserif', 'B', 10,'',false);
$pdf->MultiCell(0, 5, $glava1, 0, 'C', 1, 1, '', '', true);
$pdf->SetFont('freeserif', '', 10,'',false);
$pdf->MultiCell(0, 5, $glava2, 0, 'C', 1, 1, '', '', true);
$pdf->MultiCell(0, 5, $glava3, 0, 'C', 1, 1, '', '', true);
$pdf->MultiCell(0, 5, $glava4, 0, 'C', 1, 1, '', '', true);

$pdf->SetFont('freeserif', 'I', 8,'',false);

$style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0,0,0),
    'bgcolor' => false
);

// QRCODE,H : QR-CODE Best error correction
$pdf->write2DBarcode($potek_ure, 'QRCODE,H', 15, 10, 20, 20, $style, 'N');

$pdf->SetXY(135, 10);
$pdf->MultiCell(50, 5, $sola."\n".$sola_ulica."\n".$sola_kraj."\n".$sola_splet."\n".$ucitelj_mail, 0, 'R', 1, 1, '', '', true);
/*$pdf->Write(0, $sola, '', 1, 'R', true, 0, false, false, 0);
$pdf->SetXY(150, 13);
$pdf->Write(0, $sola_ulica, '', 1, 'R', true, 0, false, false, 0);
$pdf->SetXY(150, 16);
$pdf->Write(0, $sola_kraj, '', 1, 'R', true, 0, false, false, 0);
$pdf->SetXY(150, 19);
$pdf->Write(0, $sola_splet, '', 1, 'R', true, 0, false, false, 0);
$pdf->SetXY(150, 22);
$pdf->Write(0, $sola_mail, '', 1, 'R', true, 0, false, false, 0);
$pdf->SetXY(150, 25);
$pdf->Write(0, 'tel.: '.$sola_tel, '', 1, 'R', true, 0, false, false, 0);
*/

$pdf->SetFont('freeserif', 'I', 10,'',false);

//$pdf->SetXY(10, 20);
$pdf->Image($logotip, 187, 10, 12,0, '', '', '', false, 100, '', false, false, 0);
//$pdf->SetXY(110, 200);

// set cell padding
$pdf->setCellPaddings(1, 0.5, 0.5, 0.5);

// set cell margins
$pdf->setCellMargins(1, 1, 0.5, 0.5);

$pdf->SetFillColor(255, 255, 255);
//$style3 = array('width' => 1, 'cap' => 'round', 'join' => 'round', 'dash' => '2,10', 'color' => array(255, 0, 0));
$pdf->Line(15,31,200,31);

$pdf->SetY(31);
//$pdf-> getPageWidth()
$pdf->SetFont('freeserif', 'I', 6,'',false);

$pdf->MultiCell(151, 2, 'Učne oblike: '.$ucne_oblike, 1, 'L', 1, 1, '', '', true);
//$pdf->Ln(6,false);

// set cell margins
$pdf->setCellMargins(1, 0.5, 0.5, 0.5);
//$pdf->SetFillColor(255, 155, 127);
$pdf->MultiCell(151, 2, 'Učne metode: '.$ucne_metode, 1, 'L', 1, 1, '', '', true);
//$pdf->Ln(6,false);

//$tempY=$pdf->GetY();
$pdf->SetFont('freeserif', 'I', 6,'',false);
$pdf->MultiCell(151, 3, "Prilagoditve otrok: ".$prilagoditve_otork, 1, 'L', 1, 1, '', '', true);
//$pY=$pdf->GetY();

$pdf->setCellMargins(1, 0.1, 0, 0);
$pdf->SetFont('freeserif', 'I', 6,'',false);
$pdf->MultiCell(151, 5, "Legenda prilagoditev otrok:\n".$legenda_prilagoditev, 0, 'L', 1, 1, '', '', true);
//$pdf->SetY($tempY);
//$pdf->SetX(126);
//$pdf->SetFillColor(255, 155, 127);
//$pdf->Ln(6,false);
$pdf->Ln(1,false);

$uX=$pdf->GetX();
$uY=$pdf->Gety();

$pdf->write2DBarcode(str_replace(array(' , ',' ,',', ',','),"\n",$ucni_pripomocki), 'QRCODE,L', 169, 32, 30, 30, $style, 'N');


$pdf->SetFont('freeserif', 'I', 8,'',false);

$pdf->SetFillColor(205, 255, 127);
$pdf->SetX(168);
$pdf->MultiCell(30, 5, 'Učni pripomočki: '.$ucni_pripomocki, 0, 'L', 1, 1, '', '', true);
$qY=$pdf->Gety();



if($qY>$uY){
    if($qY>$pY){
    $pdf->SetXY($uX,$qY);
    }else{
    $pdf->SetXY($uX,$pY);
    }
}else{
    if($uY>$pY){
    $pdf->SetXY($uX,$uY);
    }else{
    $pdf->SetXY($uX,$pY);
    }
}



$pdf->SetFillColor(255, 255, 127);
$pdf->MultiCell(0, 5, 'Kaj počnejo učenci / Opombe: '.$opombe_realizacije, 0, 'L', 1, 1, '', '', true);
$pdf->Ln(1,false);

$pdf->SetFont('freeserif', '', 8,'',false);





$Parsedown = new Parsedown();
//$html = file_get_contents($potek_ure);
if($potek_ure!=''){
try{
 $markdown=file_get_contents($potek_ure);
 if($markdown===false){$podrobno='<h1><b>URL priprave ni veljaven ali stran nedosegjiva</b></h1>';
    }else{
  $podrobno = $Parsedown->text($markdown);
  }
  }catch(Exception $e){
    $podrobno = '<h1><b>'.$e->getMessage().'</b></h1>';
    }
}else{
$podrobno='<h1><b>Manjka priprava</b></h1>';
}

// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// get current vertical position
//$y = $pdf->getY();

$pdf->SetDrawColorArray(0, 0, 0);

// set color for background
$pdf->SetFillColor(235, 235, 235);

// set color for text
$pdf->SetTextColor(0, 0, 0);

// write the second column
$pdf->writeHTMLCell(0, '', '', '','<i>Učni cilji in potek ure:</i>'.$podrobno, 1, 1, 1, true, 'J', true);


}
}
}
// reset pointer to the last page

$pdf->lastPage();
// ---------------------------------------------------------
//ob_end_clean();
//Close and output PDF document
$pdf->Output('priprava.pdf', 'I');

?>
