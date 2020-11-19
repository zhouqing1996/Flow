<?php
namespace backend\module\flow\controllers;

use yii\db\Query;
use yii\web\Controller;
use backend\module\flow\controllers\FlowController;
use TCPDF;
class DownloadController extends Controller
{
    public function actionIndex()
    {
        return array('data'=>[],'msg'=>'download');
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
//申请表内容
    public function Application($mid)
    {
//        基本 信息
        $info = (new Query())
            ->select('*')
            ->from('flows')
            ->where(['id'=>$mid])
            ->one();
        $info['userid']= $this->getUserName($info['userid'])['username'];
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
    public function actionDownload()
    {
        $request = \Yii::$app->request;
        $mid = $request->post('id');
        $x = $this->Application($mid);

        $Info = $x['data'][0];
        $manageInfo =$x['data'][1];
        $appInfo = $x['data'][2];
//        return array('data'=>[$Info,$manageInfo,$appInfo],'msg'=>'申请表信息');

        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Helloweba');
        $pdf->SetAuthor('zhouqing');
        $pdf->SetTitle($Info['name']);
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, PHP');
        // 设置字体
        $pdf->setFont('stsongstdlight', '', 14);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // 设置页眉和页脚信息
        $pdf->setPrintFooter(true);
        $pdf->setPrintHeader(true);
        // 设置默认等宽字体
        $pdf->SetDefaultMonospacedFont('courier');
        // 设置间距
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // 设置分页
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);
        $pdf->AddPage();
        $pdf->SetFont('stsongstdlight', '', 20);
        $pdf->SetTextColor(238, 121, 66);

        $pdf->Write(0, '基本信息', '', 0, 'C', 1, 0, false, false, 0);
        $pdf->Ln(10);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(159, 182, 205);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=50, $h=5, $txt='申请人', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                    $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=60, $h=5, $txt='申请表名称', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=50, $h=5, $txt='申请时间', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->Ln(5);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=50, $h=20, $txt=$Info['userid'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=60, $h=20, $txt=$Info['name'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=50, $h=20, $txt=$Info['ctime'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->Ln(20);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(159, 182, 205);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=160, $h=5, $txt='审批人列表', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->Ln(5);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $p='';
        for($j=0;$j<count($Info['member']);$j++)
        {
            $p = $p.'-'.$Info['member'][$j];
        }
        $pdf->MultiCell($w=160, $h=20, $txt=$p, $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->Ln(20);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(159, 182, 205);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=50, $h=5, $txt='审批阶段', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=60, $h=5, $txt='当前审批人', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=50, $h=5, $txt='审批意见', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->Ln(5);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=50, $h=20, $txt=$Info['stage'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=60, $h=20, $txt=$Info['auth'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->MultiCell($w=50, $h=20, $txt=$Info['result'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->Ln(35);
        if(count($manageInfo)>0)
        {
            $pdf->SetFont('stsongstdlight', '', 20);
            $pdf->SetTextColor(238, 121, 66);
            $pdf->Write(0, '审批人意见一览', '', 0, 'C', 1, 0, false, false, 0);
            $pdf->Ln(10);
            $pdf->SetFont('stsongstdlight', '', 14);
            $pdf->SetFillColor(159, 182, 205);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell($w=30, $h=5, $txt='审批人', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
            $pdf->MultiCell($w=30, $h=5, $txt='审批意见', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
            $pdf->MultiCell($w=60, $h=5, $txt='审批备注', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
            $pdf->MultiCell($w=40, $h=5, $txt='审批时间', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
            $pdf->Ln(5);
            $pdf->SetFont('stsongstdlight', '', 14);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);
            for($i=0;$i<count($manageInfo);$i++)
            {
                $pdf->MultiCell($w=30, $h=20, $txt=$manageInfo[$i]['userid'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                    $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
                $pdf->MultiCell($w=30, $h=20, $txt=$manageInfo[$i]['flow'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                    $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
                $pdf->MultiCell($w=60, $h=20, $txt=$manageInfo[$i]['other'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                    $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
                $pdf->MultiCell($w=40, $h=20, $txt=$manageInfo[$i]['ftime'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                    $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
                $pdf->Ln(10);
            }
        }
        $pdf->Ln(25);
        $pdf->SetFont('stsongstdlight', '', 20);
        $pdf->SetTextColor(238, 121, 66);
        $pdf->Write(0, '审批内容', '', 0, 'C', 1, 0, false, false, 0);
        $pdf->Ln(10);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(159, 182, 205);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=40, $h=5, $txt='项目', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->SetFillColor(0, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=40, $h=5, $txt='内容', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->SetFillColor(159, 182, 205);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=40, $h=5, $txt='项目', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->SetFillColor(0, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($w=40, $h=5, $txt='内容', $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
            $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
        $pdf->Ln(5);
        $pdf->SetFont('stsongstdlight', '', 14);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        for($i=0;$i<count($appInfo);$i++)
        {
            $pdf->MultiCell($w=40, $h=20, $txt=$appInfo[$i]['fname'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
            $pdf->MultiCell($w=40, $h=20, $txt=$appInfo[$i]['content'], $border=1, $align='C', $fill=true, $ln=0, $x='' ,$y='',$reseth=true,
                $stretch=0,$ishtml=false,$autopadding=true,$maxh=0,$valign='M',$fitcell=true);
            if(($appInfo[$i]['fid'])%2==0)
            {
                $pdf->Ln(20);
            }
        }
        $title = $Info['id'];
        $path = \Yii::$app->basePath;
        $filePath = $path.'/files/';
        if(!is_dir($filePath))
        {
            mkdir(iconv('utf-8','GBK',$filePath),0777,true);
        }
        $path=$filePath.$title.'.pdf';
        ob_clean();
        $pdf->Output($path,'F');
        $url=explode('htdocs',$path);
        $url='http://127.0.0.1'.$url[1];
        return array('data'=>[$url,$Info['name']],'msg'=>'下载表格');
    }
}