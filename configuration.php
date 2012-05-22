<?php
class JConfig {
	var $offline = '0';
	var $editor = 'tinymce';
	var $list_limit = '100';
	var $helpurl = 'http://help.joomla.org';
	var $debug = '0';
	var $debug_lang = '0';
	var $sef = '1';
	var $sef_rewrite = '1';
	var $sef_suffix = '0';
	var $feed_limit = '20';
	var $feed_email = 'author';
	var $secret = 'Hy9wpIdtA85ksjtoSSg4';
	var $gzip = '0';
	var $error_reporting = '-1';
	var $xmlrpc_server = '1';
	var $log_path = '/var/chroot/home/content/79/8047279/mylogs';
	var $tmp_path = '/tmp';
	var $live_site = '';
	var $force_ssl = '0';
	var $offset = '0';
	var $caching = '0';
	var $cachetime = '15';
	var $cache_handler = 'file';
	var $memcache_settings = array();
	var $ftp_enable = '0';
	var $ftp_host = '';
	var $ftp_port = '0';
	var $ftp_user = '';
	var $ftp_pass = '';
	var $ftp_root = '';
	var $dbtype = 'mysql';
	var $host = 'corajoomla15.db.8047279.hostedresource.com';
	var $user = 'corajoomla15';
	var $db = 'corajoomla15';
	var $dbprefix = 'jos_';
	var $mailer = 'mail';
	var $mailfrom = 'fanclub@i-josh.com';
	var $fromname = 'Cora Fox Fan Club';
	var $sendmail = '/usr/sbin/sendmail';
	var $smtpauth = '0';
	var $smtpsecure = 'none';
	var $smtpport = '25';
	var $smtpuser = '';
	var $smtppass = '';
	var $smtphost = 'localhost';
	var $MetaAuthor = '1';
	var $MetaTitle = '1';
	var $lifetime = '15';
	var $session_handler = 'database';
	var $password = 'Irtpws2b';
	var $sitename = 'Cora Fox Fan Club';
	var $MetaDesc = 'Cora Fox Fan Club! A website devoted to Cora Fox, containing news, pictures, and other things about her life.';
	var $MetaKeys = 'cora fox, Cora Fox, Shanin Fox, shanin fox, Josh Wickham, josh wickham';
	var $offline_message = 'Cora&#039;s awesomeness has brought the site down, but we will be back online soon!';

	public function __construct () {
        if (file_exists(dirname(__FILE__) . '/localConfig.php')) {
            include_once(dirname(__FILE__) . '/localConfig.php');
            if (defined('LOCAL_MODE') && LOCAL_MODE) {
                $this->host = DB_HOST;
                $this->user = DB_USER;
                $this->password = DB_PASSWORD;
            }
        }
    }
}
