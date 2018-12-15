<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Role;

/**
 * RoleSearch represents the model behind the search form about `backend\models\Role`.
 */
class RoleSearch extends Role
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'p_id', 'status', 'admin_use', 'allow_change_user', 'allow_show_price'], 'integer'],
            [['role_name', 'role_label', 'acl_desc', 'create_time', 'update_time', 'list_status'], 'safe'],
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
        $query = Role::find();

        // add conditions that should always apply here

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
            'role_id' => $this->role_id,
            'p_id' => $this->p_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'status' => $this->status,
            'admin_use' => $this->admin_use,
            'allow_change_user' => $this->allow_change_user,
            'allow_show_price' => $this->allow_show_price,
        ]);

        $query->andFilterWhere(['like', 'role_name', $this->role_name])
            ->andFilterWhere(['like', 'role_label', $this->role_label])
            ->andFilterWhere(['like', 'acl_desc', $this->acl_desc])
            ->andFilterWhere(['like', 'list_status', $this->list_status]);

        return $dataProvider;
    }
}
