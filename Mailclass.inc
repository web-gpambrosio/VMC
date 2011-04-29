<?php
//require_once($_SERVER['DOCUMENT_ROOT']."/phpmailer/class.phpmailer.php");
require_once("/usr/local/www/data/veritas/phpmailer/class.phpmailer.php");
//require_once("/usr/local/www/online/veritas/phpmailer/class.phpmailer.php");

class VMCMailer extends PHPMailer
{
    var $priority = 3;
    var $to_name;
    var $to_email;
    var $From;
    var $FromName;
    var $Sender;
  
    function VMCMailer()
    {
      global $site; 
      
      // Comes from config.php $site array
      
      if($site['smtp_mode'] == "enabled")
      {
        $this->Host = $site['smtp_host'];
        $this->Port = $site['smtp_port'];
        if($site['smtp_username'] != '')
        {
         $this->SMTPAuth  = true;
         $this->Username  = $site['smtp_username'];
         $this->Password  =  $site['smtp_password'];
        }
        $this->Mailer = "smtp";
      }
      if(!$this->From)
      {
        $this->From = $site['from_email'];
      }
      if(!$this->FromName)
      {
        $this-> FromName = $site['from_name'];
      }
      if(!$this->Sender)
      {
        $this->Sender = $site['from_email'];
      }
      $this->Priority = $this->priority;
    }
}
?> 