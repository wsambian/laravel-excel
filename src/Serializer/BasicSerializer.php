<?php
namespace Cyberduck\LaravelExcel\Serializer;

use Illuminate\Database\Eloquent\Model;
use Cyberduck\LaravelExcel\Contract\SerializerInterface;

class BasicSerializer implements SerializerInterface
{
    public function getData(Model $data)
    {
        return $data->toArray();
    }
}
