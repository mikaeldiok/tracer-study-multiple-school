<?php

namespace App\Overrides;

class Zip extends \ZanySoft\Zip\Zip{

    /**
     * Class constructor
     *
     * @param string $zip_file ZIP file name
     *
     */
    public function __construct($zip_file)
    {
        if (empty($zip_file)) {
            throw new \Exception($this::getStatus(\ZipArchive::ER_NOENT));
        }

        if(is_string($zip_file) == true) {
            $this->zip_file = $zip_file;
        }

    }

}
