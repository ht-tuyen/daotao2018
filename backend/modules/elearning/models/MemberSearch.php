<?php

namespace backend\modules\elearning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\elearning\models\Member;

/**
 * MemberSearch represents the model behind the search form about `backend\modules\elearning\models\Member`.
 */
class MemberSearch extends Member
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'user_type', 'idthanhvien', 'role_id', 'status', 'first_change_pass'], 'integer'],
            [['fullname', 'username', 'password', 'salt', 'birthday', 'email', 'avatar', 'address', 'last_login', 'phone', 'mobile', 'created_at', 'created_by', 'updated_at', 'updated_by', 'last_login_time', 'about', 'token', 'time_token', 'linhvucquantam'], 'safe'],
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
        $query = Member::find();

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
            'user_id' => $this->user_id,
            'type' => $this->type,
            'user_type' => $this->user_type,
            'idthanhvien' => $this->idthanhvien,
            'role_id' => $this->role_id,
            'last_login' => $this->last_login,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'last_login_time' => $this->last_login_time,
            'first_change_pass' => $this->first_change_pass,
            'time_token' => $this->time_token,
        ]);

        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'salt', $this->salt])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'about', $this->about])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'linhvucquantam', $this->linhvucquantam]);

        return $dataProvider;
    }
}
