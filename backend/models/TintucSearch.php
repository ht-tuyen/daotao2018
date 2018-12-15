<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Tintuc;

/**
 * TintucSearch represents the model behind the search form about `backend\models\Tintuc`.
 */
class TintucSearch extends Tintuc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tt_id', 'idchuyenmuc', 'nguoitao', 'nguoicapnhat', 'trangthai'], 'integer'],
            [['tieude', 'noidung', 'gioithieu', 'anhdaidien', 'slug', 'ngaytao', 'ngaycapnhat'], 'safe'],
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
        $query = Tintuc::find();

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

        $query->orderBy(['tt_id' => SORT_DESC]);
        // grid filtering conditions
        $query->andFilterWhere([
            // 'tt_id' => $this->tt_id,
            'idchuyenmuc' => $this->idchuyenmuc,
            'ngaytao' => $this->ngaytao,
            'ngaycapnhat' => $this->ngaycapnhat,
            'nguoitao' => $this->nguoitao,
            'nguoicapnhat' => $this->nguoicapnhat,
            'trangthai' => $this->trangthai,
        ]);

        $query->andFilterWhere(['like', 'tieude', $this->tieude])
            ->andFilterWhere(['like', 'noidung', $this->noidung])
            ->andFilterWhere(['like', 'gioithieu', $this->gioithieu])
            ->andFilterWhere(['like', 'anhdaidien', $this->anhdaidien])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
