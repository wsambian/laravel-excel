<?php
namespace Cyberduck\LaravelExcel\Contract;

use Illuminate\Database\Eloquent\Collection;

interface ImporterInterface
{
    public function load($path);
    public function setParser(ParserInterface $parser);
    public function getCollection();
}
