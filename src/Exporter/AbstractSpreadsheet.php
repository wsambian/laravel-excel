<?php
namespace Cyberduck\LaravelExcel\Exporter;

use Illuminate\Database\Eloquent\Collection;
use Box\Spout\Writer\WriterFactory;
use Cyberduck\LaravelExcel\Serializer\BasicSerializer;
use Cyberduck\LaravelExcel\Contract\SerializerInterface;
use Cyberduck\LaravelExcel\Contract\ExporterInterface;

abstract class AbstractSpreadsheet implements ExporterInterface
{
    protected $data;
    protected $type;
    protected $headers;
    protected $serializer;

    public function __construct()
    {
        $this->data = [];
        $this->type = $this->getType();
        $this->serializer = new BasicSerializer();
        $this->headers = [];
    }


    public function load(Collection $data, $headers = [])
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    abstract public function getType();

    public function save($filename)
    {
        $writer = $this->create();
        $writer->openToFile($filename);
        $writer->addRow($this->headers);
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
        foreach ($this->data as $record) {
            $writer->addRow($this->serializer->getData($record));
        }
        return $writer;
    }
}
