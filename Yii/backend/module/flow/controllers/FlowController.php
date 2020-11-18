<?php
namespace backend\module\flow\controllers;

use yii\db\Query;
use yii\web\Controller;

class FlowController extends Controller
{
    public function actionIndex()
    {
        return array('data'=>'ss','msg'=>'flow-index');
    }
//    根据用户id获取用户名
    public function getUserName($id)
    {
        return (new Query())
            ->select('*')
            ->from('user')
            ->where(['id'=>$id])
            ->andWhere(['status'=>1])
            ->one();
    }
//    获得个人的申请列表
//参数：用户id
    public function actionGetflow()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $query = (new Query())
            ->select('*')
            ->from('flows')
            ->where(['userid'=>$id])
            ->all();
        if($query)
        {
            $list = [];
            for($i=0;$i<count($query);$i++)
            {
                $list[$i]['id']=$query[$i]['id'];
                $list[$i]['name'] = $query[$i]['name'];
                switch ($query[$i]['stage'])
                {
                    case 1:
                    {
                        $list[$i]['stage'] = '未审批';
                        break;
                    }
                    case 2:
                    {
                        $list[$i]['stage'] = '正在审批';
                        break;
                    }
                    case 3:
                    {
                        $list[$i]['stage'] = '完成审批';
                        break;
                    }
                    default:
                    {
                        $list[$i]['stage']= '——';
                        break;
                    }
                }
                switch ($query[$i]['result'])
                {
                    case 1:
                    {
                        $list[$i]['result']= '同意审批';
                        break;
                    }
                    case 2:
                    {
                        $list[$i]['result']= '否定审批';
                        break;
                    }
                    case 3:
                    {
                        $list[$i]['result']= '未审批';
                        break;
                    }
                    default:
                    {
                        $list[$i]['result']= '——';
                        break;
                    }

                }
                $list[$i]['ctime']=$query[$i]['ctime'];
                $list[$i]['status']=$query[$i]['status'];
                if($query[$i]['auth']==0)
                {
                    $list[$i]['auth'] = '--';
                }
                else
                {
                    $list[$i]['auth']=$this->getUserName($query[$i]['auth'])['username'];
                }
            }
            return array('data'=>$list,'msg'=>'个人申请信息');
        }
        else
        {
            return array('data'=>1,'msg'=>'还未有申请');
        }
    }
//    申请编号
    public function appID()
    {
        return (new Query())
            ->select('*')
            ->from('flows')
            ->max('id')+1;
    }
//    创建新的申请
//参数：用户id,申请表名，审批人列表，申请表中的内容
    public function actionCreateflow()
    {
        $request = \Yii::$app->request;
        $id = $this->appID();
        $appName = $request->post('name');
        $appUserid = $request->post('userid');
        $appMember = $request->post('member');
        $appMember = join(';',$appMember);
//        最初为未审批stage=1,result=3
        $appStage = 1;
        $appAuth = 0;
        $appResult = 3;
        $appCtime = date('y-m-d h:i:s',time());
        $appStatus =1;
        $appContent = $request->post('content');
//        审批表中包含内容：审批项目，审批内容
        for($i=0;$i<count($appContent);$i++)
        {
            $fid = $i+1;
            $fname = $appContent[$i]['fname'];
            $fcontent = $appContent[$i]['fcontent'];
            $insertF = \Yii::$app->db->createCommand()->insert('detail',
            array('id'=>$id,'fid'=>$fid,'fname'=>$fname,'content'=>$fcontent,'status'=>1))->execute();
        }
        $insertApp = \Yii::$app->db->createCommand()->insert('flows',
        array('id'=>$id,'name'=>$appName,'userid'=>$appUserid,'member'=>$appMember,
            'stage'=>$appStage,'auth'=>$appAuth,'result'=>$appResult,'ctime'=>$appCtime,'status'=>$appStatus))->execute();
        if($insertApp)
        {
            return array('data'=>$insertApp,'msg'=>'创建申请成功');
        }
        else
        {
            return array('data'=>$insertApp,'msg'=>'创建申请失败');
        }
    }
//    删除申请
    public function actionDeleteflow()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $deleteApp = \Yii::$app->db->createCommand()->delete('flows',['id'=>$id])->execute();
        $deleteApp2 = \Yii::$app->db->createCommand()->delete('detail',['id'=>$id])->execute();
        if($deleteApp&&$deleteApp2)
        {
            return array('data'=>[$deleteApp,$deleteApp2],'msg'=>'删除成功');
        }
        else
        {
            return array('data'=>[$deleteApp,$deleteApp2],'msg'=>'删除失败');
        }
    }
}
