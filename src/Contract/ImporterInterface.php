<?php
namespace Cyberduck\LaravelExcel\Contract;

use Illuminate\Database\Eloquent\Collection;

interface ExporterInterface
{
    public function load($path);
    public function setParser(ParserInterface $parser);
    public function getCollection();
}
