<?php

namespace app\modules\user\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user\models\User;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form about `app\modules\user\models\User`.
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
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
