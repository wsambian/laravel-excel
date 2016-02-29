<?php
namespace Cyberduck\LaravelExcel\Parser;

use Illuminate\Database\Eloquent\Model;

class BasicParser implements ParserInterface
{
    public function getData($row)
    {
        return $row;
    }
}
