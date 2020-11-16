<?php

namespace backend\module\home\controllers;

use yii\db\Exception;
use yii\web\Controller;
use yii\common\models\User;
use yii\web\Response;
use yii\web\Request;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\behaviors\TimestampBehavior;
header("Access-Control-Allow-Origin: *");
file_get_contents("php://input");


/**
 * Default controller for the `home` module
 */
class IndexController extends Controller
{
//    基本数据
    public $url = 'http://localhost:8080/#/';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
//        return "Home";
//        测试发邮件

        $mail= \Yii::$app->mailer->compose();
        $mail->setTo('2466815693@qq.com');
        $mail->setSubject("邮件测试");
        $mail->setHtmlBody("<br>问我我我我我");    //发布可以带html标签的文本
        if($mail->send())
            echo "success";
        else
            echo "failse";
    }
    /*
    *提前处理：密码加密，检查username重复，token加工
     */
    /*
    *加密
     */
    public function PasswordEncry($password,$encryptedData="zhouqing")
    {
        $en = \Yii::$app->getSecurity()->encryptByPassword($password,$encryptedData);
        return base64_encode($en);
    }
    /*
     * 解密
     */
    public function PasswordDecry($password,$encryptedData="zhouqing")
    {
        return \Yii::$app->getSecurity()->decryptByPassword(base64_decode($password),$encryptedData);
    }
    public function actionResetpass()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $newpass = $this->PasswordEncry('zhou@123');
        $updateU = \Yii::$app->db->createCommand()->update('user', ['password' => $newpass], ['id'=>$id])->execute();
        if($updateU)
        {
            return array('data'=>$updateU,'msg'=>'重置密码成功！');
        }
    }

    /*
    *查找用户名
     */
    public function UsernameQuery($name)
    {
    	$query = (new Query())
    		->select('*')
    		->from('user')
    		->Where(['username'=> $name])
    		->andWhere(['status'=> 1])
    		->one();
    	if($query==null)
    	{
    		return array("data"=>[],"msg"=>"No");
    	}
    	else
    	{
    		return array("data"=>$query,"msg"=>"Yes");
    	}

    }
    /*
    * 注册
     */
    public function actionRegister()
    {

    	$request = \Yii::$app->request;
    	$username = $request->post('username');
    	$password = $request->post('password');
    	$no = $request->post('no');
		$query = (new Query())
    		->select('*')
    		->from('user')
    		->Where(['no'=> $no])
    		->one();
		if($query){
			return array("data"=>[],"msg"=>"该用户名已存在");
		}
    	else{
    		$passwordE = $this->PasswordEncry($password);
    		$role =2;
//    		默认注册用户为2，普通用户
    		$status = 1;
    		$queryid  = (new Query())
    				->select('*')
    				->from('user')
                    ->max('id');
    		$id = $queryid +1;
    		$insertU = \Yii::$app->db->createCommand()->insert('user',
                array('id'=>$id,'username'=>$username,'password'=>$passwordE,'role'=>$role,'status'=>$status,'no'=>$no))->execute();
    		if($insertU)
    		{
    			return array("data"=>[$username,$passwordE],"msg"=>"注册成功");
    		}
    		else
    		{
    			return array("data"=>[$username,$passwordE],"msg"=>"注册失败");
    		}

    	}
    }
    /*
    * 忘记密码
     */
    public function actionForget()
    {
    	$request = \Yii::$app->request;
    	$username = $request->post('username');
    	$email = $request->post('email');
        $query = (new Query())
            ->select('*')
            ->from('user')
            ->Where(['no'=> $username])
            ->andWhere(['status'=> 1])
            ->one();
//        return array("data"=>[$query,$email],'msg'=>'ssss');
    	if($query!=null)
    	{
            $updateU = \Yii::$app->db->createCommand()->update('user', ['email'=>$email], ['id'=>$query['id']])->execute();
//            return array("data"=>$updateU,"msg"=>"sss！");
            if($updateU)
            {
//                添加邮箱至数据库中，发送消息到邮箱中
                $mail= \Yii::$app->mailer->compose();
                $mail->setTo($email);
                $mail->setSubject("（文件流转系统）找回密码");
                $mail->setHtmlBody("<p><br>工号为：{$username}的用户，您好！</p>
                    <p>我们收到您的（文件流转系统）找回密码请求，请访问下面地址进行重置密码</p>
                    <a href='{$this->url}newpass'>重置密码</a><p>若非本人操作，请忽略此消息，请勿回复！</p>");    //发布可以带html标签的文本
                if($mail->send())
                {
                    return array("data"=>$mail->send(),'msg'=>'系统已发送消息至您的邮箱，请查看！');
                }
                else
                {
                    return array("data"=>$mail->send(),'msg'=>'系统发生未知错误，发送邮件失败！');
                }
            }
            else
            {
                return array("data"=>$updateU,"msg"=>"更新失败！");
            }
    	}
    	else
    	{
    		return array("data"=>$query,"msg"=>"该用户名尚未注册！不能找回密码");
    	}

    }
//    重置密码
    public function actionNewpass()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');
        $query = (new Query())
            ->select('*')
            ->from('user')
            ->Where(['no'=> $username])
            ->andWhere(['status'=> 1])
            ->one();

        $passE = $this->PasswordEncry($password);
        $updateU = \Yii::$app->db->createCommand()->update('user', ['password'=>$passE], ['id'=>$query['id']])->execute();
        if($updateU)
        {
            return array("data"=>[$username],"msg"=>"修改密码成功");
        }
        else
        {
            return array("data"=>[$username],"msg"=>"修改密码不成功");
        }

    }

    //生成token
    public function generateAccessToken()
    {
//        $this->token = \Yii::$app->security->generateRandomString();
        return \Yii::$app->security->generateRandomString();
    }
    /*
    * 登录
     */
    public function actionLogin()
    {
    	$request = \Yii::$app->request;
    	$username = $request->post('username');
    	$password = $request->post('password');
        $query = (new Query())
            ->select('*')
            ->from('user')
            ->Where(['no'=> $username])
            ->andWhere(['status'=> 1])
            ->one();
    	if($query != null)
    	{
    	    $sqlPassword = $this->PasswordDecry($query['password']);
    	    if($sqlPassword===$password)
            {
                return array("data"=>$query,"msg"=>"登录成功");
            }
            else
            {
                return array("data" =>[$username,$password],"msg"=>"密码错误，登录失败");
            }
    	}
    	else
    	{
    		return array("data"=>[$username,$password],"msg"=>"该用户不存在");
    	}
    }
    /*
    * 注销
     */
    public function actionLogout()
    {
        //改变token
        $request = \Yii::$app->request;
        $userid = $request->post('userid');
        $query = (new Query())
            ->select('*')
            ->from('user')
            ->Where(['id'=> $userid])
            ->andWhere(['status'=> 1])
            ->one();
        if($query)
        {
            $token ="";
            $updateU =\Yii::$app->db->createCommand()->update('user', ['token' => $token], "id={$userid}")->execute();
            if($updateU)
            {
                return array("data"=>[$userid],"msg"=>"退出成功");
            }
            else{
                return array("data"=>[$userid],"msg"=>"已退出，退出时，token失败");
            }
        }
        else{
            return array("data"=>[$userid],"msg"=>"退出时，没有找到相应的用户");
        }

    }
}
