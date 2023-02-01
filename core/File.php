<?php

class File
{
    private $file;
    private $allowed_ext = [];

    private $message;

    public function __construct($file)
    {
        $this->file = $file;
    }

    // get file name
    public function getOriginalName()
    {
        return $this->file["name"];
    }

    // for allowing file extensions
    public function allowedExt(array $exts)
    {
        $this->allowed_ext = $exts;
    }

    // for uploading file
    public function move($path, $filename)
    {

        // file path name for saving files
        $target_file = $path . $filename;

        // get file extension of target file
        $fileExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // get file size
        $fileSize = $this->file["size"];

        // check file already exits
        if (file_exists($target_file)) {
            $this->message = "File already exists";
            return false;
        }

        // check file size
        if ($fileSize > 5000000) {
            $this->message = "Your file is too large for system";
            return false;
        }

        // check file extension
        if(!in_array($fileExt,$this->allowed_ext)){
            $this->message = "Only ".implode(',',$this->allowed_ext)." are allowed.";
            return false;
        }
        return move_uploaded_file($this->file["tmp_name"], $target_file);
    }

    public function getErrorMessage()
    {
        return $this->message;
    }

    // class method

    // check target file exits or not
    public static function exists($target_file){
        return file_exists($target_file);
    }

    // delete target file
    public static function delete($target_file){
        unlink($target_file);
    }
}
