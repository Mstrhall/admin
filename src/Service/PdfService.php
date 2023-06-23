<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
 private $domPDf;
 public function __construct()
 {
     $this->domPDf=new Dompdf();

     $pdfOptions= new Options();
     $pdfOptions->set('defaultFont','Garamond');
     $pdfOptions->set('A4');
     $this->domPDf->setOptions($pdfOptions);
 }

 public function showPdf($code){
     $this->domPDf->loadHtml($code);
     $this->domPDf->render();
     $this->domPDf->stream("fichier.pdf",[
         'Attachement'=>false
     ]);
 }
 public function generateBinaryPdf($code){
     $this->domPDf->loadHtml($code);
     $this->domPDf->render();
     $this->domPDf->output();

 }
}