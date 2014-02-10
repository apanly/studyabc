<?php
/**
 * Created by 狂神文帝.
 * User: vincent
 * Date: 2/10/14
 * Time: 8:34 PM
 * Site: tech.yyabc.org
 * Email:apanly@163.com
 * QQ:364054110
 */
require_class("Mail_phpmailer");
require_class("Mail_smtp");
class Mail_mailhelp {
    function sendmail_sunchis_com($mailTo,$subject,$body,$AddAttachment){
        //$mailTo：是一个数组，表示收件人地址 和收件人姓名，格式为array('邮箱地址','姓名')
        //$subject 表示邮件标题
        //$body  ：表示邮件正文
        //$AddAttachment 附件地址
        //error_reporting(E_ALL);
        if(count($mailTo)==0){
           return ;
        }
        $mail             = new PHPMailer();        //new一个PHPMailer对象出来
        $mail->Mailer     = "SMTP";
        $mail->CharSet    = "UTF-8";                //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->IsSMTP();                            // 设定使用SMTP服务
        //$mail->SMTPDebug  = 2;                      // 启用SMTP调试功能
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
        $mail->SMTPSecure = "ssl";                // 安全协议
        $mail->Host       = "smtp.qq.com";         // SMTP 服务器
        $mail->Port       = 465;                     // SMTP服务器的端口号
        $mail->Username   = "364054110@qq.com";             // SMTP服务器用户名,注意！！！！这里需要到这个邮箱中开启SMTP服务才可以的
        $mail->Password   = "GWloveYOU";        // SMTP服务器密码
        $mail->SetFrom('364054110@qq.com', 'Vincentguo');
        #$mail->AddReplyTo("364054110@qq.com","订单管理");
        $mail->Subject    = $subject;
        #$mail->AltBody    = $body; // optional, comment out and test
        #$mail->MsgHTML($body);
        $mail->IsHTML(true);
        $mail->Body=$body;
        foreach($mailTo as $k => $v){
            $mail->AddAddress($mailTo[$k][0], $mailTo[$k][1]);
        }
        if(count($AddAttachment) > 0){
            foreach($AddAttachment as $k => $AttachmentAddress){
                $mail->AddAttachment($AttachmentAddress);
            }
        }
        if(!$mail->Send()) {
           return $mail->ErrorInfo;
        } else {
            return 1;
        }
    }
} 