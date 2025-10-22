<?php
namespace app\deshang\base;


class BaseService
{
    
    protected $dao;
    protected $model;
    protected $request;
    public function __construct()
    {
        $this->request = request();
    }


    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;


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

    protected function validate(array $data, string|array $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
}
