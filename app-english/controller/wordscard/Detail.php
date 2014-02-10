<?php
/**
 * Created by 狂神文帝.
 * User: vincent
 * Date: 2/9/14
 * Time: 12:47 AM
 * Site: tech.yyabc.org
 * Email:apanly@163.com
 * QQ:364054110
 */
require_class("Controller");
require_class("Mail_mailhelp");
class WordsCard_DetailController extends Controller{
    public function handle_request(){
        exit();
        $mailTo = array();
        $AddAttachment = array();
//收件人邮箱，可以用array_push函数添加多个
        array_push($mailTo, array("364054110@qzone.qq.com","helper"));
//array_push($AddAttachment,"d:\\照毕业证相.txt");    //邮寄的附件也可添加多个
//发送的主题
        $subject = "[IT生活]测试";
        //发送的邮件内容
        $body ="<a href='http://www.yyabc.org'>ceshi </a>";
        $mailhelper=new Mail_mailhelp();
        $res=$mailhelper->sendmail_sunchis_com($mailTo,$subject,$body,$AddAttachment);
        var_dump($res);
        exit();
        return "WordsCard_Detail";
    }
} 