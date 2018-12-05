<?php

namespace common\modules\dbmanager\controllers;

use Yii;
use yii\web\Controller;
use common\modules\dbmanager\models\BackupModel;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
/**
 * Default controller for the `dbmanager` module
 */
class DefaultController extends Controller
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if(isset($_GET['file'])){
            $file="//".$_GET['file'];
        }else{
            $file="";
        }
        $dataArray = $this->prepareFileData();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataArray,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        $dataProvider->pagination->pageSize=6;
        $model=new BackupModel();
        $model->database='all';
        $model->extension='tar';
        $model->backupdatabase=1;
        $model->download=0;
        $model->backupfiles=0;
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'file'=>$file
        ]);
    }
    /**
     * @inheritdoc
     */
    public function actionDownload($id)
    {
        $dumpPath = $this->getModule()->path . StringHelper::basename(ArrayHelper::getValue($this->getModule()->getFileList(), $id));

        return Yii::$app->response->sendFile($dumpPath);
    }
    /**
     * @inheritdoc
     */
    public function actionDeleteAll()
    {
        if (!empty($this->getModule()->getFileList())) {
            $fail = [];
            foreach ($this->getModule()->getFileList() as $file) {
                if (!unlink($file)) {
                    $fail[] = $file;
                }
            }
            if (empty($fail)) {
                Yii::$app->session->setFlash('success', Yii::t('dbManager', 'All dumps successfully removed.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('dbManager', 'Error deleting dumps.'));
            }
        }

        return $this->redirect(['index']);
    }
    /**
     * @inheritdoc
     */
    public function actionDelete($id)
    {
        $dumpFile = $this->getModule()->path . StringHelper::basename(ArrayHelper::getValue($this->getModule()->getFileList(), $id));
        if (unlink($dumpFile)) {
            Yii::$app->session->setFlash('success', Yii::t('dbManager', 'Dump deleted successfully.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('dbManager', 'Error deleting dump.'));
        }

        return $this->redirect(['index']);
    }
    /**
     * @return array
     */
    protected function prepareFileData()
    {
        foreach ($this->getModule()->getFileList() as $id => $file) {
            $columns = [];
            $columns['id'] = $id;
            $columns['type'] = pathinfo($file, PATHINFO_EXTENSION);
            $columns['name'] = StringHelper::basename($file);
            $columns['size'] = Yii::$app->formatter->asSize(filesize($file));
            $columns['create_at'] = Yii::$app->formatter->asDatetime(filectime($file));
            $dataArray[] = $columns;
        }
        ArrayHelper::multisort($dataArray, ['create_at'], [SORT_DESC]);

        return $dataArray;
    }
    public function actionCreateBackup(){
        $post= \Yii::$app->request->post();
        $BackModel=$post['BackupModel'];
        if(!isset($BackModel['database'])){
            $database='';
        }else{
            $database=$BackModel['database'];
        }
        //echo "<pre>";
        //var_dump($BackModel);
        //echo "</pre>";
        //exit;
        $backup =\Yii::$app->backup; 
        $backup->ext=$BackModel['extension'];
        $file = $backup->create($BackModel['backupfiles'],$BackModel['backupdatabase'],$database);
        $downloadFile="";
        if((int)$BackModel['download']==1){
            $downloadFile=\Yii::$app->getRequest()->serverName ."/backups/$file";
        }
        \Yii::$app->session->setFlash('success', 'Backup created Successfully!');
        $this->redirect(["/dbmanager",'file'=>$downloadFile]);
    }
}
