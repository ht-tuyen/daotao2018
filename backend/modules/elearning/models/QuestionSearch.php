<?php

namespace backend\modules\elearning\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\elearning\models\Question;

/**
 * QuestionSearch represents the model behind the search form about `backend\modules\course\models\Question`.
 */
class QuestionSearch extends Question
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'quiz_id', 'question_type', 'point'], 'integer'],
            [['name', 'description', 'answers', 'correct_answer'], 'safe'],
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
        $query = Question::find();

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
            'question_id' => $this->question_id,
            'quiz_id' => $this->quiz_id,
            'question_type' => $this->question_type,
            'point' => $this->point,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'answers', $this->answers])
            ->andFilterWhere(['like', 'correct_answer', $this->correct_answer]);

        return $dataProvider;
    }
}
