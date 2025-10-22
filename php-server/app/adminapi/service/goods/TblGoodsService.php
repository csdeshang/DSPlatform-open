<?php

namespace app\adminapi\service\goods;

use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\goods\DeshangTblGoodsService;

use app\common\enum\goods\TblGoodsEnum;

use app\common\dao\goods\TblGoodsDao;
use app\common\dao\store\TblStoreDao;
use app\common\dao\merchant\MerchantDao;

use app\deshang\utils\SearchHelper;

use think\facade\Db;


class TblGoodsService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 获取店铺列表
     * @param array $params 查询参数
     * @return array
     */
    public function getTblGoodsPages(array $data): array
    {

        // 此处只对 TblGoods 表进行处理 ， 涉及 mall_goods 表 调用 MallGoodsService 进行处理

        $condition = [];
        $condition[] = ['platform', '=', $data['platform']];


        // 使用映射表优化 tab_selected 条件筛选
        $tabConditions = [
            'pending' => [
                ['goods_status', '=', TblGoodsEnum::STATUS_SHELVED],
                ['is_deleted', '=', 0]
            ],
            'un_send' => [
                ['goods_status', '=', TblGoodsEnum::STATUS_UNSHELVED],
                ['is_deleted', '=', 0]
            ],
            'pending_review' => [
                ['sys_status', '=', TblGoodsEnum::SYS_STATUS_PENDING_REVIEW],
                ['is_deleted', '=', 0]
            ],
            'review_failed' => [
                ['sys_status', '=', TblGoodsEnum::SYS_STATUS_FAILED],
                ['is_deleted', '=', 0]
            ],
            'violated' => [
                ['sys_status', '=', TblGoodsEnum::SYS_STATUS_VIOLATED],
                ['is_deleted', '=', 0]
            ],
            'sys_recommend' => [
                ['sys_recommend_status', '=', TblGoodsEnum::SYS_RECOMMEND_STATUS_REC],
                ['is_deleted', '=', 0]
            ],
            'deleted' => [
                ['is_deleted', '=', 1]
            ]
        ];
        
        // 根据tab_selected添加对应的条件
        if (isset($data['tab_selected']) && isset($tabConditions[$data['tab_selected']])) {
            $condition = array_merge($condition, $tabConditions[$data['tab_selected']]);
        }


        if (isset($data['is_distributor_goods']) && $data['is_distributor_goods'] == 1) {
            $condition[] = ['is_distributor_goods', '=', 1];
        }

        // 商品名称搜索
        if (isset($data['goods_name']) && !empty($data['goods_name'])) {
            $condition[] = ['goods_name', 'like', '%' . $data['goods_name'] . '%'];
        }

        // 店铺名搜索
        if (isset($data['store_name']) && !empty($data['store_name'])) {
            $storeIds = SearchHelper::getStoreIdsByStoreName($data['store_name']);
            $condition[] = ['store_id', 'in', $storeIds];
        }


        $result = (new TblGoodsDao())->getWithRelGoodsPages($condition);
        return $result;
    }


    // 更新商品系统状态
    public function updateTblGoodsSysStatus(int $id, array $data)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];


        $updateData = array(
            'sys_status' => $data['sys_status'],
            'sys_status_reason' => $data['sys_status_reason'],
        );

        $result = (new TblGoodsDao())->updateGoods($condition, $updateData);

        // 审核失败 通知商户
        if ($data['sys_status'] == TblGoodsEnum::SYS_STATUS_FAILED && $result) {

            // 根据商品ID 获取 商品信息
            $goods_info = (new TblGoodsDao())->getGoodsInfo([['id', '=', $id]]);
            // 根据店铺ID 获取 店铺信息
            $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $goods_info['store_id']]]);
            // 根据商户ID 获取 商户信息
            $merchant_info = (new MerchantDao())->getMerchantInfo([['id', '=', $store_info['merchant_id']]]);

            if (!empty($merchant_info['user_id']) && $merchant_info['user_id'] > 0) {

                // 通知商户， 实际是发送到 用户 消息中
                event('SysNoticeListener', [
                    'key' => 'store_goods_review_failed',
                    'receiver_params' => [
                        'user_id' => $merchant_info['user_id'],
                    ],
                    // 具体参数  以  sys_notice_tpl 表 为准
                    'template_params' => [
                        'goods_name' => $goods_info['goods_name'],
                        'goods_id' => $id,
                        'reason' => $data['sys_status_reason'],
                    ]
                ]);
            }
        }

        // 商品被下架 通知商户
        if ($data['sys_status'] == TblGoodsEnum::SYS_STATUS_VIOLATED && $result) {
            // 根据商品ID 获取 商品信息
            $goods_info = (new TblGoodsDao())->getGoodsInfo([['id', '=', $id]]);
            // 根据店铺ID 获取 店铺信息
            $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $goods_info['store_id']]]);
            // 根据商户ID 获取 商户信息
            $merchant_info = (new MerchantDao())->getMerchantInfo([['id', '=', $store_info['merchant_id']]]);

            if (!empty($merchant_info['user_id']) && $merchant_info['user_id'] > 0) {

                // 通知商户， 实际是发送到 用户 消息中
                event('SysNoticeListener', [
                    'key' => 'store_goods_offline',
                    'receiver_params' => [
                        'user_id' => $merchant_info['user_id'],
                    ],
                    // 具体参数  以  sys_notice_tpl 表 为准
                    'template_params' => [
                        'goods_name' => $goods_info['goods_name'],
                        'goods_id' => $id,
                        'reason' => $data['sys_status_reason'],
                    ]
                ]);
            }
        }




        return $result;
    }




    // 更新商品系统推荐状态
    public function updateSysRecommend(int $id, array $data)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $updateData = array(
            'sys_recommend_status' => $data['sys_recommend_status'] == 1 ? 1 : 0
        );
        return (new TblGoodsDao())->updateGoods($condition, $updateData);
    }

    // 获取商品详情
    public function getTblGoodsInfo(array $data)
    {
        $goods_info = (new TblGoodsDao())->getGoodsInfo([['id', '=', $data['id']]]);

        return $goods_info;

    }

    // 创建商品 涉及 其他关联数据 需要单独使用 对应的 平台方法进行处理
    public function createTblGoods(array $data) {}



    // 更新商品， 只能对 TblGoods 表数据进行更新
    public function updateTblGoods(array $data)
    {


        //公共商品表 tbl_goods tbl_goods_sku tbl_goods_spec 
        Db::startTrans();
        try {

            (new DeshangTblGoodsService())->updateTblGoods($data);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new CommonException('获取到的异常' . $e->getMessage());
        }
    }



    // 删除商品， 涉及 其他关联数据 需要单独使用 对应的 平台方法进行处理
    public function deleteTblGoods(array $data) {}
}
