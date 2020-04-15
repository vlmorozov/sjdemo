<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 15.02.20
 * Time: 21:53
 */

namespace App\Utils;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FormFilters
{
    protected $filterFieldName = 'filters';
    /*
     * @var Request $request
     */
    private $request;

    private $action;

    private $fields = [];

    private $data = [];

    /**
     * FormFilters constructor.
     * @param $action
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = $request->input($this->filterFieldName);
        if (isset($this->data['formButton']) && $this->data['formButton']=='clear') {
            $this->data = [];
        }
//        dd($this->data);
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function addField($type, $name, $value = null, $label = '', $params = [])
    {
        $this->fields[] = [
            'type' => $type,
            'name' => $name,
            'formName' => "filters[{$name}]",
            'value' => $value,
            'label' => $label,
            'params' => $params
        ];

        return $this;
    }

    public function addText($name, $value = null, $label = '', $params = [])
    {
        return $this->addField('text', $name, $value, $label, $params);
    }

    public function getValues()
    {
        return (new Collection($this->fields))
            ->pluck('value', 'formName')
            ->all();
    }

    public function render()
    {
        return view('common/filters', [
            'action' => $this->action,
            'fields' => $this->fields
        ]);
    }
}
