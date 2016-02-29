<?php
namespace Cyberduck\LaravelExcel\Contract;

use Illuminate\Database\Eloquent\Model;

interface SerializerInterface
{
    public function getData(Model $data);
}
