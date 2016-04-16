<?php
namespace Cyberduck\LaravelExcel\Contract;

use Illuminate\Database\Eloquent\Collection;

interface ExporterInterface
{
    public function load(Collection $data);
    public function setSerialiser(SerialiserInterface $serialiser);
    public function save($filename);
    public function stream($filename);
}
