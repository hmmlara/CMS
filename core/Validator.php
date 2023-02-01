<?php

class Validator
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    // return a boolean value for array validation
    public function validated()
    {
        return $this->is_empty($this->data);
    }

    // return error message if not validated
    public function getErrorMessages()
    {
        $error_data = [];

        if (isset($_FILES)) {

            // getting key from file
            foreach ($_FILES as $key => $value) {
                $file_key = $key;
            }

            if (isset($file_key)) {
                if (empty($_FILES[$file_key]["name"])) {
                    $error_data[$file_key] = "Please nter $key";
                }
            }
        }

        if (!$this->validated()) {
            foreach ($this->data as $key => $value) {
                if (empty($value)) {
                    $error_data[$key] = "Please enter $key";
                    // "<p>".$error_data[$key] = "Please enter $key"."</p>";
                }
            }
        }
        return $error_data;
    }

    // check if empty or not
    private function is_empty($data)
    {
        $is_empty = true;
        foreach ($data as $val) {
            if (is_array($val)) {
                $is_empty = $this->is_empty($val);
            } else {
                if (empty($val)) {
                    $is_empty = false;
                    break;
                }
            }
        }
        return $is_empty;
    }
}
