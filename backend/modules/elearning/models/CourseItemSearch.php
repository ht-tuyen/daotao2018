<?php

namespace backend\modules\elearning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\elearning\models\CourseItem;

/**
 * CourseItemSearch represents the model behind the search form about `backend\modules\course\models\CourseItem`.
 */
class CourseItemSearch extends CourseItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'category_id', 'state', 'ordering', 'created_by', 'modified_by'], 'integer'],
            [['name', 'thumbnail', 'short_desc', 'full_desc', 'created_date', 'modified_date'], 'safe'],
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
        $query = CourseItem::find();

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
            'course_id' => $this->course_id,
            'category_id' => $this->category_id,
            'state' => $this->state,
            'ordering' => $this->ordering,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'short_desc', $this->short_desc])
            ->andFilterWhere(['like', 'full_desc', $this->full_desc]);

        return $dataProvider;
    }
}
