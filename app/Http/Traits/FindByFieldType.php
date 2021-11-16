<?php

namespace App\Http\Traits;

use Schema;

trait FindByFieldType
{
    /**
     * From list info input on screen 
     *  => Get info if have value input
     * 
     * @param array $params []
     *
     * @return collection
     */
    public static function findByParams(array $params = [])
    {
        $table = static::$table_static;
        if (empty($table)) {
            return [];
        }
        // just get key search diff with null, false, ''
        $params = array_filter(
            $params, 
            function ($value) {
                return ($value !== null && $value !== false && $value !== ''); 
            }
        );
        $model = static::select($table.'.*');
        if (Schema::hasColumn($table, 'is_deleted')) {
            $model = $model->where($table.'.is_deleted', 0);
        }
        if (empty($params)) {
            return $model;
        }

        $list_field   = Schema::getColumnListing($table);
        $value_search = [];
        foreach ($params as $key => $value) {
            if (!in_array($key, $list_field)) {
                continue;
            }
            $value_search[$key] = $value;
        }

        //search all other params
        foreach ($value_search as $key => $value) {
            if ($key == 'code') {
                $model->where($table.'.'.$key, 'LIKE', '%'. $value . '%');
                continue;
            }
            switch (Schema::getColumnType($table, $key)) {
            case 'integer':
            case 'decimal':
            case 'boolean':
                $model->where($table.'.'.$key, $value);
                break;
            case 'text':
            case 'string';
                $model->where($table.'.'.$key, 'LIKE', '%' . $value . '%');
                break;
            default:
                $model->where($table.'.'.$key, 'LIKE', '%' . $value . '%');
            }
        }

        return $model;
    }
}
