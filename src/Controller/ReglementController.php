<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpWord\IOFactory;

class ReglementController extends AbstractController
{
    #[Route('/reglement', name: 'app_reglement')]
    public function getReglementAction()
    {
        $fileName = $this->getParameter('kernel.project_dir') . '/public/file.docx';
        ;

        $phpWord = IOFactory::load($fileName);
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });
        #indiquer au navigateur le type du contenu de la reponse
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        #indiquer la possibilité de telechargement
        $response->headers->set('Content-Disposition', 'attachment;filename="file.docx"');
        #pour indiquer que le fichier ne doit pas rester en cache du navigateur
        $response->headers->set('Cache-Control', 'max-age=0');

        $response->send();
    }
}