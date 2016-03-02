<?php
namespace Cyberduck\LaravelExcel\Contract;

use Illuminate\Database\Eloquent\Model;

interface SerialiserInterface
{
    public function getData(Model $data);
    public function getHeaderRow();
}
