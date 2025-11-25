<?php

namespace app\adminapi\service\editable;

use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\common\dao\editable\EditablePageDao;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;

class EditablePageService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEditablePages(array $data): array
    {
        $condition = [];
        if (isset($data['title']) && !empty($data['title'])) {
            $condition[] = ['title', 'like', "%" . $data['title'] . "%"];
        }
        if (isset($data['platform']) && !empty($data['platform'])) {
            $condition[] = ['platform', '=', $data['platform']];
        }
        $condition[] = ['store_id', '=', 0];




        return (new EditablePageDao())->getEditablePages($condition);
    }


    public function getEditablePageInfo(int $id): array
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $condition[] = ['store_id', '=', 0];



        return (new EditablePageDao())->getEditablePageInfo($condition);
    }

    public function createEditablePage(array $data): int
    {
        $data['store_id'] = 0;
        return (new EditablePageDao())->createEditablePage($data);
    }

    public function updateEditablePage(int $id, array $data): int
    {

        $result = (new EditablePageDao())->updateEditablePage([['id', '=', $id], ['store_id', '=', 0]], $data);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::EDITABLE_PAGE_TAG);

        return $result;
    }

    public function deleteEditablePage(int $id): int
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $condition[] = ['store_id', '=', 0];

        $result = (new EditablePageDao())->deleteEditablePage($condition);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::EDITABLE_PAGE_TAG);

        return $result;
    }
    
}
