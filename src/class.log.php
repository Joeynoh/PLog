<?php

	/* -------------------------------
	 * Class Name: Log
	 * Desc: A small class to log data to a .log file. Useful for debugging, error logging, access loggin and more.
	 * -------------------------------
	 */

	class Log {
		
		private $logs = array(
			'default' => 'example.log'
			
			// Add more log files here
			
		);
		
		public $entry;
		public $log_status;
		
		const LOG_INACTIVE = 0;
		const LOG_ACTIVE = 1;
		const LOG_FAILED = 2;
		
		const TIMESTAMP_FORMAT = 'd/m/y - G:i:s';
		
		/**	
		 * __construct function
		 * --------------------
		 * Initialize the class and set the time zone for proper
		 * timestamps.
		 * 
		 * @param String $timezone 
		 */
		
		public function __construct($timezone = 0)
		{
			$timezone = (is_string($timezone)) ? $timezone : date_default_timezone_get();
			date_default_timezone_set($timezone);
			
			$this->log_status = self::LOG_ACTIVE;
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
			$this->entry .= ($space === true) ? ' ' : '';
		}
		
		/**	
		 * add function
		 * -------------
		 * Adds the $entry to the log file. Should be used after
		 * line() for submitting.
		 */
		
		public function add($log = 'default')
		{
			if($this->log_status){
				$this->entry = '[' . date(self::TIMESTAMP_FORMAT, time()) . ']: ' . $this->entry . "\n";
				
				$fp = @fopen($this->logs[$log],'a') or die('Can\'t open log file: ' . $log);
				
				if($fp){
					fwrite($fp, $this->entry);
				}else{
					$this->log_status = self::LOG_FAILED;
				}
				
				fclose($fp);
				
				$this->entry = '';
			}
		}
		
		/**	
		 * entry function
		 * -------------
		 * Adds a single line of content to the log file straight
		 * away, automatically calling add()
		 * 
		 * @param String $content 
		 */
		
		public function entry($entry, $id = '', $label = '', $log = 'default')
		{
			$this->entry = '';
			
			if($id != '' || $label != ''){
				if($id != ''){
					$this->entry .= '[' . $id . ']';
				}
				if($label != ''){
					$this->entry .= '[' . $label . ']';
				}
				$this->entry .= ' - ';
			}
			
			$this->entry .= $entry;
			$this->add($log);
			
			$this->entry = '';
		}
		
		/**	
		 * clearLog function
		 * -------------
		 * Clears the log entirely
		 */
		
		public function clearLog($log = 'default')
		{
			$fp = fopen($this->logs[$log],'w') or die("can't open log");
		}
		
		/**	
		 * deactivateLog function
		 * -------------
		 * deactivates logging
		 */
		
		public function deactivateLog()
		{
			$this->log_status = self::LOG_INACTIVE;
		}
		
		/**	
		 * activateLog function
		 * -------------
		 * activates logging
		 */
		
		public function activateLog()
		{
			$this->log_status = self::LOG_ACTIVE;
		}
			
	}
	
?>