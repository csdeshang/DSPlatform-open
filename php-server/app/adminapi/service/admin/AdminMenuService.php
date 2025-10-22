<?php


namespace app\adminapi\service\admin;

use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\common\dao\admin\AdminMenuDao;
use app\common\enum\admin\AdminMenuEnum;

class AdminMenuService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new AdminMenuDao();
    }

    public function getAdminMenuList(array $data)
    {
        $condition = array();
        $result = $this->dao->getAdminMenuList($condition);
        return $result;
    }


    public function getAdminMenuInfo(array $data)
    {
        $condition = array();
        $condition[] = array('id', '=', $data['id']);
        $field = '*';
        $admin_menu = $this->dao->getAdminMenuInfo($condition, $field);
        return $admin_menu;
    }




    public function createAdminMenu(array $data)
    {
        // 可放验证器
        // 判断父级分类是否存在
        if ($data['pid'] != 0) {
            $condition = [
                ['id', '=', $data['pid']],
            ];
            $has_cate = $this->dao->getAdminMenuCount($condition);
            if ($has_cate == 0) {
                throw new CommonException('父级分类不存在');
            }
        }

        // 根据路径生成可读的名称
        $data['name'] = $this->generateReadableNameFromPath($data['path']);

        // 判断路径是否存在，如果是添加按钮 则不判断 [按钮 path 为空]
        if ($data['type'] != AdminMenuEnum::TYPE_BUTTON) {
            $condition = [
                ['path', '=', $data['path']],
            ];
            $has_path = $this->dao->getAdminMenuCount($condition);
            if ($has_path > 0) {
                throw new CommonException('路径已存在');
            }
        }

        //添加
        $id = $this->dao->createAdminMenu($data);
        return $id;
    }


    public function updateAdminMenu(array $data)
    {
        // 可放验证器
        // 检测  pid
        $list = $this->getAdminMenuList(array());
        $result = checkParentId($data['pid'], $data['id'], $list, 'id', 'pid');
        if (!$result) {
            throw new CommonException('分类ID ' . $data['id'] . ' 不能将父分类ID ' . $data['pid'] . ' 设置为子分类，这将导致循环引用');
        }



        // 根据路径生成可读的名称
        $data['name'] = $this->generateReadableNameFromPath($data['path']);

        // 判断路径是否存在,如果是添加按钮 则不判断[按钮 path 为空]
        if ($data['type'] != AdminMenuEnum::TYPE_BUTTON) {
            $condition = [
                ['path', '=', $data['path']],
                ['id', '!=', $data['id']],
            ];
            $has_path = $this->dao->getAdminMenuCount($condition);
            if ($has_path > 0) {
                throw new CommonException('路径已存在');
            }
        }

        //更新
        $result = $this->dao->updateAdminMenu([['id', '=', $data['id']]], $data);
        return $result;
    }


    public function deleteAdminMenu(array $data)
    {
        $id = $data['id'];
        //判断是否有下级
        $has_child = $this->dao->getAdminMenuCount([['pid', '=', $id]]);
        if ($has_child > 0) {
            throw new CommonException('该菜单有下级菜单，无法删除');
        }

        $result = $this->dao->deleteAdminMenu([['id', '=', $id]]);
        if ($result == 0) {
            throw new CommonException('删除失败');
        }
        return true;
    }

    /**
     * 获取所有菜单的apiurl
     */
    public function getAllAdminMenuApiurlList()
    {
        $condition = array();
        $condition[] = array('api_url', '<>', '');
        $result = $this->dao->getAdminMenuColumn($condition, 'api_url');
        return $result;
    }



    /**
     * 根据路径生成可读的名称
     */
    public function generateReadableNameFromPath($path)
    {
        // 去掉路径的首尾斜杠
        $trimmedPath = trim($path, '/');
        // 将斜杠替换为下划线
        $name = str_replace('/', '_', $trimmedPath);
        return $name;
    }
}
