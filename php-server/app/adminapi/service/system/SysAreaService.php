<?php


namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;

use app\deshang\exceptions\CommonException;

use app\common\dao\system\SysAreaDao;

class SysAreaService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
        $this->dao = new SysAreaDao();
    }

    public function getSysAreaList(array $data)
    {

        $condition = array();
        if (isset($data['deep'])) {
            $condition[] = array('deep', '<=', $data['deep']);
        }

        if (isset($data['pid'])) {
            $condition[] = array('pid', '=', $data['pid']);
        }

        $result = $this->dao->getAreaList($condition);

        foreach ($result as &$item) {
            $hasChildren = $this->dao->getAreaCount(['pid' => $item['id']]);
            $item['hasChildren'] = $hasChildren > 0 ? true : false;
        }

        return $result;
    }



    public function getSysAreaInfo($id)
    {

        $condition = array();
        $condition[] = array('id', '=', $id);
        $field = '*';
        $sys_area = $this->dao->getAreaInfo($condition, $field);


        if (empty($sys_area)) {
            throw new CommonException('地区不存在');
        }

        return $sys_area;
    }


    public function createSysArea(array $data)
    {
        // 可放验证器
        // 判断父级分类是否存在
        if ($data['pid'] != 0) {
            $condition = [
                ['id', '=', $data['pid']],
            ];
            $has_cate = $this->dao->getAreaCount($condition);
            if ($has_cate == 0) {
                throw new CommonException('父级分类不存在');
            }
        }
        $area_id = $this->dao->createArea($data);

        // 更新深度
        if ($data['pid'] == 0) {
            $this->updateSysAreaDeep($area_id, 1);
        } else {
            $parent = $this->dao->getAreaInfo(['id' => $data['pid']]);
            $this->updateSysAreaDeep($area_id, $parent['deep'] + 1);
        }

        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_AREA_TAG);

        return $area_id;
    }


    public function updateSysArea(array $data)
    {
        // 可放验证器
        // 检测pid
        $list = $this->getSysAreaList(array());
        $result = checkParentId($data['pid'], $data['id'], $list, 'id', 'pid');
        if (!$result) {
            throw new CommonException('分类ID ' . $data['id'] . ' 不能将父分类ID ' . $data['pid'] . ' 设置为子分类，这将导致循环引用');
        }

        $result = $this->dao->updateArea($data);

        // 更新深度
        if ($data['pid'] == 0) {
            $this->updateSysAreaDeep($data['id'], 1);
        } else {
            $parent = $this->dao->getAreaInfo(['id' => $data['pid']]);
            $this->updateSysAreaDeep($data['id'], $parent['deep'] + 1);
        }

        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_AREA_TAG);

        return $result;
    }


    // 单个删除
    public function deleteSysArea($id)
    {
        //判断是否有下级
        $has_child = $this->dao->getAreaCount([['pid', '=', $id]]);
        if ($has_child > 0) {
            throw new CommonException('该菜单有下级菜单，无法删除');
        }

        $result = $this->dao->deleteArea([['id', '=', $id]]);

        if ($result === false) {
            throw new CommonException('删除失败');
        }
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_AREA_TAG);
        
        return true;
    }

    // 遍历更新深度
    public function updateSysAreaDeep($areaId, $newDepth)
    {
        // 验证参数
        if (!is_numeric($areaId) || !is_numeric($newDepth)) {
            throw new CommonException('Invalid provided.');
        }

        // 更新当前区域的深度
        $area = $this->dao->getAreaInfo(['id' => $areaId]);
        if (empty($area)) {
            throw new CommonException('Area not found.');
        }


        $area->deep = $newDepth;
        $area->save();

        // 获取所有下级区域
        $children = $this->dao->getAreaList(['pid' => $areaId]);
        foreach ($children as $child) {
            // 递归更新下级区域的深度
            $this->updateSysAreaDeep($child['id'], $newDepth + 1); // 深度加 1
        }


        return true; // 返回成功状态
    }
}
