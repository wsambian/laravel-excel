<?php
namespace Cyberduck\LaravelExcel;

use Illuminate\Database\Eloquent\Model;
use Cyberduck\LaravelExcel\Contract\SerializerInterface;
use Ximbio\Repositories\ProductRepository;

class ProductSerializer implements SerializerInterface
{
    protected $repository;
    protected $fieldkeys;

    public function __construct(ProductRepository $repository, $fieldkeys)
    {
        $this->repository = $repository;
        $this->fieldkeys = $fieldkeys;
    }

    public function getData(Model $data)
    {
        $row = [];
        $inventors     = $this->repository->inventors($data->id, true)->lists('name');
        $suppliers     = $this->repository->suppliers($data->id, true)->lists('name');
        $organisations = $data->getAllUniversities()->lists('name');
        $publications  = $data->getAllPublications()->lists('title');
        $tto           = isset($data->tto->name) ? $data->tto->name : '';

        $product_image = '';
        if ($data->getAllProductImages()->count()) {
            $product_image = \Helpers::cloudinary($data->getAllProductImages()->first());
        }

        $related = $this->repository->related($data->id, true)->lists('title');

        $row[] = $data->id;
        $row[] = $data->type->name;
        $row[] = $data->title;
        $row[] = $data->clone_name;
        $row[] = $data->tech_id;
        $row[] = $data->inteum_id;
        $row[] = ($data->status ? $data->status->name : null);
        $row[] = implode(' ; ', $inventors);
        $row[] = implode(' ; ', $suppliers);
        $row[] = implode(' ; ', $organisations);
        $row[] = implode(' ; ', $publications);
        $row[] = $tto;
        $row[] = $product_image;
        $row[] = implode(' ; ', $related);
        $row[] = date('Y-m-d', $data->created_at->timestamp);
        $row[] = date('Y-m-d', $data->updated_at->timestamp);

        $offset = 16;

        $row = array_pad($row, count($this->fieldkeys) + $offset, '');

        $fields = $this->repository->getFieldsWithValues($data);

        foreach ($fields as $f_key => $field) {
            $letter = array_search($field['name'], $this->fieldkeys) + $offset;

            $cellContent = '';

            switch ($field['type']) {
                case 'text':
                case 'textarea':
                    $cellContent = $field['value'];
                    break;
                case 'dropdown':
                    if (isset($field['relationship'])) {
                        if ($field['value'] != 0 && $field['value'] != 'Please select') {
                            $cellContent = $field['relationship'][$field['value']];
                        } else {
                            $cellContent = '';
                        }
                    } elseif (isset($field['static_options'])) {
                        $cellContent = $field['value'];
                    }
                    break;
                case 'checkbox':
                    if (isset($field['relationship']) && $field['value']) {
                        $cellContentArr = [];
                        foreach ($field['value'] as $id) {
                            if ($id) {
                                if (isset($field['relationship'][$id])) {
                                    $cellContentArr[] = $field['relationship'][$id];
                                }
                            }
                        }
                        $cellContent = implode(' ; ', $cellContentArr);
                    } else {
                        $cellContent = $field['value'] ? 'Yes' : 'No';
                    }
                    break;

            }

            $row[$letter] =  $cellContent;
        }

        array_walk($row, function (&$item, $key) {
            if (is_string($item)) {
                $item = str_replace("\n", '', $item);
            }
        });

        return $row;
    }
}
