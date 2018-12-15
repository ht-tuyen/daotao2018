<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Regions;

/**
 * RegionsSearch represents the model behind the search form about `backend\models\Regions`.
 */
class RegionsSearch extends Regions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['regionId', 'countryId', 'user_edit', 'status', 'sort_order'], 'integer'],
            [['region', 'region_url', 'code', 'ADM1Code'], 'safe'],
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
        $query = Regions::find();

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
            'regionId' => $this->regionId,
            'countryId' => $this->countryId,
            'user_edit' => $this->user_edit,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'region', $this->region])
            ->andFilterWhere(['like', 'region_url', $this->region_url])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'ADM1Code', $this->ADM1Code]);

        return $dataProvider;
    }
}
