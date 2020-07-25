<?php

// extend TCPF with custom functions



if (($_POST["geslo"]!="vov.si")&&($_POST["geslo"]!="vov.si+")){
echo "
<html>
<head>
<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />
</head>
<body>
Napačno ste odgovorili na vprašanje.
<body></html>
";}else{

//EASTER EGG ;)
$egg=false;
if ($_POST["geslo"]=="vov.si+"){$egg=true;}

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);*/

require_once('tcpdf_include.php');
require_once('pozicije.php');



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
/*
$type = explode(".",$_FILES['userfile']['name']);

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
 
$lines = explode(PHP_EOL, $CsvString);
$Data = array();
foreach ($lines as $line) {
    $Data[] = str_getcsv($line,$delilec);
}
*/
/////

/*print_r(array_values($Data));
echo '<br><br>';
*/
//$kolicina= count($Data)-1;

//if ($kolicina>1){

//echo $kolicina;

  /*for ($x=0;$x<$kolicina;$x++){
    echo '<br>'.$Data[$x][1];
  }*/
  


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Miha Kočar');
$pdf->SetTitle('Avtomatizacija tiskanja priprav OŠ');
$pdf->SetSubject('PHP verzija');
$pdf->SetKeywords('OŠ,priprava,printanje');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(0,0);//(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(false);//(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

 //set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/slv.php')) {
	require_once(dirname(__FILE__).'/lang/slv.php');
	$pdf->setLanguageArray($l);
}


// ---------------------------------------------------------


// add a page

$pdf->SetTextColor(180,20,30);
$pdf->SetDrawColor(170);

$pdf->SetFont('freeserif', 'B', 20,'',false); //nastavi false če hočeš da se da editirat tudi če oseba nima fonta

$regexfilter='/[^\p{Latin}\d\s\p{P}]|[*]|(INSERT|DROP|SELECT|http|https|ftp)/u';

/*
$PrejRazredOddelek=$Data[1][2]; //hrani kateri razred je bil na listu prej - za vmesni beli list
$PrejRazredOddelek=trim($PrejRazredOddelek); //odstrani presledke na začetku in koncu
$PrejRazredOddelek=preg_replace($regexfilter, '', $PrejRazredOddelek); //manjša injection zaščita
*/

  //for ($x=1;$x<$kolicina;$x++){
  
  error_reporting(-1);
ini_set('display_errors', 'On');

$pdf->AddPage();

$Razred=3;

$te=$pdf->Image('3R.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

$ImePriimek='ottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat ottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nottom=8\nLorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue.\nvel, laoreet vitae augue.\n'.$te;//$Data[$x][0].' '.$Data[$x][1];
$ImePriimek=trim($ImePriimek); //odstrani presledke na začetku in koncu
$ImePriimek=preg_replace($regexfilter, '', $ImePriimek); //manjša injection zaščita







$pdf->SetFont('freeserif', $Polje_ImePriimek[$Razred]['t'], $Polje_ImePriimek[$Razred]['f'],'',false);
$pdf->SetXY($Polje_ImePriimek[$Razred]['x'],$Polje_ImePriimek[$Razred]['y'],true);
$pdf->Multicell($Polje_ImePriimek[$Razred]['w'], $Polje_ImePriimek[$Razred]['h'], $ImePriimek , $Okvir, $Polje_ImePriimek[$Razred]['p'],false,1,'','', true, 0,false,false,$Polje_ImePriimek[$Razred]['h']+1,'B',true);



// reset pointer to the last page
$pdf->lastPage();



//Close and output PDF document
$pdf->Output('priznanja_bralna.pdf', 'I');//D za vsiljen download I za odprtje v brskaniku



}

?>
