<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Model;

class DataTable extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $data, 
        public array $headers, 
        public $btn_edit = false, 
        public $btn_delete = false, 
        public $btn_view = false
    ) {
        $keys = array_keys($headers);
        
        foreach($keys as &$key){
            if(is_numeric($key)){
                $key = is_array($headers[$key]) ? $headers[$key]['label'] : $headers[$key];
            }
        }

        if($data instanceof Model){
            $this->data = $data->get();
        }elseif(is_string($data) && class_exists($data) && is_subclass_of($data, Model::class)){
            $this->data = $data::select($keys)->get();
        }elseif($data instanceof \Illuminate\Database\Eloquent\Builder){
            $this->data = $data->get();
        }elseif(is_array($data)){
            $this->data = collect($data);
        }else{
            throw new \Exception("DataTable: Tipo de dado inválido para 'data' ");
        }

    }
    
    public function createRoute($type, $object){
        if($type == 'edit' && $this->btn_edit){
            return route($this->btn_edit, $object);
        }elseif($type == 'delete' && $this->btn_delete){
            return route($this->btn_delete, $object);
        }elseif($type == 'view' && $this->btn_view){
            return route($this->btn_view, $object); 
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data-table');
    }
}
