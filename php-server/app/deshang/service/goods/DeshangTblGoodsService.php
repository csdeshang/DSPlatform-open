<?php


namespace app\deshang\service\goods;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;


use app\common\dao\goods\TblGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsSpecDao;
use app\common\dao\goods\TblGoodsCategoryRelDao;
use app\common\dao\goods\TblGoodsCommentDao;
use app\common\dao\goods\TblGoodsFavoritesDao;



class DeshangTblGoodsService extends BaseDeshangService
{
    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblGoodsDao();
    }



    // 核心方法不使用 数据库事务 避免出现数据不一致
    public function addTblGoods($data)
    {
        $this->validate($data, 'app\deshang\service\goods\validate\TblGoodsValidator.add');

        // 添加商品
        $tbl_goods_data = $this->getTblGoodsData($data);
        $tbl_goods_data['create_at'] = TIMESTAMP;
        $tbl_goods_data['store_id'] = $data['store_id'];


        $goods_id = $this->dao->createGoods($tbl_goods_data);


        // 添加商品分类关联
        $this->updateGoodsCategoryRef($goods_id, $data['category_ids'], false);



        //添加规格和Sku
        if ($data['spec_type'] == 'single') {

            $tbl_goods_sku = array(
                'goods_id' => $goods_id,
                'sku_name' => '',
                'sku_image' => $data['single']['sku_image'],
                'sku_code' => $data['single']['sku_code'],
                'sku_price' => $data['single']['sku_price'],
                'sku_stock' => $data['single']['sku_stock'],
                'sku_spec_format' => '',
                'market_price' => $data['single']['market_price'],
                'cost_price' => $data['single']['cost_price'],
                'is_default' => 1,
            );

            //添加SKU
            (new TblGoodsSkuDao)->createGoodsSku($tbl_goods_sku);
        } else if ($data['spec_type'] == 'multiple') {

            $tbl_goods_sku = array();
            foreach ($data['goods_sku_list'] as $k => $goods_sku) {
                $sku_spec_format = [];
                foreach ($goods_sku['sku_spec'] as $ck => $cv) {
                    $sku_spec_format[] = $cv['spec_value_name'];
                }

                // 添加SKU
                $tbl_goods_sku[] = array(
                    'goods_id' => $goods_id,
                    'sku_name' => $goods_sku['spec_name'],
                    'sku_image' => $goods_sku['sku_image'],
                    'sku_code' => $goods_sku['sku_code'],
                    'sku_price' => $goods_sku['sku_price'],
                    'sku_stock' => $goods_sku['sku_stock'],
                    'sku_spec_format' => implode(',', $sku_spec_format),
                    'market_price' => $goods_sku['market_price'],
                    'cost_price' => $goods_sku['cost_price'],
                    'is_default' => $goods_sku['is_default'],
                );
            }

            (new TblGoodsSkuDao)->createGoodsSkuAll($tbl_goods_sku);


            // 添加规格
            $spec_data = [];
            foreach ($data['goods_spec_format'] as $k => $v) {
                $spec_values = [];
                foreach ($v['values'] as $ck => $cv) {
                    $spec_values[] = $cv['spec_value_name'];
                }
                $spec_data[] = [
                    'goods_id' => $goods_id,
                    'spec_name' => $v['spec_name'],
                    'spec_value' => implode(',', $spec_values)
                ];
            }
            (new TblGoodsSpecDao)->createGoodsSpecAll($spec_data);
        } else {
            throw new CommonException('规格类型错误');
        }

        return $goods_id;
    }



    public function updateTblGoods($data)
    {

        $this->validate($data, 'app\deshang\service\goods\validate\TblGoodsValidator.edit');


        $goods_sku_dao = new TblGoodsSkuDao();
        $goods_spec_dao = new TblGoodsSpecDao();


        $condition = array();
        $condition[] = ['id', '=', $data['id']];
        $condition[] = ['store_id', '=', $data['store_id']];
        $condition[] = ['platform', '=', $data['platform']];
        $goods_info = $this->dao->getGoodsInfo($condition);
        if (empty($goods_info)) {
            throw new CommonException('商品不存在');
        }

        // 判断商品是否参加了活动,参加活动的商品不能编辑
        if ($this->isGoodsInPromotion($goods_info)) {
            throw new CommonException('商品正在参加活动不能进行编辑');
        }

        $tbl_goods_data = $this->getTblGoodsData($data);

        $this->dao->updateGoods($condition, $tbl_goods_data);

        // 更新商品分类关联
        $this->updateGoodsCategoryRef($data['id'], $data['category_ids']);

        $sku_count = $goods_sku_dao->getGoodsSkuCount([['goods_id', '=', $data['id']]]);


        if ($data['spec_type'] == 'single') {
            $tbl_goods_sku = array(
                'goods_id' => $data['id'],
                'sku_name' => '',
                'sku_image' => $data['single']['sku_image'],
                'sku_code' => $data['single']['sku_code'],
                'sku_price' => $data['single']['sku_price'],
                'sku_stock' => $data['single']['sku_stock'],
                'sku_spec_format' => '',
                'market_price' => $data['single']['market_price'],
                'cost_price' => $data['single']['cost_price'],
                'is_default' => 1,
            );




            if ($sku_count != 1) {
                //修改前是多规格 则删除 sku spec

                $goods_sku_dao->deleteGoodsSku([['goods_id', '=', $data['id']]]);
                $goods_spec_dao->deleteGoodsSpec([['goods_id', '=', $data['id']]]);

                $goods_sku_dao->createGoodsSku($tbl_goods_sku);
            } else {
                //更新sku
                $goods_sku_dao->updateGoodsSku([['goods_id', '=', $data['id']]], $tbl_goods_sku);
                // Spec 不需要
                $goods_spec_dao->deleteGoodsSpec([['goods_id', '=', $data['id']]]);
            }
        } else if ($data['spec_type'] == 'multiple') {

            if ($sku_count != 1) {
                // 修改之前是多规格 进行更新及新增

                //当前规格列表
                $sku_id_arr = [];
                $tbl_goods_sku = array();
                foreach ($data['goods_sku_list'] as $k => $goods_sku) {
                    $sku_spec_format = [];
                    foreach ($goods_sku['sku_spec'] as $ck => $cv) {
                        $sku_spec_format[] = $cv['spec_value_name'];
                    }

                    // 添加SKU
                    $tbl_goods_sku = array(
                        'goods_id' => $data['id'],
                        'sku_name' => $goods_sku['spec_name'],
                        'sku_image' => $goods_sku['sku_image'],
                        'sku_code' => $goods_sku['sku_code'],
                        'sku_price' => $goods_sku['sku_price'],
                        'sku_stock' => $goods_sku['sku_stock'],
                        'sku_spec_format' => implode(',', $sku_spec_format),
                        'market_price' => $goods_sku['market_price'],
                        'cost_price' => $goods_sku['cost_price'],
                        'is_default' => $goods_sku['is_default'],
                    );

                    //单个SKU 添加及修改
                    if (!empty($goods_sku['id'])) {
                        // 修改规格
                        $sku_id_arr[] = $goods_sku['id'];

                        $goods_sku_dao->updateGoodsSku([['id', '=', $goods_sku['id']], ['goods_id', '=', $data['id']]], $tbl_goods_sku);
                    } else {
                        // 新增规格
                        $sku_id_arr[] = $goods_sku_dao->createGoodsSku($tbl_goods_sku);
                    }
                }
                // 删除多余的SKU
                $goods_sku_dao->deleteGoodsSku([['goods_id', '=', $data['id']], ['id', 'not in', $sku_id_arr]]);


                $spec_id_list = $goods_spec_dao->getGoodsSpecColumn([['goods_id', '=', $data['id']]], 'id');

                // 商品规格值
                foreach ($data['goods_spec_format'] as $k => $v) {
                    $spec_values = [];
                    foreach ($v['values'] as $ck => $cv) {
                        $spec_values[] = $cv['spec_value_name'];
                    }
                    $spec_data = [
                        'goods_id' => $data['id'],
                        'spec_name' => $v['spec_name'],
                        'spec_value' => implode(',', $spec_values)
                    ];
                    if (!empty($v['spec_id'])) {
                        // 修改规格值
                        $goods_spec_dao->updateGoodsSpec([['goods_id', '=', $data['id']], ['id', '=', $v['spec_id']]], $spec_data);

                        foreach ($spec_id_list as $ck => $cv) {
                            if ($v['spec_id'] == $cv) {
                                unset($spec_id_list[$ck]);
                            }
                        }
                    } else {
                        // 添加规格值
                        $goods_spec_dao->createGoodsSpec($spec_data);
                    }
                }

                // 移除不存在的规格项
                if (!empty($spec_id_list)) {
                    $goods_spec_dao->deleteGoodsSpec([['id', 'in', implode(',', $spec_id_list)]]);
                }

                // 移除不存在的商品SKU
                $sku_id_list = $goods_sku_dao->getGoodsSkuColumn([['goods_id', '=', $data['id']]], 'id');
                $sku_id_list = array_column($sku_id_list, 'id');
                foreach ($sku_id_list as $k => $v) {
                    foreach ($sku_id_arr as $ck => $cv) {
                        if ($v == $cv) {
                            unset($sku_id_list[$k]);
                        }
                    }
                }
                $sku_id_list = array_values($sku_id_list);

                if (!empty($sku_id_list)) {
                    $goods_sku_dao->deleteGoodsSku([['goods_id', '=', $data['id']], ['id', 'in', implode(',', $sku_id_list)]]);
                }
            } else {
                // 修改之前是单规格  删除所有规格内容

                $goods_sku_dao->deleteGoodsSku([['goods_id', '=', $data['id']]]);

                $goods_spec_dao->deleteGoodsSpec([['goods_id', '=', $data['id']]]);



                //进行新增  与添加一致
                $tbl_goods_sku = array();
                foreach ($data['goods_sku_list'] as $k => $goods_sku) {
                    $sku_spec_format = [];
                    foreach ($goods_sku['sku_spec'] as $ck => $cv) {
                        $sku_spec_format[] = $cv['spec_value_name'];
                    }

                    // 添加SKU
                    $tbl_goods_sku[] = array(
                        'goods_id' => $data['id'],
                        'sku_name' => $goods_sku['spec_name'],
                        'sku_image' => $goods_sku['sku_image'],
                        'sku_code' => $goods_sku['sku_code'],
                        'sku_price' => $goods_sku['sku_price'],
                        'sku_stock' => $goods_sku['sku_stock'],
                        'sku_spec_format' => implode(',', $sku_spec_format),
                        'market_price' => $goods_sku['market_price'],
                        'cost_price' => $goods_sku['cost_price'],
                        'is_default' => $goods_sku['is_default'],
                    );
                }

                $goods_sku_dao->createGoodsSkuAll($tbl_goods_sku);


                // 添加规格
                $spec_data = [];
                foreach ($data['goods_spec_format'] as $k => $v) {
                    $spec_values = [];
                    foreach ($v['values'] as $ck => $cv) {
                        $spec_values[] = $cv['spec_value_name'];
                    }
                    $spec_data[] = [
                        'goods_id' => $data['id'],
                        'spec_name' => $v['spec_name'],
                        'spec_value' => implode(',', $spec_values)
                    ];
                }
                $goods_spec_dao->createGoodsSpecAll($spec_data);
            }
        } else {
            throw new CommonException('规格类型错误');
        }
    }


    // 商品编辑信息
    public function getTblGoodsUpdateInfo($data)
    {
        $condition = array();
        $condition[] = ['id', '=', $data['id']];
        $condition[] = ['store_id', '=', $data['store_id']];
        $condition[] = ['platform', '=', $data['platform']];

        $goods_info = $this->dao->getGoodsInfo($condition);

        if (empty($goods_info)) {
            throw new CommonException('商品不存在');
        }

        // 判断商品是否参加了活动,参加活动的商品不能编辑
        if ($this->isGoodsInPromotion($goods_info)) {
            throw new CommonException('商品正在参加活动不能进行编辑');
        }

        $goods_info['goodsSkuList'] = (new TblGoodsSkuDao())->getGoodsSkuList([['goods_id', '=', $data['id']]]);

        // 商品分类关联
        $goods_info['category_ids'] = (new TblGoodsCategoryRelDao())->getGoodsCategoryRelColumn([['goods_id', '=', $data['id']]], 'category_id');





        if (count($goods_info['goodsSkuList']) == 1) {
            //单个SKU
            $goods_info['spec_type'] = 'single';
        } else {
            $goods_info['spec_type'] = 'multiple';
            $goods_info['goodsSpecList'] =  (new TblGoodsSpecDao())->getGoodsSpecList([['goods_id', '=', $data['id']]]);
        }
        if (!empty($goods_info['slide_image'])) $goods_info['slide_image'] = explode(',', $goods_info['slide_image']);

        if (!empty($goods_info['goods_parameters'])) {
            $goods_info['goods_parameters'] = json_decode($goods_info['goods_parameters'], true) ?: [];
        }


        return $goods_info;
    }



    // 判断商品是否参加了活动促销,参加活动的商品不能编辑
    private function isGoodsInPromotion(array $goods_info): bool
    {
        // 商品促销[限时折扣]
        if ($goods_info['is_flashsale_goods'] == 1) {
            return true;
        }

        // 商品促销[批发]
        if ($goods_info['is_wholesale_goods'] == 1) {
            return true;
        }

        // 商品促销[平台会员价]
        if ($goods_info['is_userdiscount_goods'] == 1) {
            return true;
        }

        // 商品促销[商品分销]
        if ($goods_info['is_distributor_goods'] == 1) {
            return true;
        }

        return false;
    }




    private function getTblGoodsData($data)
    {
        //商品主图取第一张
        $data['cover_image'] = isset($data['slide_image'][0]) ? $data['slide_image'][0] : '';
        if (!empty($data['slide_image'])) $data['slide_image'] = implode(',', $data['slide_image']);


        // 检查规格数据是否完整
        foreach ($data['goods_sku_list'] as $k => $goods_sku) {
            if (empty($goods_sku['sku_price'])) {
                throw new CommonException('请设置商品规格价格');
            }
            if (empty($goods_sku['sku_stock'])) {
                throw new CommonException('请设置商品规格库存');
            }
            if (empty($goods_sku['market_price'])) {
                throw new CommonException('请设置商品规格市场价');
            }
            if (empty($goods_sku['cost_price'])) {
                throw new CommonException('请设置商品规格成本价');
            }
        }

        // 获取商品分类选择限制
        $goods_category_select_limit = sysConfig('goods:goods_category_select_limit');
        if ($goods_category_select_limit > 0) {
            // 如果商品分类选择限制大于0 数量不能大于商品分类选择限制
            if ($goods_category_select_limit < count($data['category_ids'])) {
                throw new CommonException('商品分类选择限制数量' . $goods_category_select_limit);
            }
        }



        // 获取商品是否需要审核  0:不需要审核 1:需要审核
        $goods_need_audit = sysConfig('goods:goods_need_audit');



        $tbl_goods_data = array(
            'platform' => $data['platform'],
            'goods_name' => $data['goods_name'],
            'goods_advword' => $data['goods_advword'],
            'goods_status' => $data['goods_status'],
            'brand_id' => $data['brand_id'],
            'goods_video' => $data['goods_video'],
            'goods_body' => $data['goods_body'],
            // 店铺商品分类id  系统商品分类在关联表
            'store_goods_cid' => $data['store_goods_cid'],
            'cover_image' => $data['cover_image'],
            'slide_image' => $data['slide_image'],
            'stock_num' => $data['stock_num'],
            'virtual_sales_num' => $data['virtual_sales_num'],
            'goods_sort' => $data['goods_sort'],

            // 运费相关字段[针对Mall类型店铺 快递费设置]
            'mall_express_type' => $data['mall_express_type'],
            'mall_express_tpl_id' => $data['mall_express_tpl_id'],
            'mall_express_fee' => $data['mall_express_fee'],


            //商品参数
            'goods_parameters' => json_encode($data['goods_parameters']),
            'sys_status' => $goods_need_audit == 0 ? 1 : 0,
        );



        return $tbl_goods_data;
    }


    /* 更新商品分类关联
     * @param $goods_id
     * @param $category_ids
     * @param bool $is_update
     */
    private function updateGoodsCategoryRef($goods_id, $category_ids, $is_update = true)
    {
        if ($is_update) {
            // 更新
            if (!empty($category_ids)) {
                //获取当前商品分类关联
                $current_category_ids = (new TblGoodsCategoryRelDao())->getGoodsCategoryRelColumn([['goods_id', '=', $goods_id]], 'category_id');


                $data = [];
                $exist_category_ids = []; //存在分类ID
                foreach ($category_ids as $category_id) {

                    if (!in_array($category_id, $current_category_ids)) {
                        $data[] = [
                            'goods_id' => (int)$goods_id,
                            'category_id' => (int)$category_id,
                        ];
                    } else {
                        // 存在分类ID
                        $exist_category_ids[] = $category_id;
                    }
                }
                // 删除不存在分类ID
                (new TblGoodsCategoryRelDao())->deleteGoodsCategoryRel([['goods_id', '=', $goods_id], ['category_id', 'not in', $exist_category_ids]]);
                // 添加新分类ID
                (new TblGoodsCategoryRelDao())->createGoodsCategoryRelAll($data);
            } else {
                // 删除当前商品分类关联

                (new TblGoodsCategoryRelDao())->deleteGoodsCategoryRel([['goods_id', '=', $goods_id]]);
            }
        } else {
            // 新增
            if (!empty($category_ids)) {
                foreach ($category_ids as $category_id) {
                    $data[] = [
                        'goods_id' => (int)$goods_id,
                        'category_id' => (int)$category_id,
                    ];
                }
                (new TblGoodsCategoryRelDao())->createGoodsCategoryRelAll($data);
            }
        }
    }




    // 硬删除(物理删除)
    public function hardDeleteTblGoods($data)
    {
        //查询
        $condition = array();
        $condition[] = ['platform', '=', $data['platform']];
        $condition[] = ['store_id', '=', $data['store_id']];
        $condition[] = ['id', 'in', ids_to_array($data['goods_ids'])];

        $goods_ids = $this->dao->getGoodsColumn($condition, 'id');
        if (empty($goods_ids)) {
            throw new CommonException('商品不存在');
        }

        // 获取所有商品信息，并使用isGoodsInPromotion方法判断
        $goods_list = $this->dao->getGoodsList([['id', 'in', $goods_ids]]);
        $promotion_goods_names = [];
        foreach ($goods_list as $goods_info) {
            if ($this->isGoodsInPromotion($goods_info)) {
                $promotion_goods_names[] = $goods_info['goods_name'];
            }
        }
        if (!empty($promotion_goods_names)) {
            throw new CommonException('以下商品正在参加活动不能删除：' . implode('、', $promotion_goods_names));
        }


        // 删除TblGoods关联数据
        $this->dao->deleteGoods([['id', 'in', $goods_ids]]);
        // 删除TblGoodsSku关联数据
        (new TblGoodsSkuDao())->deleteGoodsSku([['goods_id', 'in', $goods_ids]]);
        // 删除TblGoodsSpec关联数据
        (new TblGoodsSpecDao())->deleteGoodsSpec([['goods_id', 'in', $goods_ids]]);
        // 删除商品分类关联
        (new TblGoodsCategoryRelDao())->deleteGoodsCategoryRel([['goods_id', 'in', $goods_ids]]);
        // 删除商品评论
        (new TblGoodsCommentDao())->deleteGoodsComment([['goods_id', 'in', $goods_ids]]);
        // 删除商品收藏
        (new TblGoodsFavoritesDao())->deleteGoodsFavorites([['goods_id', 'in', $goods_ids]]);



        return $goods_ids;
    }

    // 软删除(逻辑删除)
    public function softDeleteTblGoods($data)
    {
        // 查询
        $condition = array();
        $condition[] = ['platform', '=', $data['platform']];
        $condition[] = ['store_id', '=', $data['store_id']];
        $condition[] = ['id', 'in', ids_to_array($data['goods_ids'])];

        $goods_ids = $this->dao->getGoodsColumn($condition, 'id');
        if (empty($goods_ids)) {
            throw new CommonException('商品不存在');
        }

        // 获取所有商品信息，并使用isGoodsInPromotion方法判断
        $goods_list = $this->dao->getGoodsList([['id', 'in', $goods_ids]]);
        $promotion_goods_names = [];
        foreach ($goods_list as $goods_info) {
            if ($this->isGoodsInPromotion($goods_info)) {
                $promotion_goods_names[] = $goods_info['goods_name'];
            }
        }
        if (!empty($promotion_goods_names)) {
            throw new CommonException('以下商品正在参加活动不能删除：' . implode('、', $promotion_goods_names));
        }


        // 更新商品状态
        $goods_data = [
            'goods_status' => 0,
            'is_deleted' => 1,
            'deleted_at' => time(),
        ];
        $this->dao->updateGoods([['id', 'in', $goods_ids]], $goods_data);

        return $goods_ids;
    }
}
