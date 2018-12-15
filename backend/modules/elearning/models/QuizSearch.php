<?php

namespace backend\modules\elearning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\elearning\models\Quiz;

/**
 * QuizSearch represents the model behind the search form about `backend\modules\course\models\Quiz`.
 */
class QuizSearch extends Quiz
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quiz_id', 'course_id', 'number_questions', 'time', 'state', 'created_by', 'modified_by'], 'integer'],
            [['name', 'short_desc', 'published_start', 'published_end', 'created_date', 'modified_date'], 'safe'],
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
        $query = Quiz::find();

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
            'quiz_id' => $this->quiz_id,
            'course_id' => $this->course_id,
            'number_questions' => $this->number_questions,
            'published_start' => $this->published_start,
            'published_end' => $this->published_end,
            'time' => $this->time,
            'state' => $this->state,
            'created_by' => $this->created_by,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'short_desc', $this->short_desc]);

        return $dataProvider;
    }
}
