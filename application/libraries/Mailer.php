<?php
defined('BASEPATH') OR exit('No direct script access allowed');  

class Mailer { 
    
    /**
     * 发送邮件
     * @param  string $email         
     * @param  string $temp_password 验证码
     * @return boolean                
     */
    function sendMail($email, $temp_password, $type) {

        include_once("PHPMailer/class.smtp.php");       // 引入php邮件类  
        include_once("PHPMailer/class.phpmailer.php");      // 引入php邮件类  
        $mail= new PHPMailer();  
        $mail->CharSet = "utf-8";                // 编码格式  
        $mail->IsSMTP();  
        $mail->Host       = "smtp.163.com";         // 设置SMTP服务器  
        $mail->Port       = 465;                     // 设置端口  
        $mail->Username   = "ptj_server@163.com";    // 开通SMTP服务的邮箱；任意一个163邮箱均可  
        $mail->Password   = "kelele67";         // 以上邮箱对应的密码(不是登录密码) 
        $mail->SMTPSecure = 'ssl';                 //传输协议  
        $mail->From       = "ptj_server@163.com";       // 发件人Email  
        $mail->FromName   = "ptj_server";             // 发件人昵称  

        if ($type == 'Register') {
            $mail->Subject = '欢迎注册撮虾子';
            $mail->MsgHTML('你好!<br><br> 感谢你注册撮虾子。<br><br> 你的验证码是'.$temp_password.'</b> 。 验证码有效期为5分钟，请在5分钟内完成验证。');
        }
        else if ($type == 'ResetPas') {
            $mail->Subject = '重置你的撮虾子密码';
            $mail->MsgHTML('尊敬的用户,<br><br> 找回你的密码 <br><br> 你的验证码是 <b>'.$temp_password.'</b> 。 验证码有效期为5分钟，请在5分钟内完成验证。');
        }

        $mail->AddReplyTo("ptj_server@163.com");      // 收件人回复的邮箱地址  
        $mail->AddAddress($email);      // 收件人邮箱  
        $mail->IsHTML(true);            // 是否以HTML形式发送  
  
        if(!$mail->Send()) {  
            echo "Mailer Error: " . $mail->ErrorInfo;  
        }  
        else {  
            echo "Message has been sent";  
        } 
    }    
}  
