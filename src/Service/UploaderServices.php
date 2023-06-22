<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderServices
{public function __construct(private  SluggerInterface $slugger){

}
public function uploadImage(UploadedFile $file,string $dossierDeLimage){

    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    // this is needed to safely include the file name as part of the URL
    $safeFilename = $this->slugger->slug($originalFilename);
    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

    // Move the file to the directory where brochures are stored
    try {
        $file->move(
           $dossierDeLimage,
            $newFilename
        );
    } catch (FileException $e) {
        // ... handle exception if something happens during file upload
    }
    return $newFilename;

    // updates the 'brochureFilename' property to store the PDF file name
    // instead of its contents


}
}