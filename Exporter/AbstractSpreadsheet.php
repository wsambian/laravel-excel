<?php
namespace Cyberduck\LaravelExcel\Exporter;

use Illuminate\Database\Eloquent\Collection;
use Box\Spout\Writer\WriterFactory;
use Cyberduck\LaravelExcel\Serialiser\BasicSerialiser;
use Cyberduck\LaravelExcel\Contract\SerialiserInterface;
use Cyberduck\LaravelExcel\Contract\ExporterInterface;

abstract class AbstractSpreadsheet implements ExporterInterface
{
    protected $data;
    protected $type;
    protected $serialiser;

    public function __construct()
    {
        $this->data = [];
        $this->type = $this->getType();
        $this->serialiser = new BasicSerialiser();
    }


    public function load(Collection $data)
    {
        $this->data = $data;
        return $this;
    }

    public function setSerialiser(SerialiserInterface $serialiser)
    {
        $this->serialiser = $serialiser;
        return $this;
    }

    abstract public function getType();

    public function save($filename)
    {
        $writer = $this->create();
        $writer->openToFile($filename);
        $writer = $this->makeRows($writer);
        $writer->close();
    }

    public function stream($filename)
    {
        $writer = $this->create();
        $writer->openToBrowser($filename);
        $writer = $this->makeRows($writer);
        $writer->close();
    }

    protected function create()
    {
        return WriterFactory::create($this->type);
    }

    protected function makeRows($writer)
    {
        $headerRow = $this->serialiser->getHeaderRow();
        if (!empty($headerRow)) {
            $writer->addRow($headerRow);
        }
        foreach ($this->data as $record) {
            $writer->addRow($this->serialiser->getData($record));
        }
        return $writer;
    }
}
