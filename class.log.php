<?php

	class Log {
		
		private $log = '/var/www/vhosts/www.cssnv.com/httpdocs/twitter.log';
		public $entry = '';
		public $timestamp = '';
		
		public function __construct(){
			date_default_timezone_set('America/New_York');
			$this->timestamp = date('d/m/y - G:i:s', time()) . ': ';
		}
		
		public function line($content){
			$this->entry .= $content . ' ';
		}
		
		public function add(){
			$this->entry = $this->timestamp . $this->entry . "\n";
			$fp = @fopen($this->log,'a') or die("can't open log");
			$response = fwrite($fp, $this->entry);
			
			fclose($fp);
			
			$this->entry = '';
		}
		
		public function singleLine($content){
			$this->entry = $content;
			$this->add();
			
			$this->entry = '';
		}
			
	}
?>