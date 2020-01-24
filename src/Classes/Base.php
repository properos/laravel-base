<?php

namespace Properos\Base\Classes;

use Properos\Base\Classes\Paginator;
use Properos\Base\Classes\Api;
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
    protected $fillable;
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
            $this->fillable[$key] = '';
        }
    }

    public function createModel($data = [], $rules = [], $messages = [])
    {
        $validation = \Validator::make($data, $rules, $messages);

        if ($validation->passes()) {
            return $this->model->create($this->fillable(array_merge($this->fillable, $this->formatting($data))));
        }

        throw new ApiException($validation->errors(), '006', $data);
    }

    public function updateModel($data = [], $rules = [], $messages = [])
    {
        if (!isset($data['id']) || $data['id'] <= 0) {
            throw new ApiException('The field id is required', '006', $data);
        }
        $rules = array_intersect_key($rules, $data);

        $validation = \Validator::make($data, $rules, $messages);

        if ($validation->passes()) {
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
            throw new ApiException($this->title . "  could not be updated.", '006', $data);
        }

        throw new ApiException($validation->errors(), '006', $data);
    }

    public function deleteModel($id, $where = [])
    {
        if (count($where) > 0) {
            $this->model->where($where);
        }

        if (is_array($id) && count($id) > 0) {
            return Api::success(\Str::plural($this->title) . ' were successfully deleted.', $this->model->whereIn('id', $id)->delete());
        } elseif ($id > 0) {
            return Api::success($this->title . ' was successfully deleted.', $this->model->where('id', $id)->delete());
        }
        throw new ApiException($this->title . "  could not be deleted.", '006', ['id' => $id]);
    }

    public function find($options = [], $collection = false)
    {
        return $this->findModel($this->mode::query(), $options, $collection);
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
            $this->set_paginator($model->paginate($options['limit']));
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
                if (isset($options['q_fields']) && count($options['q_fields']) > 0) {
                    $fields = $this->intersect($options['q_fields'], $this->model->searchable);
                } else {
                    $fields = $this->model->searchable;
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
            if (!is_array($options['where'][0])) {
                if (count($options['where']) > 2) {
                    $model->where($options['where'][0], $options['where'][1], $options['where'][2]);
                } else {
                    $model->where($options['where'][0], $options['where'][1]);
                }
            } else {
                foreach ($options['where'] as $where) {
                    if (!is_array($where[0])) {
                        if (count($where) > 2) {
                            $model->where($where[0], $where[1], $where[2]);
                        } else {
                            $model->where($where[0], $where[1]);
                        }
                    }
                }
            }
        }

        if (isset($options['or_where']) && count($options['or_where']) > 0) {
            if (!is_array($options['or_where'][0])) {
                if (count($options['or_where']) > 2) {
                    $model->orWhere($options['or_where'][0], $options['or_where'][1], $options['or_where'][2]);
                } else {
                    $model->orWhere($options['or_where'][0], $options['or_where'][1]);
                }
            } else {
                foreach ($options['or_where'] as $or_where) {
                    if (!is_array($or_where[0])) {
                        if (count($or_where) > 2) {
                            $model->orWhere($or_where[0], $or_where[1], $or_where[2]);
                        } else {
                            $model->orWhere($or_where[0], $or_where[1]);
                        }
                    }
                }
            }
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
            foreach ($options['where_in'] as $where) {
                if (Helper::isAssoc($where)) {
                    $model->whereIn($where['field'], $where['value']);
                }
            }
        }

        if (isset($options['where_not_in']) && count($options['where_not_in']) > 0) {
            foreach ($options['where_not_in'] as $where) {
                if (Helper::isAssoc($where)) {
                    $model->whereNotIn($where['field'], $where['value']);
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
                foreach ($options['orderby'] as $field => $value) {
                    $model->orderBy($field, $value);
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
        if ($collection) {
            return $this->findModel($this->mode::on(), $options, $collection)->first();
        } else {
            return head($this->findModel($this->mode::on(), $options, $collection));
        }
    }

    public function fillable($data)
    {
        return array_intersect_key($data, $this->fillable);
    }

    public function getFillableKeys()
    {
        return array_keys($this->fillable);
    }

    public function standardize_search($request, $parameter = [], $only = ['query', 'page', 'limit', 'where', 'fields', 'with', 'has', 'doesnt_have', 'where_null', 'where_not_null', 'where_in', 'where_not_in', 'where_has', 'where_doesnt_have', 'orderby', 'withTrashed', 'or_where_has', 'group_by', 'func_where', 'where_raw', 'fields_raw'])
    {
        if (is_array($request)) {
            $data = array_merge_recursive($request, $parameter);
        } else {
            $data = array_merge_recursive($request->only($only), $parameter);
        }
        $options = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, ['limit', 'query', 'withTrashed'])) {
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
                        $options['q'] = \Arr::get($value, 'value', '');
                        $options['q_fields'] = \Arr::get($value, 'fields', []);
                    }
                    break;
                case 'fields_raw':
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
                    if (count($value) > 0) {
                        $options[$key] = $value;
                    }
                    break;
                case 'fields':
                    if (count($value) > 0) {
                        $options['fields'] = $value;
                    } else {
                        $options['fields'] = array_merge(['id'], $this->getFillableKeys());
                    }
                    break;
                case 'with':
                    if (count($value) > 0) {
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
                    if (count($value) > 0) {
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
                case 'take':
                case 'limit':
                    if ($value > 0) {
                        $options[$key] = $value;
                    } else {
                        $options[$key] = 15;
                    }
                    break;
                case 'orderby':
                    if (count($value) > 0) {
                        $options['orderby'] = $value;
                    }
                    break;
                case 'func_where':
                    if (count($value) > 0) {
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
            $whereAND = '';
            $whereOR = '';
            $aQuery = explode(' ', $query);
            foreach ($aQuery as $value) {
                if (\Str::startsWith($value, '+*') || (\Str::startsWith($value, '+') && \Str::endsWith($value, '*'))) {
                    $whereAND .= " ?field? LIKE '" . preg_replace("/(^\+\*|\*$)/", "%", $value) . "' AND";
                } elseif (\Str::startsWith($value, '+')) {
                    $whereAND .= " ?field? LIKE '" . preg_replace("/^\+/", "", $value) . "' AND";
                } elseif (\Str::startsWith($value, '*') || \Str::endsWith($value, '*')) {
                    $whereOR .= " ?field? LIKE '" . preg_replace("/(^\*|\*$)/", "%", $value) . "' OR";
                } else {
                    $whereOR .= " ?field? LIKE '" . $value . "' OR";
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
        if ($collection) {
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

                return $model->get($this->intersect($options['fields'], $fillable));
            } else {
                return $model->get();
            }
        } else {
            if (isset($options['fields']) && count($options['fields']) > 0) {
                $collections = $model->get();
                $res = $collections->map(function ($item) use ($options) {
                    return \Arr::only($item->toArray(), $options['fields']);
                });
                return $res->all();
            } else {
                return $model->get()->toArray();
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
}
