<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace site\models;

/**
 * @property Interest[] $interests
 */
class Post extends \shared\models\Post
{
    use InterestRelationTrait;

    const SORT_DATE = 'date';
    const SORT_VOTES = 'votes';

    public $inInterests = [];

    private $_originalContent;

    //TODO: add support for cyrillic urls
    const REGEX_URL = '#(^|.)((?:(?:http|https|ftp|ftps)://|www\.)[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(?:/\S*)?)#';

    public function rules() {
        return array_merge(
            parent::rules(),
            [['inInterests', 'safe']]
        );
    }

    public function scopes() {
        return [
            'byDate' => ['order' => $this->tableAlias . ".dateSubmitted DESC"],
//            'good'   => ['condition' => "(SELECT COUNT(`Post_id`) FROM `Vote` where `type` != 'like' AND $this->tableAlias.id = `Vote`.`Post_id`) <= (SELECT COUNT(`Post_id`) FROM `Vote` where `type` = 'like' AND $this->tableAlias.id = `Vote`.`Post_id`)"],
            'good'   => ['condition' => "(SELECT COUNT(`Post_id`) FROM `Vote` where `type` != 'like' AND $this->tableAlias.id = `Vote`.`Post_id`) = 0"],
        ];
    }

    public function getIsGood() {
//        return $this->dbConnection->createCommand('SELECT (SELECT COUNT(*) FROM `Vote` WHERE `type` != "like" AND `Post_id` = :id) <= (SELECT COUNT(*) FROM `Vote` WHERE `type` = "like" AND `Post_id` = :id)')
//            ->queryScalar(['id' => $this->id]);

        return $this->dbConnection->createCommand('SELECT COUNT(*) FROM `Vote` WHERE `type` != "like" AND `Post_id` = :id')
            ->queryScalar(['id' => $this->id]) == 0;
    }

    public function sortBy($sort) {

        switch ($sort) {
            case self::SORT_DATE:
                $this->dbCriteria->mergeWith(['order' => $this->tableAlias . '.dateSubmitted DESC']);
                break;
            case self::SORT_VOTES:
                $this->dbCriteria->mergeWith(
                    ['order' => "(SELECT COUNT(*) FROM Vote WHERE Vote.Post_id = `$this->tableAlias`.`id` AND Vote.`type` = 'like') DESC, `$this->tableAlias`.`dateSubmitted` DESC"]
                );
                break;
        }

        return $this;
    }


    public function afterFind() {
        $this->_originalContent = $this->content;
    }

    public function beforeValidate() {
        if ($this->content != $this->_originalContent) {
            list($this->content, $this->htmlContent) = $this->transformLinks(\CHtml::encode(strip_tags($this->content)));
        }

        if (!is_array($this->inInterests)) {
            $this->inInterests = [$this->inInterests];
        }
        if ($this->inInterests) {
            $interests = $this->interests(['index' => 'id']);

            foreach ($this->inInterests as $id) {
                if (!array_key_exists($id, $interests)) {
                    if ($interest = Interest::model()->findByPk($id)) {
                        $interests[$id] = $interest;
                    }
                }
            }
            $this->interests = array_values($interests);
        }
        return parent::beforeValidate();
    }

    public function getLocalDateSubmitted() {
        //TODO: implement retrieving of local time
        return $this->dateSubmitted;
    }

    public function save($runValidation = true, $attributes = null) {
        $transaction = $this->dbConnection->currentTransaction ? false : $this->dbConnection->beginTransaction();
        try {
            if (parent::save($runValidation, $attributes)) {
                foreach ($this->interests as $index => $interest) {
                    $index < 3 ? $this->addInterest($interest) : $this->removeInterest($interest);
                }
                $transaction && $transaction->commit();

                $this->refresh();
                return true;
            }
        } catch (\Exception $e) {
            $transaction && $transaction->rollback();
            throw $e;
        }

        return false;
    }


    private function transformLinks($content) {
        $htmlContent = $content;
        if ($content) {
            // Check if there is a url in the text
            if (preg_match_all(self::REGEX_URL, $content, $matches)) {

                foreach ($matches[2] as $index => $url) {
                    $previousSymbol = $matches[1][$index];
                    if ($previousSymbol && ' ' != $previousSymbol) {
                        $content = str_replace($previousSymbol . $url, "$previousSymbol $url", $content);
                        $htmlContent = str_replace($previousSymbol . $url, "$previousSymbol $url", $htmlContent);
                    }
                }

                $shorten = Yii()->urlShorten;
                foreach (array_unique($matches[2]) as $url) {
                    if ('www' == substr($url, 0, 3)) {
                        $realUrl = "http://$url";
                    } else {
                        $realUrl = $url;
                    }
                    $shortUrl = $shorten($realUrl);
                    $content = str_replace($url, $shortUrl, $content);
                    $htmlContent = str_replace($url, \CHtml::link('link', $shortUrl), $htmlContent);
                }
            }
        }
        return [$content, $htmlContent];
    }

    public function getContentLength() {
        return mb_strlen($this->content, Yii()->charset);
    }

    public function getRestAttributes() {
        $user = Yii()->user->model;
        if($user && ($vote = $user->getVote($this))){
            $vote = $vote->type;
        }
        else{
            $vote = null;
        }

        $interests = [];
        foreach($this->interests as $interest){
            $interests[] = $interest->getRestAttributes();
        }

        return [
            'id' => $this->id,
            'content' => $this->content,
            'htmlContent' => $this->htmlContent,
            'date' => Yii()->dateFormatter->format('HH:mm dd MMM yyy', $this->dateSubmitted),
            'isFavorite' => $user && $user->isFavorite($this),
            'likesCount' => $this->likesCount,
            'isGood' => $this->getIsGood(),
            'isModerated' => false,
            'userVote' => $vote,
            'author' => $this->author->restAttributes,
            'interests' => $interests,

            'error' => $this->getError('content'),
            'success' => !$this->isNewRecord,
        ];
    }
}