# Laravel Excel  

[Installation](#installation)  
[Export Excel](#export-excel)  
[Import Excel](#import-excel)  
[Different formats](#different-formats)  

This package provides a way to export an Eloquent collection as an excel file and to import a Excel file as an Eloquent collection. It's based on [box/spout](https://github.com/box/spout).

## Installation  
The package must be installed directly from [Composer](https://getcomposer.org/).  
Run the following command:  
```
$ composer require cyberduck/laravel-excel
```

To make Facades available, register the service provider in config/app.php adding *Cyberduck\LaravelExcel\ExcelServiceProvider* to the provider array.

Note. If you are on Laravel 4, use *Cyberduck\LaravelExcel\ExcelLegacyServiceProvider*

## Export Excel

### Generate and download an excel file
Add  
```
use Exporter;
```  
to your controller.

In your action, add
```
$excel = Exporter::make('Excel');
$excel->load($yourCollection);  
return $excel->stream($yourFileName);  
```  

The exporter class is fluent, then you can also write  
```
return Exporter::make('Excel')->load($yourCollection)->stream($yourFileName);
```

### Generate and save an excel file
Add  
```
use Exporter;
```  
to your controller.

In your action, add
```
$excel = Exporter::make('Excel');
$excel->load($yourCollection);  
return $excel->save($yourFileNameWithPath);  
```  

The exporter class is fluent, then you can also write  
```
return Exporter::make('Excel')->load($yourCollection)->save($yourFileNameWithPath);
```

### Advanced usage
By default, every element of the Collection become a row and every unprotected field of the Model become a cell. No headers row is printed.

To change this behaviour, create a class extending *Cyberduck\LaravelExcel\Contract\SerialiserInterface* and implement the methods *getHeaderRow()* and *getData(Model $data)*.  
*getHeaderRow()* must return an array of string, and every elements is a cell of the first row. To not print the header row, simply return a void array *[]*.  
*getData(Model $data)* must return an array of string, and every elements is a cell of rows after the header.

Example
```
namespace App\Serialiser;

use Illuminate\Database\Eloquent\Model;
use Cyberduck\LaravelExcel\Contract\SerialiserInterface;

class ExampleSerialiser implements SerialiserInterface
{
    public function getData(Model $data)
    {
        $row = [];

        $row[] = $data->field1;
        $row[] = $data->relation->field2;

        return $row;
    }

    public function getHeaderRow()
    {
        return [
            'Field 1',
            'Field 2 (from a relation)'
        ];
    }
}
```

## Import Excel
Coming soon! (In development)

## Different formats
Coming soon!
