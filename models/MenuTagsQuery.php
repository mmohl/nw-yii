<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MenuTags]].
 *
 * @see MenuTags
 */
class MenuTagsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return MenuTags[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MenuTags|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
