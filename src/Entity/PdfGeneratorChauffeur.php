<?php
// src/Chauffeur/PdfGeneratorChauffeur.php

namespace App\Entity;

use TCPDF;

class PdfGeneratorChauffeur

    {
        public function generatePdf($html)
        {
            $pdf = new TCPDF();
            $pdf->AddPage();
            // Add logo to the PDF
            $image_file = 'public/images/logo.jpg';
            $pdf->Image($image_file, 10, 10, '', '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
    
            $pdf->writeHTML($html, true, false, true, false, '');
            return $pdf->Output('document.pdf', 'S');
        }
    }


?>

