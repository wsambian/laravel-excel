<?php
namespace Cyberduck\LaravelExcel\Contract;

use Illuminate\Database\Eloquent\Collection;

interface ExporterInterface
{
    public function load(Collection $data);
    public function setSerializer(SerializerInterface $serializer);
    public function save($filename);
    public function stream($filename);
}
