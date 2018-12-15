<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Chuyenmuc;

/**
 * ChuyenmucSearch represents the model behind the search form about `backend\models\Chuyenmuc`.
 */
class ChuyenmucSearch extends Chuyenmuc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cm_id', 'nguoitao', 'nguoicapnhat', 'trangthai', 'thutu'], 'integer'],
            [['tenchuyenmuc', 'slug', 'ngaytao', 'ngaycapnhat', 'anhdaidien', 'gioithieu'], 'safe'],
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
        $query = Chuyenmuc::find();

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
            'cm_id' => $this->cm_id,
            'ngaytao' => $this->ngaytao,
            'ngaycapnhat' => $this->ngaycapnhat,
            'nguoitao' => $this->nguoitao,
            'nguoicapnhat' => $this->nguoicapnhat,
            'trangthai' => $this->trangthai,
            'thutu' => $this->thutu,
        ]);

        $query->andFilterWhere(['like', 'tenchuyenmuc', $this->tenchuyenmuc])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'anhdaidien', $this->anhdaidien])
            ->andFilterWhere(['like', 'gioithieu', $this->gioithieu]);

        return $dataProvider;
    }
}
