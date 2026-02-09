<?php

namespace App;

class Uploader
{
    private $uploadDir;
    private $allowedTypes;
    private $maxSize;

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = $uploadDir;
        // Разрешаем только картинки и PDF
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        // Максимум 5 MB
        $this->maxSize = 5 * 1024 * 1024; 
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload(array $file): array
    {
        try {
            $this->validate($file);
            
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            // Генерируем уникальное имя, чтобы файлы не перезаписывались
            $filename = uniqid('file_', true) . '.' . $extension;
            $destination = $this->uploadDir . $filename;

            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                throw new \Exception('Failed to move uploaded file.');
            }

            return [
                'success' => true,
                'path' => $destination,
                'message' => 'File uploaded successfully!'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function validate(array $file): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Upload error code: ' . $file['error']);
        }

        if ($file['size'] > $this->maxSize) {
            throw new \Exception('File is too large. Max size is 5MB.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $this->allowedTypes)) {
            throw new \Exception('Invalid file type. Only JPG, PNG, GIF and PDF allowed.');
        }
    }
}