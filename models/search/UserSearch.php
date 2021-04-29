<?php

namespace skyline\yii\user\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use skyline\yii\user\models\User;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form about `skyline\yii\user\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'status',
                    'modifiedBy'
                ],
                'integer'
            ],
            [
                [
                    'lastLogin',
                    'email',
                    'name',
                    'dateCreated',
                    'lastModified'
                ],
                'safe'
            ],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        /**
         * If we cannot validate the model with the given params, return the base query (all records)
         */
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'lastLogin' => $this->lastLogin,
            'User.status' => $this->status,
            'dateCreated' => $this->dateCreated,
            'lastModified' => $this->lastModified,
            'modifiedBy' => $this->modifiedBy,
        ]);

        $query->andFilterWhere(['like', 'authKey', $this->authKey])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'User.name', $this->name]);

        return $dataProvider;
    }
}
