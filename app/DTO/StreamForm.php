<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class StreamForm extends DataTransferObject
{
    public string $name;

    public string $description;

    public $preview;
}
