<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Log;

/**
 * LogSearch represents the model behind the search form about `backend\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'action_type', 'user_id', 'remote_addr', 'status'], 'integer'],
            [['action_info', 'action_controller', 'action_model', 'edited', 'create_time', 'update_time'], 'safe'],
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
        $query = Log::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['create_time'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // $query->orderBy([
        //     'create_time' => SORT_DESC,
        // ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'action_type' => $this->action_type,
            'user_id' => $this->user_id,
            'remote_addr' => $this->remote_addr,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'action_info', $this->action_info])
            ->andFilterWhere(['like', 'action_controller', $this->action_controller])
            ->andFilterWhere(['like', 'action_model', $this->action_model])
            ->andFilterWhere(['like', 'edited', $this->edited]);

        return $dataProvider;
    }
}
