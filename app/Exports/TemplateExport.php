<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TemplateExport implements FromCollection
{
    public function __construct(
        public $data
    ) {}

    public function collection()
    {
        return $this->data;
    }
}
