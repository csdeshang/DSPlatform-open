<?php


namespace app\deshang\service\goods;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;


use app\common\dao\goods\TblGoodsDao;
use app\common\dao\goods\TblGoodsSkuDao;
use app\common\dao\goods\TblGoodsSpecDao;
use app\common\dao\goods\TblGoodsCategoryRelDao;
use app\common\dao\goods\TblGoodsCommentDao;
use app\common\dao\store\TblStoreDao;
use app\common\dao\goods\TblGoodsFlashsaleDao;
use app\common\dao\goods\TblGoodsWholesaleDao;
use app\common\dao\goods\TblGoodsUserdiscountDao;
use app\common\dao\user\UserGrowthLevelDao;
use app\common\dao\user\UserDao;

use app\common\enum\goods\TblGoodsEnum;
use app\common\enum\store\TblStoreEnum;
use app\common\enum\goods\TblGoodsPromotionEnum;
use app\common\enum\goods\TblGoodsFlashsaleEnum;


// 主要用于前端显示,不涉及任何数据库操作
class DeshangTblGoodsDisplayService extends BaseDeshangService
{
    public $flashsaleDao;
    public $wholesaleDao;
    public $userdiscountDao;
    public $userGrowthLevelDao;
    public $userDao;

    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblGoodsDao();
        $this->flashsaleDao = new TblGoodsFlashsaleDao();
        $this->wholesaleDao = new TblGoodsWholesaleDao();
        $this->userdiscountDao = new TblGoodsUserdiscountDao();
        $this->userGrowthLevelDao = new UserGrowthLevelDao();
        $this->userDao = new UserDao();
    }


    
    // 主要用于前端显示
    public function getTblGoodsDetail($data)
    {

        $goods_info = $this->dao->getGoodsInfo([['id', '=', $data['id']]]);

        if (empty($goods_info)) {
            throw new CommonException('商品不存在');
        }

        if ($goods_info['sys_status'] != TblGoodsEnum::SYS_STATUS_NORMAL) {
            throw new CommonException('商品未审核');
        }

        // 查询SKU信息
        $goods_info['skuList'] = (new TblGoodsSkuDao())->getGoodsSkuList([['goods_id', '=', $data['id']]]);

        // 检查是否有sku_id，如果有则查询该sku_id的SKU，否则查询默认SKU
        if (!empty($data['sku_id'])) {
            $goods_info['defaultSku'] = (new TblGoodsSkuDao())->getGoodsSkuInfo([['id', '=', $data['sku_id']], ['goods_id', '=', $data['id']]]);
        } else {
            $goods_info['defaultSku'] = (new TblGoodsSkuDao())->getGoodsSkuInfo([['goods_id', '=', $data['id']], ['is_default', '=', 1]]);
        }

        // 获取促销信息
        $this->getGoodsPromotionInfo($goods_info, $data);

        // 查询规格信息
        $goods_info['goodsSpec'] = (new TblGoodsSpecDao())->getGoodsSpecList([['goods_id', '=', $data['id']]]);

        // 店铺信息
        $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $goods_info['store_id']]], 'id,platform,store_name,store_logo,collect_num,avg_describe_score,avg_service_score,avg_logistics_score,apply_status,is_enabled');
        if ($store_info['apply_status'] != TblStoreEnum::APPLY_STATUS_APPROVED) {
            throw new CommonException('店铺未审核');
        }
        if ($store_info['is_enabled'] != 1) {
            throw new CommonException('店铺已关闭');
        }
        $goods_info['store'] = $store_info;


        // 获取最近2条评价
        $goods_info['goodsCommentList'] = (new TblGoodsCommentDao())->getWithRelGoodsCommentList([['goods_id', '=', $data['id']], ['is_show', '=', 1], ['is_deleted', '=', 0]], '*', 'create_at desc', 2);


        if (!empty($goods_info['slide_image'])) $goods_info['slide_image'] = explode(',', $goods_info['slide_image']);

        // 自增商品浏览数
        (new TblGoodsDao())->setGoodsInc([['id', '=', $data['id']]], 'click_num');

        return $goods_info;
    }


    /**
     * 获取商品促销信息
     * @param array &$goods_info 商品信息（引用传递，直接修改）
     * @param array $data 请求数据
     */
    protected function getGoodsPromotionInfo(&$goods_info, $data)
    {
        // 如果没有SKU列表，则不处理促销信息
        if (empty($goods_info['skuList'])) {
            return;
        }

        // 初始化参数
        $user_id = $data['user_id'] ?? 0;
        $quantity = $data['quantity'] ?? 1;
        $goods_id = $goods_info['id'];

        // 提前准备所有SKU的ID列表
        $sku_ids = [];
        foreach ($goods_info['skuList'] as $sku) {
            $sku_ids[] = $sku['id'];
        }

        // 一次性批量获取所有促销信息
        $flashsale_list = [];
        $wholesale_list = [];
        $userdiscount_list = [];

        // 根据商品的促销标志判断是否需要查询对应的促销信息
        // 批量获取限时折扣信息
        if (!empty($goods_info['is_flashsale_goods']) && $goods_info['is_flashsale_goods'] == 1) {
            $flashsale_list = $this->batchGetGoodsFlashsale($goods_id, $sku_ids);
        }

        // 批量获取批发价信息
        if (!empty($goods_info['is_wholesale_goods']) && $goods_info['is_wholesale_goods'] == 1) {
            $wholesale_list = $this->batchGetGoodsWholesaleRules($goods_id, $sku_ids);
        }

        // 批量获取会员折扣信息
        if (!empty($goods_info['is_userdiscount_goods']) && $goods_info['is_userdiscount_goods'] == 1 && !empty($user_id)) {
            $userdiscount_list = $this->batchGetGoodsUserdiscountLevels($goods_id, $sku_ids, $user_id);
        }

        // 为每个SKU处理促销信息
        foreach ($goods_info['skuList'] as $key => $sku) {
            $sku_id = $sku['id'];

            // 构建结构化的促销信息
            $promotions = [
                // 限时折扣
                'flashsale' => [
                    'enabled' => isset($flashsale_list[$sku_id]),
                    'flashsale_price' => isset($flashsale_list[$sku_id]) ? $flashsale_list[$sku_id]['flashsale_price'] : 0,
                    'start_time' => isset($flashsale_list[$sku_id]) ? $flashsale_list[$sku_id]['start_time'] : 0,
                    'end_time' => isset($flashsale_list[$sku_id]) ? $flashsale_list[$sku_id]['end_time'] : 0,
                    'promotion_type' => TblGoodsPromotionEnum::PROMOTION_TYPE_FLASHSALE,
                    'promotion_type_desc' => TblGoodsPromotionEnum::getPromotionTypeDesc(TblGoodsPromotionEnum::PROMOTION_TYPE_FLASHSALE)
                ],

                // 批发价格
                'wholesale' => [
                    'enabled' => !empty($wholesale_list[$sku_id]),
                    'rules' => isset($wholesale_list[$sku_id]) ? $wholesale_list[$sku_id] : [],
                    'promotion_type' => TblGoodsPromotionEnum::PROMOTION_TYPE_WHOLESALE,
                    'promotion_type_desc' => TblGoodsPromotionEnum::getPromotionTypeDesc(TblGoodsPromotionEnum::PROMOTION_TYPE_WHOLESALE)
                ],

                // 会员等级价格
                'userdiscount' => [
                    'enabled' => !empty($userdiscount_list[$sku_id]),
                    'levels' => isset($userdiscount_list[$sku_id]) ? $userdiscount_list[$sku_id] : [],
                    'promotion_type' => TblGoodsPromotionEnum::PROMOTION_TYPE_USER_DISCOUNT,
                    'promotion_type_desc' => TblGoodsPromotionEnum::getPromotionTypeDesc(TblGoodsPromotionEnum::PROMOTION_TYPE_USER_DISCOUNT)
                ]
            ];

            // 添加到SKU信息中
            $goods_info['skuList'][$key]['promotions'] = $promotions;

            // 计算当前可用的最低价格和对应的促销类型
            $current_price = $sku['sku_price'];
            $current_promotion_type = '';
            $current_promotion_platform = '';
            $current_promotion_related_id = 0;

            // 检查限时折扣
            if ($promotions['flashsale']['enabled'] && $promotions['flashsale']['flashsale_price'] < $current_price) {
                $current_price = $promotions['flashsale']['flashsale_price'];
                $current_promotion_type = TblGoodsPromotionEnum::PROMOTION_TYPE_FLASHSALE;
                $current_promotion_platform = 'default';
                $current_promotion_related_id = $flashsale_list[$sku_id]['id'];
            }

            // 检查批发价
            if ($promotions['wholesale']['enabled']) {
                foreach ($promotions['wholesale']['rules'] as $rule) {
                    if (
                        $quantity >= $rule['quantity_min'] &&
                        ($rule['quantity_max'] == 0 || $quantity <= $rule['quantity_max']) &&
                        $rule['wholesale_price'] < $current_price
                    ) {
                        $current_price = $rule['wholesale_price'];
                        $current_promotion_type = TblGoodsPromotionEnum::PROMOTION_TYPE_WHOLESALE;
                        $current_promotion_platform = 'default';
                        $current_promotion_related_id = $rule['id'];
                    }
                }
            }

            // 检查会员价
            if ($promotions['userdiscount']['enabled'] && $user_id > 0) {
                foreach ($promotions['userdiscount']['levels'] as $level) {
                    if ($level['user_level_price'] < $current_price) {
                        $current_price = $level['user_level_price'];
                        $current_promotion_type = TblGoodsPromotionEnum::PROMOTION_TYPE_USER_DISCOUNT;
                        $current_promotion_platform = 'default';
                        $current_promotion_related_id = $level['id'];
                    }
                }
            }

            // 添加当前最优促销信息
            $goods_info['skuList'][$key]['promotion_platform'] = $current_promotion_platform;
            $goods_info['skuList'][$key]['promotion_type'] = $current_promotion_type;
            $goods_info['skuList'][$key]['promotion_related_id'] = $current_promotion_related_id;
            $goods_info['skuList'][$key]['promotion_price'] = $current_price;

            // 计算折扣率
            if ($current_price < $sku['sku_price'] && $sku['sku_price'] > 0) {
                $discount_rate = round(($sku['sku_price'] - $current_price) / $sku['sku_price'] * 100, 1);
                $goods_info['skuList'][$key]['discount_rate'] = $discount_rate;
            }

            // 如果是默认SKU，额外保存一份到defaultSku中
            if (isset($goods_info['defaultSku']) && $goods_info['defaultSku']['id'] == $sku_id) {
                $goods_info['defaultSku']['promotions'] = $promotions;
                $goods_info['defaultSku']['promotion_platform'] = $current_promotion_platform;
                $goods_info['defaultSku']['promotion_type'] = $current_promotion_type;
                $goods_info['defaultSku']['promotion_related_id'] = $current_promotion_related_id;
                $goods_info['defaultSku']['promotion_price'] = $current_price;

                if (isset($goods_info['skuList'][$key]['discount_rate'])) {
                    $goods_info['defaultSku']['discount_rate'] = $goods_info['skuList'][$key]['discount_rate'];
                }
            }
        }
    }

    /**
     * 批量获取商品限时折扣信息
     * @param int $goods_id 商品ID
     * @param array $sku_ids SKU ID列表
     * @return array 折扣信息，以sku_id为键
     */
    public function batchGetGoodsFlashsale($goods_id, $sku_ids)
    {
        if (empty($sku_ids)) {
            return [];
        }

        $condition = [];
        $condition[] = ['goods_id', '=', $goods_id];
        $condition[] = ['sku_id', 'in', $sku_ids];
        $condition[] = ['status', '=', TblGoodsFlashsaleEnum::STATUS_START];
        $condition[] = ['start_time', '<=', time()];
        $condition[] = ['end_time', '>=', time()];

        $list = $this->flashsaleDao->getGoodsFlashsaleList($condition);

        // 转换为以sku_id为键的关联数组
        $result = [];
        foreach ($list as $item) {
            $result[$item['sku_id']] = $item;
        }

        return $result;
    }

    /**
     * 批量获取商品批发价格规则
     * @param int $goods_id 商品ID
     * @param array $sku_ids SKU ID列表
     * @return array 批发价规则，以sku_id为键
     */
    public function batchGetGoodsWholesaleRules($goods_id, $sku_ids)
    {
        if (empty($sku_ids)) {
            return [];
        }

        $condition = [];
        $condition[] = ['goods_id', '=', $goods_id];
        $condition[] = ['sku_id', 'in', $sku_ids];

        $list = $this->wholesaleDao->getGoodsWholesaleList($condition);

        // 转换为以sku_id为键的关联数组，每个sku_id对应多个规则
        $result = [];
        foreach ($list as $item) {
            $sku_id = $item['sku_id'];

            if (!isset($result[$sku_id])) {
                $result[$sku_id] = [];
            }

            $result[$sku_id][] = [
                'id' => $item['id'],
                'quantity_min' => $item['quantity_min'],
                'quantity_max' => $item['quantity_max'] ?: 0,
                'wholesale_price' => $item['wholesale_price']
            ];
        }

        return $result;
    }

    /**
     * 批量获取商品会员等级价格
     * @param int $goods_id 商品ID
     * @param array $sku_ids SKU ID列表
     * @param int $user_id 用户ID
     * @return array 会员等级价格，以sku_id为键
     */
    public function batchGetGoodsUserdiscountLevels($goods_id, $sku_ids, $user_id)
    {
        if (empty($sku_ids) || empty($user_id)) {
            return [];
        }

        // 获取用户信息，主要是获取用户等级ID
        $userInfo = $this->userDao->getUserInfoById($user_id);
        if (empty($userInfo) || empty($userInfo['growth_level_id'])) {
            return [];
        }

        $condition = [];
        $condition[] = ['goods_id', '=', $goods_id];
        $condition[] = ['sku_id', 'in', $sku_ids];

        $list = $this->userdiscountDao->getGoodsUserdiscountList($condition);

        // 转换为以sku_id为键的关联数组，每个sku_id对应多个等级价格
        $result = [];
        foreach ($list as $item) {
            // 只处理当前用户等级的价格
            if ($item['user_level_id'] != $userInfo['growth_level_id']) {
                continue;
            }

            $sku_id = $item['sku_id'];

            if (!isset($result[$sku_id])) {
                $result[$sku_id] = [];
            }

            // 获取用户等级信息
            $levelInfo = $this->getUserGrowthLevelById($item['user_level_id']);

            $result[$sku_id][] = [
                'id' => $item['id'],
                'user_level_id' => $item['user_level_id'],
                'user_level_name' => $levelInfo['level_name'] ?? '',
                'user_level_price' => $item['user_level_price']
            ];
        }

        return $result;
    }

    /**
     * 获取用户等级信息
     * @param int $level_id 等级ID
     * @return array 等级信息
     */
    protected function getUserGrowthLevelById($level_id)
    {
        // 使用静态缓存避免重复查询
        static $level_cache = [];

        if (!isset($level_cache[$level_id])) {
            $level_cache[$level_id] = $this->userGrowthLevelDao->getUserGrowthLevelInfo([['id', '=', $level_id]]);
        }

        return $level_cache[$level_id];
    }

}