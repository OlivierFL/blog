<?php

namespace App\Core\Service;

use Exception;

class FileUploader
{
    public const IMAGE = 'image';
    public const FILE = 'file';
    private const UPLOAD_MAX_SIZE = 16777220;
    private const UPLOAD_DIR = '..'.\DIRECTORY_SEPARATOR.'public'.\DIRECTORY_SEPARATOR.'img';
    private array $imageMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];
    private string $fileMimeTypes = 'application/pdf';

    /**
     * @param array  $file
     * @param string $context
     *
     * @throws Exception
     *
     * @return string
     */
    public function upload(array $file, string $context): string
    {
        if (self::IMAGE === $context) {
            try {
                return $this->uploadImage($file);
            } catch (Exception $e) {
                throw new Exception('Erreur lors du téléversement de l\'image');
            }
        }

        if (self::FILE === $context) {
            try {
                return $this->uploadFile($file);
            } catch (Exception $e) {
                throw new Exception('Erreur lors du téléversement du fichier');
            }
        }

        throw new Exception('Contexte invalide : '.$context);
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

    /**
     * @param array $file
     *
     *@throws Exception
     *
     * @return string
     */
    private function uploadImage(array $file): string
    {
        if (0 === $file['error']) {
            if (!\in_array($file['type'], $this->imageMimeTypes, true)) {
                throw new Exception('Type de fichier invalide');
            }

            try {
                return $this->uploadToDir($file);
            } catch (Exception $e) {
                throw $e;
            }
        }

        throw new Exception($file['error']);
    }

    /**
     * @param array $file
     *
     *@throws Exception
     *
     * @return string
     */
    private function uploadFile(array $file): string
    {
        if (0 === $file['error']) {
            if ($this->fileMimeTypes !== $file['type']) {
                throw new Exception('Type de fichier invalide');
            }

            try {
                return $this->uploadToDir($file);
            } catch (Exception $e) {
                throw $e;
            }
        }

        throw new Exception($file['error']);
    }

    /**
     * @param array $file
     *
     * @throws Exception
     *
     * @return string
     */
    private function uploadToDir(array $file): string
    {
        if ($file['size'] > self::UPLOAD_MAX_SIZE) {
            throw new Exception('Le poids du fichier est supérieur au poids maximal accepté, veuillez réessayer');
        }
        $tempFilename = explode('.', $file['name']);
        $filename = round(microtime(true)).'.'.end($tempFilename);

        if (move_uploaded_file($file['tmp_name'], self::UPLOAD_DIR.\DIRECTORY_SEPARATOR.basename($filename))) {
            return $filename;
        }

        throw new Exception('Erreur lors de l\'enregistrement de l\'image');
    }
}
