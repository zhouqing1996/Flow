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
//    审批状态
    public function Stage($s)
    {
        switch ($s)
        {
            case 1:
                return '未审批';
            case 2:
                return '正在审批';
            case 3:
                return'完成审批';
            default:
                return '——';
        }
    }
//    审批结果
    public function Result($r)
    {
        switch ($r)
        {
            case 1:
                return '同意审批';
            case 2:
                return '否定审批';
            case 3:
                return '未审批';
            default:
                return '——';
        }
    }
//    审批意见
    public function Opinion($p)
    {
        switch ($p)
        {
            case 1:
                return '通过';
            case 2:
                return '不通过';
            default:
                return '——';
        }
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
                $list[$i]['stage'] =$this->Stage($query[$i]['stage']);
                $list[$i]['result']= $this->Result($query[$i]['result']);
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
        $appCtime = date('Y-m-d H:i:s',time());
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
//    待审批的流程:(1)审批列表中人员id包含该id,且该流程当前审批人为该id之前的一个id,
//  而且此审批没有被终止result!=2,审批没有完成，stage!=3
//参数：用户id
    public function actionPendmanage()
    {
        $request = \Yii::$app->request;
        $uid = $request->post('id');
        $query = (new Query())
            ->select('*')
            ->from('flows')
            ->where(['or',['like','member',$uid]])
            ->andWhere(['<>','stage',3])
            ->andWhere(['<>','result',2])
            ->andwhere(['status'=>1])
            ->all();
        if($query)
        {
            $list =[];
            $k=0;
            for($i=0;$i<count($query);$i++)
            {
                $member = explode(';',$query[$i]['member']);
                if($uid==$member[0]&&$query[$i]['auth']==0)
                {
//                    第一个审批人,且此时还未审批
                    $list[$k]['id']=$query[$i]['id'];
                    $list[$k]['name'] = $query[$i]['name'];
                    $list[$k]['userid']=$this->getUserName($query[$i]['userid'])['username'];
                    $list[$k]['stage'] =$this->Stage($query[$i]['stage']);
                    $list[$k]['auth']='--';
                    $list[$k]['result']=$this->Result($query[$i]['result']);
                    $list[$k]['ctime'] = $query[$i]['ctime'];
                    $list[$k]['status'] = $query[$i]['status'];
                    $k++;
                }
                else
                {
//                    查找当前审核用户的索引
                    $index = array_search($query[$i]['auth'],$member);
//                    return array('data'=>$member[$index+1],'msg'=>'待审批的文件');
                    if($member[$index+1]==$uid)
                    {
                        $list[$k]['id']=$query[$i]['id'];
                        $list[$k]['name'] = $query[$i]['name'];
                        $list[$k]['userid']=$this->getUserName($query[$i]['userid'])['username'];
                        $list[$k]['stage'] =$this->Stage($query[$i]['stage']);
                        $list[$k]['auth']=$this->getUserName($query[$i]['auth'])['username'];
                        $list[$k]['result']=$this->Result($query[$i]['result']);
                        $list[$k]['ctime'] = $query[$i]['ctime'];
                        $list[$k]['status'] = $query[$i]['status'];
                        $k++;
                    }
                }
            }
            return array('data'=>$list,'msg'=>'待审批的文件');
        }
        else
        {
            return array('data'=>$query,'msg'=>'还没有待审批的文件');
        }
    }
//    已审批的文件
    public function actionFinishmanage()
    {
        $request = \Yii::$app->request;
        $uid = $request->post('id');
        $q = (new Query())
            ->select('*')
            ->from('flow')
            ->where(['userid'=>$uid])
            ->all();
        if($q)
        {
            $list = [];
            for($j=0;$j<count($q);$j++)
            {
                $query = (new Query())
                    ->select('*')
                    ->from('flows')
                    ->where(['id'=>$q[$j]['id']])
                    ->andwhere(['status'=>1])
                    ->one();
                $list[$j]['id']=$query['id'];
                $list[$j]['name'] = $query['name'];
                $list[$j]['userid']=$this->getUserName($query['userid'])['username'];
                $list[$j]['result']=$this->Result($query['result']);
                $list[$j]['ctime'] = $query['ctime'];
                $list[$j]['other'] = $q[$j]['other'];
                $list[$j]['status'] = $query['status'];

            }
            return array('data'=>$list,'msg'=>'已审批的文件');
        }
        else
        {
            return array('data'=>$q,'msg'=>'没有已审批的文件');
        }


    }
//    审批文件时查看申请表
    public function actionLookmanage()
    {
        $request = \Yii::$app->request;
        $mid = $request->post('id');
//        基本 信息
        $info = (new Query())
            ->select('*')
            ->from('flows')
            ->where(['id'=>$mid])
            ->one();
        $info['userid']=$this->getUserName($info['userid'])['username'];
        $info['member'] = explode(';',$info['member']);
        for($i=0;$i<count($info['member']);$i++)
        {
            $info['member'][$i] = $this->getUserName($info['member'][$i])['username'];
        }
        if($info['auth']==0)
        {
            $info['auth']='--';
        }
        else
        {
            $info['auth'] =$this->getUserName($info['auth'])['username'];
        }
        $info['stage']=$this->Stage($info['stage']);
        $info['result'] = $this->Result($info['result']);

//        审批信息
        $manageInfo = (new Query())
            ->select('*')
            ->from('flow')
            ->where(['id'=>$mid])
            ->all();
        for($i=0;$i<count($manageInfo);$i++)
        {
            $manageInfo[$i]['userid'] = $this->getUserName($manageInfo[$i]['userid'])['username'];
            $manageInfo[$i]['flow'] = $this->Opinion($manageInfo[$i]['flow']);
        }
//        审批表内容
        $appInfo = (new Query())
            ->select('*')
            ->from('detail')
            ->where(['id'=>$mid])
            ->all();

        return array('data'=>[$info,$manageInfo,$appInfo],'msg'=>'申请表信息');
    }
//    审批
    public function actionManage()
    {
        $request = \Yii::$app->request;
        $mid = $request->post('mid');
        $uid = $request->post('uid');
        $flow = $request->post('flow');
        $other = $request->post('other');
        $query = (new Query())
            ->select('*')
            ->from('flows')
            ->where(['id'=>$mid])
            ->one();
        $Ctime = date('y-m-d h:i:s',time());
        $member = explode(';',$query['member']);
        if($member[count($member)-1]==$uid)
        {
            //            该用户为最后一个审批人,stage=3,审批结果为同意，审批人变为此时的人
            if($flow==1)
            {
                $updateM1 = \Yii::$app->db->createCommand()->update('flows',['stage'=>3,'result'=>1,'auth'=>$uid],['id'=>$mid])->execute();
            }
            //            该用户为最后一个审批人,stage=3,审批结果为同意，
            else
            {
                $updateM1 = \Yii::$app->db->createCommand()->update('flows',['stage'=>3,'result'=>2,'auth'=>$uid],['id'=>$mid])->execute();
            }
        }
        else
        {
            //            该用户为最后一个审批人,stage=3,审批结果为同意，
            if($flow==1)
            {
                $updateM1 = \Yii::$app->db->createCommand()->update('flows',['stage'=>2,'result'=>1,'auth'=>$uid],['id'=>$mid])->execute();
            }
            //            该用户为最后一个审批人,stage=3,审批结果为同意，
            else
            {
                $updateM1 = \Yii::$app->db->createCommand()->update('flows',['stage'=>2,'result'=>2,'auth'=>$uid],['id'=>$mid])->execute();
            }
        }
        $updateM2 = \Yii::$app->db->createCommand()->insert('flow',
            array('id'=>$mid,'userid'=>$uid,'flow'=>$flow,'other'=>$other,'ftime'=>$Ctime,'status'=>1))->execute();
        if($updateM1&&$updateM2)
        {
            return array('data'=>[$updateM1,$updateM2],'msg'=>'审批成功');
        }
        else
        {
            return array('data'=>[$updateM1,$updateM2],'msg'=>'审批失败');
        }
    }
}
