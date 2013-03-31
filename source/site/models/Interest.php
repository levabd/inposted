<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\models;
class Interest extends \shared\models\Interest
{
    protected $parentIds;

    public function addParent($interest) {
        if ($interest->id == $this->id) {
            return false;
        }
        $table = static::PARENT_RELATION_TABLE;

        $result = true;
        $transaction = $this->dbConnection->currentTransaction ? false : $this->dbConnection->beginTransaction();
        if (!$this->hasParent($interest, true)) {
            $result = $this->dbConnection->createCommand(
                "INSERT INTO `$table`
                SET `Interest_id` = :modelId, `Parent_id` = :interestId"
            )->execute(['modelId' => $this->id, 'interestId' => $interest->id]);
        }
        $transaction && $transaction->commit();
        return $result;
    }

    public function hasParent($interest, $indirect = false) {
        $table = static::PARENT_RELATION_TABLE;

        if (null === $this->parentIds) {
            $this->parentIds = $this->dbConnection->createCommand(
                "
                SELECT Parent_id
                FROM `$table`
                WHERE `Interest_id` = :modelId
                "
            )->queryColumn(['modelId' => $this->id]);
        }

        return in_array($interest->id, $indirect ? $this->indirectParentIds : $this->parentIds);
    }

    public function removeParent($interest) {
        $table = static::PARENT_RELATION_TABLE;

        return $this->dbConnection->createCommand(
            "DELETE FROM `$table`
            WHERE `Interest_id` = :modelId AND `Parent_id` = :interestId"
        )->query(['modelId' => $this->id, 'interestId' => $interest->id]);
    }

    public function getIndirectParentIds() {
        $table = static::PARENT_RELATION_TABLE;

        $parents = [];

        $map = $this->dbConnection->createCommand("SELECT * FROM $table")->queryAll();

        foreach ($map as $link) {
            if (!isset($parents[$link['Interest_id']])) {
                $parents[$link['Interest_id']] = [];
            }

            $parents[$link['Interest_id']][] = $link['Parent_id'];
        }

        $processed = [];

        $getParents = function ($id) use ($parents, &$processed, &$getParents) {
            if (in_array($id, $processed) || !isset($parents[$id])) {
                return [];
            }

            $processed[] = $id;
            $result = $parents[$id];
            foreach ($parents[$id] as $parentId) {
                $result = array_merge($result, $getParents($parentId));
            }

            return array_unique($result);
        };

        return $getParents($this->id);

    }


    public function getIndirectChildrenIds() {
        $table = static::PARENT_RELATION_TABLE;

        $children = [];

        $map = $this->dbConnection->createCommand("SELECT * FROM $table")->queryAll();

        foreach ($map as $link) {
            if (!isset($children[$link['Parent_id']])) {
                $children[$link['Parent_id']] = [];
            }

            $children[$link['Parent_id']][] = $link['Interest_id'];
        }

        $processed = [];

        $getChildren = function ($id) use ($children, &$processed, &$getChildren) {
            if (in_array($id, $processed) || !isset($children[$id])) {
                return [];
            }

            $processed[] = $id;
            $result = $children[$id];
            foreach ($children[$id] as $parentId) {
                $result = array_merge($result, $getChildren($parentId));
            }

            return array_unique($result);
        };

        return $getChildren($this->id);

    }

    public function getRestAttributes() {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'fullName' => $this->getFullName(),
        ];
    }


}
