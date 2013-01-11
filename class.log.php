<?php

	/* -------------------------------
	 * Class Name: Log
	 * Desc: A small class to log data to a .log file. Useful for debugging, error logging, access loggin and more.
	 * -------------------------------
	 */

	class Log {
		
		private $log = 'your/log/url/here.log';
		public $entry = '';
		public $timestamp = '';
		
		/**	
		 * __construct function
		 * --------------------
		 * Initialize the class and set the time zone for proper
		 * timestamps.
		 * 
		 * @param String $timezone 
		 */
		
		public function __construct($timezone = 'America/New_York')
		{
			date_default_timezone_set($timezone);
			$this->timestamp = date('d/m/y - G:i:s', time()) . ': ';
		}
		
		/**	
		 * line function
		 * -------------
		 * Build a sentence by using line(). Every content argument 
		 * gets stitched together and stored till add() is called.
		 * 
		 * @param String $content 
		 * @param String $space
		 */
		
		public function line($content, $space = true)
		{
			$this->entry .= $content;
			$this->entry .= ($space !== true) ? $space : ' ';
		}
		
		/**	
		 * add function
		 * -------------
		 * Adds the $entry to the log file. Should be used after
		 * line() for submitting.
		 */
		
		public function add()
		{
			$this->entry = $this->timestamp . $this->entry . "\n";
			$fp = @fopen($this->log,'a') or die("can't open log");
			$response = fwrite($fp, $this->entry);
			
			fclose($fp);
			
			$this->entry = '';
		}
		
		/**	
		 * singleLine function
		 * -------------
		 * Adds a single line of content to the log file straight
		 * away, automatically calling add()
		 * 
		 * @param String $content 
		 */
		
		public function singleLine($content)
		{
			$this->entry = $content;
			$this->add();
			
			$this->entry = '';
		}
			
	}
	
?>