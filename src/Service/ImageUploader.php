<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $dest;
    private $slugger;
    private $logger;
    public function __construct($dest, SluggerInterface $slugger, LoggerInterface $logger){
        $this->dest = $dest;
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    public function upload($fileToStore){
        $fileName = pathinfo($fileToStore->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($fileName);
        $fileNameToStore = $safeFileName."-".uniqid().".".$fileToStore->guessExtension();
        try {
            $fileToStore->move($this->dest,$fileNameToStore);
        }catch(FileException $e){
            $this->logger->info($e->getMessage());
        }
        return $fileNameToStore;
    }
}