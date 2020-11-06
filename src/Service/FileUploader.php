<?php

namespace App\Service;

use App\Exceptions\FileDeleteException;
use App\Exceptions\FileUploadException;

class FileUploader
{
    public const IMAGE = 'image';
    private const UPLOAD_MAX_SIZE = 16777220;
    private const UPLOAD_DIR = '..'.\DIRECTORY_SEPARATOR.'public'.\DIRECTORY_SEPARATOR.'uploads';
    private array $imageMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];
    private array $fileMimeTypes = ['application/pdf'];

    /**
     * @param array  $file
     * @param string $type
     *
     * @throws FileUploadException
     *
     * @return mixed
     */
    public function checkFile(array $file, string $type): array
    {
        $fileToUpload = [];

        // Check if file MIME type is valid
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (false === $fileMimeType) {
            throw new FileUploadException('Type de fichier invalide');
        }

        // Check if file MIME type is accepted
        if (!\in_array($fileMimeType, self::IMAGE === $type ? $this->imageMimeTypes : $this->fileMimeTypes, true)) {
            throw new FileUploadException('Type de fichier non accepté');
        }

        // Check file size
        if ((int) $file['size'] > self::UPLOAD_MAX_SIZE) {
            throw new FileUploadException('Le poids du fichier est supérieur au poids maximal accepté, veuillez réessayer');
        }

        // Rename uploaded file
        $tempFilename = explode('.', $file['name']);
        $fileToUpload['name'] = round(microtime(true)).'.'.end($tempFilename);
        $fileToUpload['location'] = (string) preg_replace('/^\.+/', '', $file['tmp_name']);

        return $fileToUpload;
    }

    /**
     * @param array $file
     *
     * @throws FileUploadException
     *
     * @return string
     */
    public function upload(array $file): string
    {
        if (move_uploaded_file($file['location'], self::UPLOAD_DIR.\DIRECTORY_SEPARATOR.basename($file['name']))) {
            return $file['name'];
        }

        throw new FileUploadException('Erreur lors de l\'enregistrement de l\'image');
    }

    /**
     * @param string $fileName
     *
     * @throws FileDeleteException
     *
     * @return bool
     */
    public function delete(string $fileName): bool
    {
        try {
            return unlink(self::UPLOAD_DIR.\DIRECTORY_SEPARATOR.$fileName);
        } catch (\Exception $e) {
            throw new FileDeleteException('Erreur lors la suppression de l\'image');
        }
    }
}
