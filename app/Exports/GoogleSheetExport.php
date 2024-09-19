<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class GoogleSheetExport implements FromArray
{
    protected $data;

    // Inject data dari controller ke export class
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    // Method untuk menampilkan array data yang akan diekspor
    public function array(): array
    {
        return $this->data;
    }
}
