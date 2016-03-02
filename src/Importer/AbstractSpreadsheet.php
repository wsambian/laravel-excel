<?php
namespace Cyberduck\LaravelExcel\Importer;

use Illuminate\Database\Eloquent\Collection;
use Box\Spout\Reader\ReaderFactory;
use Cyberduck\LaravelExcel\Parser\BasicParser;
use Cyberduck\LaravelExcel\Contract\ParserInterface;
use Cyberduck\LaravelExcel\Contract\ImporterInterface;

abstract class AbstractSpreadsheet implements ImporterInterface
{
    protected $path;
    protected $type;
    protected $parser;
    protected $sheet;

    public function __construct()
    {
        $this->path = '';
        $this->sheet = 0;
        $this->type = $this->getType();
        $this->parser = new BasicParser();
    }

    public function load($path)
    {
        $this->path = $path;
    }

    public function setSheet($sheet)
    {
        $this->sheet = $sheet;
    }

    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    abstract public function getType();

    public function getCollection()
    {
        $reader = $this->create();
        $reader->open();
        $collection = $this->parseRows($reader);
        $reader->close();
        return $collection;
    }

    protected function create()
    {
        return ReaderFactory::create($this->type);
    }

    protected function makeRows($writer)
    {
        $collection = [];
        foreach ($reader->getSheetIterator() as $index => $sheet) {
            if ($index !== $this->sheet) {
                continue;
            }
            foreach ($sheet->getRowIterator() as $row) {
                $collection[] = $this->parser->getData($row);
            }
        }
        return collect($collection);
    }
}
