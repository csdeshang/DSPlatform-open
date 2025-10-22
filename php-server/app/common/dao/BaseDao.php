<?php

namespace app\common\dao;


class BaseDao
{
    protected $model;

    public function __construct()
    {

    }

    public function getPageParams()
    {
        $page_current = (int) input('param.page_current');
        $page_size = (int) input('param.page_size');

        $page = array(
            'page_current' => $page_current > 0 ? $page_current : 1,
            'page_size' => $page_size > 0 ? $page_size : 10,
        );

        return $page;
    }


    public function getPaginate($model)
    {
        //获取分页数据
        $page_params = $this->getPageParams();

        $list = $model->paginate([
            'list_rows' => $page_params['page_size'],
            'page' => $page_params['page_current'],
        ])->toArray();
        return $list;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

}
            