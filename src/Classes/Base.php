<?php

namespace Properos\Base\Classes;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Properos\Base\Classes\Paginator;
use Illuminate\Support\Facades\Validator;
use Properos\Base\Exceptions\ApiException;

/**
 * Description of base_class
 *
 * @author Properos
 */
abstract class Base extends Paginator
{
    protected $model;
    protected $mode;
    protected $fillable = [];
    protected $title;

    public function __construct($model, $title)
    {
        $this->mode = $model;
        $this->title = $title;
        $this->init();
    }

    public function init()
    {
        $this->model = new $this->mode();
        $this->init_fillable();
    }

    public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }

    public function createModel($data = [], $rules = [], $messages = [])
    {
        $validation = Validator::make($data, $rules, $messages);

        if ($validation->fails()) {
            throw new ApiException($validation->errors(), '400');
        }

        $model = new $this->mode;

        $data = array_merge($this->fillable, $this->formatting($this->fillable($data)));

        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }

        $model->save();
        return $model;
    }

    public function updateModel($data = [], $rules = [], $messages = [])
    {
        if (!isset($data['id']) || $data['id'] <= 0) {
            throw new ApiException('The field id is required', '400');
        }
        $rules = array_intersect_key($rules, $data);

        $validation = Validator::make($data, $rules, $messages);

        if ($validation->fails()) {
            throw new ApiException($validation->errors(), '400', $data);
        }

        if (isset($data['where']) && count($data['where']) > 0) {
            $model = $this->mode::where('id', $data['id'])->where($data['where'])->first();
        } else {
            $model = $this->mode::find($data['id']);
        }

        if ($model) {
            foreach ($this->formatting($this->fillable($data)) as $key => $value) {
                $model->{$key} = $value;
            }
            $model->save();
            return $model;
        }

        throw new ApiException($this->title . "  could not be updated.", '400', $data);
    }

    public function deleteModel($id, $where = [])
    {
        $model = $this->mode::query();

        if (is_array($where) && count($where) > 0) {
            $model->where($where);
        }

        if (is_array($id) && count($id) > 0) {
            $model->whereIn('id', $id)->delete();
            return true;
        } elseif ($id > 0) {
            $object = $model->where('id', $id)->first();
            if ($object) {
                $object->delete();
                return true;
            } else {
                throw new ApiException($this->title . " not found", '404', ['id' => $id]);
            }
        }
        throw new ApiException($this->title . "  could not be deleted.", '400', ['id' => $id]);
    }

    public function find($options = [], $collection = false)
    {
        return $this->findModel($this->mode::query(), $options, $collection);
    }

    public function findOne($options = [], $collection = true)
    {
        if ($collection) {
            return $this->findModel($this->mode::on(), $options, $collection)->first();
        } else {
            $options['take'] = 1;
            return head($this->findModel($this->mode::on(), $options, $collection));
        }
    }

    /*
     * @param array options [fields => [], where => [], whereIn => [], q => '']
     *
     */

    public function findModel($model, $options = [], $collection = false)
    {

        if (isset($options['withtrashed']) && $options['withtrashed']) {
            $model->withTrashed();
        }

        $model = $this->buildingModel($model, $options);

        if (isset($options['limit'])) {
            $paginator = $model->paginate($options['limit']);
            $this->set_paginator($paginator);
            return $this->transform($paginator->getCollection(), $options, $collection);
        } elseif (isset($options['take'])) {
            $model->take($options['take']);
        }
        return $this->transform($model, $options, $collection);
    }

    public function buildingModel($model, $options = [])
    {
        if (isset($options['fields_raw']) && is_string($options['fields_raw'])) {
            $model->selectRaw($options['fields_raw']);
        }
        if (isset($options['q']) && is_string($options['q']) && $options['q'] != '') {
            if ($this->model->index_fulltext) {
                $indexFields = implode(',', $this->model->searchable);
                $model->whereRaw("MATCH($indexFields) AGAINST(? IN BOOLEAN MODE)", $options['q']);
            } else {
                //$searchable = isset($this->model->searchable) ? $this->model->searchable : [];
                if (isset($options['q_fields']) && count($options['q_fields']) > 0) {
                    $fields = $options['q_fields'];
                } else {
                    $fields = [];
                }

                if (count($fields) > 0) {
                    $q = $this->normalize_query($options['q']);

                    //MSSQL: The concat_ws function requires at least 3 arguments
                    while (count($fields) < 3) {
                        $fields[] = 'NULL';
                    }

                    $model->whereRaw(str_replace("?field?", "CONCAT_WS('|'," . implode(",", $fields) . ")", $q));
                }
            }
        }

        if (isset($options['where']) && count($options['where']) > 0) {
            $this->applyConditions($model, $options['where'], 'where');
        }

        if (isset($options['or_where']) && count($options['or_where']) > 0) {
            $this->applyConditions($model, $options['or_where'], 'orWhere');
        }

        if (isset($options['where_raw']) && count($options['where_raw']) > 0) {
            if (Helper::isAssoc($options['where_raw'])) {
                if (isset($options['where_raw']['sql']) && isset($options['where_raw']['params']) && is_array($options['where_raw']['params'])) {
                    $model->whereRaw($options['where_raw']['sql'], $options['where_raw']['params']);
                }
            } else {
                foreach ($options['where_raw'] as $where) {
                    if (Helper::isAssoc($where)) {
                        if (isset($where['sql']) && isset($where['params']) && is_array($where['params'])) {
                            $model->whereRaw($where['sql'], $where['params']);
                        }
                    } else {
                        $model->whereRaw($where);
                    }
                }
            }
        }

        if (isset($options['func_where']) && count($options['func_where']) > 0) {
            $model->where(function ($query) use ($options) {
                return $this->buildingModel($query, $options['func_where']);
            });
        }

        if (isset($options['where_in']) && count($options['where_in']) > 0) {
            foreach ($options['where_in'] as $field => $value) {
                if (is_string($field)) {
                    if (is_array($value) && count($value) > 0) {
                        $model->whereIn($field, $value);
                    } elseif (is_string($value) || is_numeric($value)) {
                        $model->whereIn($field, [(string)$value]);
                    }
                } elseif (Helper::isAssoc($value)) {
                    if (is_array($value['value']) && count($value['value']) > 0) {
                        $model->whereIn($value['field'], (array) $value['value']);
                    }
                }
            }
        }

        if (isset($options['where_not_in']) && count($options['where_not_in']) > 0) {
            foreach ($options['where_not_in'] as $field => $value) {
                if (is_string($field)) {
                    if (is_array($value) && count($value) > 0) {
                        $model->whereNotIn($field, $value);
                    } elseif (is_string($value) || is_numeric($value)) {
                        $model->whereNotIn($field, [(string)$value]);
                    }
                } elseif (Helper::isAssoc($value)) {
                    if (is_array($value['value']) && count($value['value']) > 0) {
                        $model->whereNotIn($value['field'], (array) $value['value']);
                    }
                }
            }
        }

        if (isset($options['where_null']) && is_array($options['where_null'])) {
            foreach ($options['where_null'] as $where_null) {
                if (is_string($where_null)) {
                    $model->whereNull($where_null);
                }
            }
        }
        if (isset($options['where_not_null']) && is_array($options['where_not_null'])) {
            foreach ($options['where_not_null'] as $where_not_null) {
                if (is_string($where_not_null)) {
                    $model->whereNotNull($where_not_null);
                }
            }
        }

        if (isset($options['with']) && count($options['with']) > 0) {
            $model->with($options['with']);
        }

        if (isset($options['has']) && count($options['has']) > 0) {
            foreach ($options['has'] as $has) {
                if (is_string($has)) {
                    $model->has($has);
                } elseif (is_array($has) && count($has) == 3) {
                    $model->has($has[0], $has[1], $has[2]);
                }
            }
        }
        if (isset($options['doesnt_have']) && count($options['doesnt_have']) > 0) {
            foreach ($options['doesnt_have'] as $doesntHave) {
                if (is_string($doesntHave)) {
                    $model->doesntHave($doesntHave);
                } elseif (is_array($doesntHave) && count($doesntHave) == 3) {
                    $model->doesntHave($doesntHave[0], $doesntHave[1], $doesntHave[2]);
                }
            }
        }

        if (isset($options['where_has']) && count($options['where_has']) > 0) {
            foreach ($options['where_has'] as $whereHas) {
                if (is_array($whereHas) && Helper::isAssoc($whereHas)) {
                    $model->whereHas($whereHas['relationship'], $whereHas['function']);
                }
            }
        }
        if (isset($options['where_doesnt_have']) && count($options['where_doesnt_have']) > 0) {
            foreach ($options['where_doesnt_have'] as $whereDoesntHave) {
                if (is_array($whereDoesntHave) && Helper::isAssoc($whereDoesntHave)) {
                    $model->whereDoesntHave($whereDoesntHave['relationship'], $whereDoesntHave['function']);
                }
            }
        }
        if (isset($options['or_where_has']) && count($options['or_where_has']) > 0) {
            foreach ($options['or_where_has'] as $orWhereHas) {
                if (is_array($orWhereHas) && Helper::isAssoc($orWhereHas)) {
                    $model->orWhereHas($orWhereHas['relationship'], $orWhereHas['function']);
                }
            }
        }
        if (isset($options['where_has_morph']) && count($options['where_has_morph']) > 0) {
            foreach ($options['where_has_morph'] as $whereHasMorph) {
                if (is_array($whereHasMorph) && Helper::isAssoc($whereHasMorph)) {
                    $model->whereHasMorph($whereHasMorph['relationship'], $whereHasMorph['morph'], $whereHasMorph['function']);
                }
            }
        }

        if (isset($options['group_by']) && count($options['group_by']) > 0) { //[field, ..., fieldN]
            if (is_string($options['group_by'])) {
                $model->groupBy($options['group_by']);
            } else if (is_array($options['group_by']) && !Helper::isAssoc($options['group_by'])) {
                foreach ($options['group_by'] as $field) {
                    $model->groupBy($field);
                }
            }
        }
        if (isset($options['orderby']) && count($options['orderby']) > 0) { //[field => ASC, ...]
            if (Helper::isAssoc($options['orderby'])) {
                $joinedTables = [];
                $hasOrderJoin = false;
                $orderSelects = [];
                $baseTable = $model->getModel()->getTable();
                foreach ($options['orderby'] as $field => $value) {
                    if (Str::contains($field, '.')) {
                        $parts = explode('.', $field);
                        if (count($parts) > 2) {
                            throw new ApiException('Ordering by nested relationships is not supported.', '400');
                        }
                        $relationName = $parts[0];
                        $relField = $parts[1];

                        if (method_exists($model->getModel(), $relationName)) {
                            $relation = $model->getModel()->{$relationName}();
                            $relatedTable = $relation->getRelated()->getTable();

                            if (!in_array($relatedTable, $joinedTables)) {
                                if ($relation instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
                                    $model->leftJoin($relatedTable, $relatedTable . '.' . $relation->getOwnerKeyName(), '=', $baseTable . '.' . $relation->getForeignKeyName());
                                } else if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne || $relation instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                                    $model->leftJoin($relatedTable, $relatedTable . '.' . $relation->getForeignKeyName(), '=', $baseTable . '.' . $relation->getLocalKeyName());
                                }
                                $joinedTables[] = $relatedTable;
                                $hasOrderJoin = true;
                            }
                            $orderColumn = $relatedTable . '.' . $relField;
                            if (!in_array($orderColumn, $orderSelects)) {
                                $orderSelects[] = $orderColumn;
                            }
                            $model->orderBy($orderColumn, $value);
                        }
                    } else {
                        $model->orderBy($baseTable . '.' . $field, $value);
                    }
                }
                if ($hasOrderJoin) {
                    $model->select($baseTable . '.*');
                    if (count($orderSelects) > 0) {
                        $model->addSelect($orderSelects);
                    }
                    $model->distinct();
                }
            }
        }

        return $model;
    }

    public function findByID($id, $options = [], $collection = false)
    {
        if (isset($options['where']) && count($options['where']) > 0 && !is_array($options['where'][0])) {
            $options['where'] = [$options['where']];
        }
        $options['where'][] = ['id', $id];
        return $this->findOne($options, $collection);
    }

    public function fillable($data)
    {
        return array_intersect_key($data, $this->fillable);
    }

    public function getFillableKeys()
    {
        $values = array_keys($this->fillable);
        if (method_exists($this->model, 'getAppends') && count($this->model->getAppends()) > 0) {
            $values = array_merge($values, $this->model->getAppends());
        }
        if (method_exists($this->model, 'getHidden') && count($this->model->getHidden()) > 0) {
            $values = array_diff($values, $this->model->getHidden());
        }
        return $values;
    }

    public function standardize_search($request, $parameter = [], $only = ['query', 'page', 'limit', 'where', 'fields', 'with', 'has', 'doesnt_have', 'where_null', 'where_not_null', 'where_in', 'where_not_in', 'where_has', 'where_doesnt_have', 'orderby', 'withtrashed', 'or_where_has', 'group_by', 'func_where', 'where_raw', 'fields_raw', 'where_has_morph', 'order_by', 'sort_by', 'sortby'])
    {
        if (is_array($request)) {
            $data = array_merge_recursive($request, $parameter);
        } else {
            $data = array_merge_recursive($request->only($only), $parameter);
        }
        if (!isset($data['fields'])) {
            $data['fields'] = [];
        }
        $options = [
            'fields' => []
        ];
        foreach ($data as $key => $value) {
            if (!in_array($key, ['limit', 'query', 'withtrashed', 'fields_raw'])) {
                if (is_string($value) && $value != '') {
                    $value = json_decode($value, true);
                } elseif (!is_array($value)) {
                    $value = [];
                }
            }
            switch (strtolower($key)) {
                case 'query':
                    if (is_string($value)) {
                        $options['q'] = $value;
                    } elseif (is_array($value) && Helper::isAssoc($value)) {
                        $options['q'] = Arr::get($value, 'value', '');
                        $options['q_fields'] = Arr::get($value, 'fields', []);
                    }
                    break;
                case 'fields_raw':
                    if (is_string($value)) {
                        if (Helper::isJson($value)) {
                            $value = json_decode($value, true);
                        } else {
                            $options[$key] = $value;
                        }
                    }

                    if (is_array($value)) {
                        $options[$key] = implode(',', $value);
                    }
                    break;
                case 'where_raw':
                    if (is_string($value)) {
                        $options[$key] = [$value];
                    } elseif (is_array($value)) {
                        $options[$key] = $value;
                    }
                    break;
                case 'where':
                case 'or_where':
                case 'where_null':
                case 'where_not_null':
                case 'where_in':
                case 'where_not_in':
                    if (is_array($value) && count($value) > 0) {
                        $options[$key] = $value;
                    }
                    break;
                case 'fields':
                    if (is_array($value) && count($value) > 0) {
                        $options['fields'] = $value;
                    } else {
                        $options['fields'] = array_unique(array_merge(['id'], $this->getFillableKeys()));
                    }
                    break;
                case 'with':
                    if (is_array($value) && count($value) > 0) {
                        $options['with'] = [];
                        $options['where_has'] = [];
                        foreach ($value as $relationship => $opts) {
                            if (is_array($opts)) {
                                $options['with'][$relationship] = function ($query) use ($opts) {
                                    return $this->buildingModel($query, $this->standardize_search($opts));
                                };
                                if ((isset($opts['where']) && count($opts['where']) > 0) || (isset($opts['filter']) && $opts['filter'] > 0)) {
                                    $options['where_has'][] = [
                                        "relationship" => $relationship,
                                        "function" => function ($query) use ($opts) {
                                            return $this->buildingModel($query, $this->standardize_search($opts));
                                        }
                                    ];
                                }
                                $options['fields'][] = explode('.', $relationship)[0];
                            } elseif (is_string($opts)) {
                                $options['with'][] = $opts;
                                $options['fields'][] = explode('.', $opts)[0];
                            }
                        }
                    }
                    break;
                case 'has':
                case 'doesnt_have':
                    if ($value != '') {
                        $options[$key] = $value;
                    }
                    break;
                case 'where_has':
                case 'where_doesnt_have':
                case 'or_where_has':
                    if (is_array($value) && count($value) > 0) {
                        $options[$key] = [];
                        foreach ($value as $relationship => $opts) {
                            if (count($opts) > 0) {
                                $options[$key][] = [
                                    "relationship" => $relationship,
                                    "function" => function ($query) use ($opts) {
                                        return $this->buildingModel($query, $this->standardize_search($opts));
                                    }
                                ];
                            }
                        }
                    }
                    break;
                case 'where_has_morph':
                    if (is_array($value) && count($value) > 0) {
                        $options[$key] = [];
                        foreach ($value as $relationship => $opts) {
                            if (isset($opts['morph']) && count($opts['morph']) > 0 && isset($opts['options']) && count($opts['options']) > 0) {
                                $options[$key][] = [
                                    "relationship" => $relationship,
                                    "morph" => $opts['morph'],
                                    "function" => function ($query) use ($opts) {
                                        return $this->buildingModel($query, $this->standardize_search($opts['options']));
                                    }
                                ];
                            }
                        }
                    }
                    break;
                case 'take':
                case 'limit':
                    if ($value > 0) {
                        $options[$key] = $value;
                    } else {
                        $options[$key] = 15;
                    }
                    break;
                case 'orderby':
                case 'order_by':
                case 'sort_by':
                case 'sortby':
                    if (is_array($value) && count($value) > 0) {
                        $options['orderby'] = $value;
                    }
                    break;
                case 'func_where':
                    if (is_array($value) && count($value) > 0) {
                        $options['func_where'] = $this->standardize_search($value);
                    }
                    break;
                case 'group_by':
                case 'withtrashed':
                    $options[$key] = $value;
                    break;
            }
        }
        return $options;
    }

    public function standardize_search_yield($request, $parameter = [])
    {
        return [];
    }

    public function normalize_query($query)
    {
        $where = '';
        if (is_string($query) && $query != '') {
            $like = 'LIKE';
            if ($this->model->getConnection()->getDriverName() == 'pgsql') {
                $like = 'ILIKE';
            }
            $whereAND = '';
            $whereOR = '';
            $aQuery = array_filter(explode(' ', $query));
            foreach ($aQuery as $value) {
                if (Str::startsWith($value, '+*') || (Str::startsWith($value, '+') && Str::endsWith($value, '*'))) {
                    $whereAND .= " ?field? $like '" . preg_replace("/(^\+\*|\*$)/", "%", $value) . "' AND";
                } elseif (Str::startsWith($value, '+')) {
                    $whereAND .= " ?field? $like '" . preg_replace("/^\+/", "", $value) . "' AND";
                } elseif (Str::startsWith($value, '*') || Str::endsWith($value, '*')) {
                    $whereOR .= " ?field? $like '" . preg_replace("/(^\*|\*$)/", "%", $value) . "' OR";
                } else {
                    $whereOR .= " ?field? $like '" . $value . "' OR";
                }
            }

            if ($whereOR != '') {
                $where .= '(' . rtrim($whereOR, "OR") . ') OR';
            }
            if ($whereAND != '') {
                $where .= ' (' . rtrim($whereAND, "AND") . ') OR';
            }
            $where = rtrim($where, "OR");
        }

        return $where;
    }

    public function transform($model, $options, $collection)
    {
        $isBuilder = ($model instanceof \Illuminate\Database\Eloquent\Builder || $model instanceof \Illuminate\Database\Query\Builder || $model instanceof \Illuminate\Database\Eloquent\Relations\Relation);

        if ($collection) {
            if ($isBuilder) {
                if (isset($options['fields']) && count($options['fields']) > 0) {
                    $fillable = $this->model->getFillable();
                    if ($this->model::CREATED_AT && !in_array($this->model::CREATED_AT, $fillable)) {
                        $fillable[] = $this->model::CREATED_AT;
                    }
                    if ($this->model::UPDATED_AT && !in_array($this->model::UPDATED_AT, $fillable)) {
                        $fillable[] = $this->model::UPDATED_AT;
                    }
                    if ($this->model->getKeyName() != '' && !in_array($this->model->getKeyName(), $fillable)) {
                        $fillable[] = $this->model->getKeyName();
                    }

                    // Keep fields that are in fillable OR were explicitly added via fields_raw/select
                    // If fields_raw is present, we trust the fields array more.
                    if (isset($options['fields_raw'])) {
                        return $model->get($options['fields']);
                    }
                    return $model->get($this->intersect($options['fields'], $fillable));
                } else {
                    return $model->get();
                }
            }

            if (isset($options['fields']) && count($options['fields']) > 0) {
                $model->each(function ($item) use ($options) {
                    if ($item instanceof \Illuminate\Database\Eloquent\Model) {
                        $attributes = $item->getAttributes();
                        // Only keep requested fields that actually exist in the loaded attributes
                        $fieldsToKeep = array_intersect($options['fields'], array_keys($attributes));
                        if (count($fieldsToKeep) > 0) {
                            $item->setRawAttributes(Arr::only($attributes, $fieldsToKeep));
                        }
                    }
                });
            }
            return $model;
        } else {
            $items = $isBuilder ? $model->get() : $model;

            if (isset($options['fields']) && count($options['fields']) > 0) {
                $res = $items->map(function ($item) use ($options) {
                    return Arr::only(is_array($item) ? $item : $item->toArray(), $options['fields']);
                });
                return $res->all();
            } else {
                return is_array($items) ? $items : $items->toArray();
            }
        }
    }

    public function formatting($data)
    {
        return $data;
    }

    protected function intersect($a1, $a2)
    {
        $res = [];
        $f = array_flip($a1);
        foreach ($a2 as $v) {
            if (isset($f[$v])) $res[] = $v;
        }

        return $res;
    }


    /**
     * param Model $model
     * param Array|json $data
     * 
     * data example: $data = appends:{
     *                          method_name:{
     *                              as: "Alias",
     *                              params: [param1,param2]
     *                          }
     *                       }
     * 
     * return array
     */
    public function appends($model, $data)
    {
        $res = [];
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (is_array($data)) {
            foreach ($data as $method => $_options) {
                if (method_exists($model, $method)) {
                    $as = Helper::getValue($_options, 'as', '_' . $method);
                    $res[$as] = call_user_func_array([
                        $model, $method
                    ], Helper::getValue($_options, 'params', []));
                }
            }
        }
        return $res;
    }

    public function getFieldsToSelect()
    {
        if (method_exists($this->model, 'getFieldsToSelect')) {
            $str = $this->model->getFieldsToSelect();
        } else {
            $fields = array_merge(['id'], $this->getFillableKeys());
            $str = '';
            foreach ($fields as $value) {
                $str .= "$value ,";
            }
            $str = rtrim($str, ' ,');
        }
        return $str;
    }

    protected function applyConditions($model, $conditions, $method = 'where')
    {
        if (isset($conditions) && count($conditions) > 0) {
            if (!is_array($conditions[0])) {
                $this->applySingleCondition($model, $conditions, $method);
            } else {
                foreach ($conditions as $condition) {
                    if (is_array($condition) && isset($condition[0]) && !is_array($condition[0])) {
                        $this->applySingleCondition($model, $condition, $method);
                    }
                }
            }
        }
        return $model;
    }

    protected function applySingleCondition($model, $condition, $method = 'where')
    {
        if (count($condition) > 2) {
            $operator = strtoupper($condition[1]);
            $isOr = $method === 'orWhere';
            if ($operator === 'IN') {
                $m = $isOr ? 'orWhereIn' : 'whereIn';
                $val = is_array($condition[2]) ? $condition[2] : [(string)$condition[2]];
                $model->{$m}($condition[0], $val);
            } elseif (in_array($operator, ['NOTIN', 'NOT IN', 'NOT_IN'])) {
                $m = $isOr ? 'orWhereNotIn' : 'whereNotIn';
                $val = is_array($condition[2]) ? $condition[2] : [(string)$condition[2]];
                $model->{$m}($condition[0], $val);
            } else {
                $model->{$method}($condition[0], $condition[1], $condition[2]);
            }
        } else {
            if (is_array($condition[1])) {
                $m = ($method === 'orWhere') ? 'orWhereIn' : 'whereIn';
                $model->{$m}($condition[0], $condition[1]);
            } else {
                $model->{$method}($condition[0], $condition[1]);
            }
        }
    }
}
