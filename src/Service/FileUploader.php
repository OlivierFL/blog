<?php

namespace App\Service;

use Exception;

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
     * @throws Exception
     *
     * @return mixed
     */
    public function checkFile(array $file, string $type): array
    {
        // Check if file MIME type is valid
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (false === $fileMimeType) {
            throw new Exception('Type de fichier invalide');
        }

        // Check if file MIME type is accepted
        if (!\in_array($fileMimeType, self::IMAGE === $type ? $this->imageMimeTypes : $this->fileMimeTypes, true)) {
            throw new Exception('Type de fichier non accepté');
        }

        // Check file size
        if ($file['size'] > self::UPLOAD_MAX_SIZE) {
            throw new Exception('Le poids du fichier est supérieur au poids maximal accepté, veuillez réessayer');
        }

        // Rename uploaded file
        $tempFilename = explode('.', $file['name']);
        $file['name'] = round(microtime(true)).'.'.end($tempFilename);

        return $file;
    }

    /**
     * @param array $file
     *
     * @throws Exception
     *
     * @return string
     */
    public function upload(array $file): string
    {
        if (move_uploaded_file($file['tmp_name'], self::UPLOAD_DIR.\DIRECTORY_SEPARATOR.basename($file['name']))) {
            return $file['name'];
        }

        throw new Exception('Erreur lors de l\'enregistrement de l\'image');
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
    public function delete(string $fileName): bool
    {
        return unlink(self::UPLOAD_DIR.\DIRECTORY_SEPARATOR.$fileName);
    }
}
