<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\models;
trait InterestRelationTrait
{
    protected $interestIds;

    public function addInterest(Interest $interest) {
        $table = static::INTEREST_RELATION_TABLE;

        $result = true;
        $transaction = $this->dbConnection->currentTransaction ? false : $this->dbConnection->beginTransaction();
        if (!$this->hasInterest($interest)) {
            $result = $this->dbConnection->createCommand(
                "INSERT INTO `$table`
                SET `{$this->formName()}_id` = :modelId, `Interest_id` = :interestId"
            )->execute(['modelId' => $this->id, 'interestId' => $interest->id]);
        }
        $transaction && $transaction->commit();
        return $result;
    }

    public function hasInterest(Interest $interest) {
        $table = static::INTEREST_RELATION_TABLE;

        if (null === $this->interestIds) {
            $this->interestIds = $this->dbConnection->createCommand(
                "
                SELECT Interest_id
                FROM `$table`
                WHERE `{$this->formName()}_id` = :modelId
                "
            )->queryColumn(['modelId' => $this->id]);
        }

        return in_array($interest->id, $this->interestIds);
    }

    public function removeInterest($interest) {
        $table = static::INTEREST_RELATION_TABLE;

        return $this->dbConnection->createCommand(
            "DELETE FROM `$table`
            WHERE `{$this->formName()}_id` = :modelId AND `Interest_id` = :interestId"
        )->query(['modelId' => $this->id, 'interestId' => $interest->id]);
    }
}
