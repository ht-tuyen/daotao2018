<?php
namespace common\widgets;
use Yii;
use yii\helpers\ArrayHelper;
use iutbay\yii2kcfinder\KCFinderAsset;
use iutbay\yii2kcfinder\KCFinder;

class CKEditor extends \dosamigos\ckeditor\CKEditor
{

    public $enableKCFinder = true;

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        if ($this->enableKCFinder)
        {
            $this->registerKCFinder();
        }

        parent::registerPlugin();
    }

    /**
     * Registers KCFinder
     */
    protected function registerKCFinder()
    {

        $_SESSION['KCFINDER'] = [

            'disabled' => false,
            'uploadURL'=>'/upload',
            'uploadDir'=>Yii::getAlias('@upload')

        ];

        $register = KCFinderAsset::register($this->view);
        $kcfinderUrl = $register->baseUrl;

        $browseOptions = [
            'disabled' => false,
            'filebrowserBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=files',
            'filebrowserUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=files',
            'filebrowserImageBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=images',
            'filebrowserImageUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=images',
        ];

        $kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
            'uploadURL' => Yii::getAlias('@upload'),
            'disabled' => false,
            'access' => [
                'files' => [
                    'upload' => true,
                    'delete' => true,
                    'copy' => true,
                    'move' => true,
                    'rename' => true,
                ],
                'dirs' => [
                    'create' => true,
                    'delete' => true,
                    'rename' => true,
                ],
            ],
        ]);

        // Set kcfinder session options
        Yii::$app->session->set('KCFINDER', $kcfOptions);

        $this->clientOptions = ArrayHelper::merge($browseOptions, $this->clientOptions);
    }

}