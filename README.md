# Laravel Excel  

[![Latest Stable Version](https://poser.pugx.org/cyber-duck/laravel-excel/v/stable)](https://packagist.org/packages/cyber-duck/laravel-excel)
[![Total Downloads](https://poser.pugx.org/cyber-duck/laravel-excel/downloads)](https://packagist.org/packages/cyber-duck/laravel-excel)
[![License](https://poser.pugx.org/cyber-duck/laravel-excel/license)](https://raw.githubusercontent.com/Cyber-Duck/laravel-excel/master/LICENSE)

Laravel Excel is a pachage to export and import excel files using Eloquent Collections in Laravel (5.* and 4.*).  
It's based on [box/spout](https://github.com/box/spout).

Author: [Simone Todaro](https://github.com/SimoTod)

Contributors: [Clément Blanco](https://github.com/Claymm)

Made with :heart: by [Cyber-Duck Ltd](http://www.cyber-duck.co.uk)

[Installation](#installation)  
[Export Excel](#export-excel)  
[Import Excel](#import-excel)  
[Different formats](#different-formats)  

## Installation 
```
$ composer require cyber-duck/laravel-excel
```

Register the service provider in config/app.php adding *Cyberduck\LaravelExcel\ExcelServiceProvider* to the provider array.

Note. If you are on Laravel 4, use *Cyberduck\LaravelExcel\ExcelLegacyServiceProvider*

## Export Excel

### Generate and download an excel file
Add  
```
use Exporter;
```  
to your controller.

In your controler function, create a new excel file.
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

In your controler function, create a new excel file.
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
By default, every element of the Collection becomes a row and every unprotected field of the Model becomes a cell.  
No headers row is printed.

To change this behaviour, create a class which extends *Cyberduck\LaravelExcel\Contract\SerialiserInterface* and implements the methods *getHeaderRow()* and *getData(Model $data)*.  
*getHeaderRow()* must return an array of string where every element is a cell of the first row. To not print the header row, simply return a void array *[]*.  
*getData(Model $data)* must return an array of string, and every elements is a cell.

Example
```
namespace App\Serialisers;

use Illuminate\Database\Eloquent\Model;
use Cyberduck\LaravelExcel\Contract\SerialiserInterface;

class ExampleSerialiser implements SerialiserInterface
{
    public function getData(Model $data)
    {
        $row = [];

        $row[] = $data->field1;
        $row[] = $data->relationship->field2;

        return $row;
    }

    public function getHeaderRow()
    {
        return [
            'Field 1',
            'Field 2 (from a relationship)'
        ];
    }
}
```

## Import Excel
Add  
```
use Importer;
```  
to your controller.

In your controler function, import an excel file.
```
$excel = Importer::make('Excel');
$excel->load($filepath);  
$collection = $excel->getCollection();  
//dd($collection)
```  

The importer class is fluent, then you can also write  
```
return Importer::make('Excel')->getCollection($filepath)->getCollection();
```

### Advanced usage
By default, every row of the first sheet of the excel file becomes an array and the final result is wraped in a Collection (Illuminate\Support\Collection).  

To import a different sheet, use *setSheet($sheet)*
```
$excel = Importer::make('Excel');
$excel->load($filepath);  
$excel->setSheet($sheetNumber);  
$collection = $excel->getCollection();  
//dd($collection)
```  

To import each row in an Eloquent model, create a class which extends *Cyberduck\LaravelExcel\Contract\ParserInterface* and implements the methods *transform($row)*.  

Example
```
namespace App\Parsers;

use App\Models\YourModel;
use Cyberduck\LaravelExcel\Contract\ParserInterface;

class ExampleSerialiser implements ParserInterface
{
    public function transform($row)
    {
        $model = new YourModel();
        $model->field1 = $row[0];
        $model->field2 = $row[1];
        // We can manunipulate the data before returning the object
        $model->field3 = new \Carbon($row[2]);
        return $model;
    }
}
```

## Different formats
The package supports ODS and CSV files.

### ODS
```
$exporter = Exporter::make('OpenOffice');
$importer = Importer::make('OpenOffice');
```

### CSV
```
$exporter = Exporter::make('Csv');
$importer = Importer::make('Csv');
```
