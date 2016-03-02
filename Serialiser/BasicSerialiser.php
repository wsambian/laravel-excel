<?php
namespace Cyberduck\LaravelExcel\Serialiser;

use Illuminate\Database\Eloquent\Model;
use Cyberduck\LaravelExcel\Contract\SerialiserInterface;

class BasicSerialiser implements SerialiserInterface
{
    public function getData(Model $data)
    {
        return $data->toArray();
    }

    public function getHeaderRow()
    {
        return [];
    }
}
