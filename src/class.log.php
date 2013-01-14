<?php

	/* -------------------------------
	 * Class Name: Log
	 * Desc: A small class to log data to a .log file. Useful for debugging, error logging, access loggin and more.
	 * -------------------------------
	 */

	class Log {
		
		private $logs;
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
		
		public function __construct($logfiles = 'temp.log', $timezone = 0)
		{
			if(is_array($logfiles)){
				$this->logs = $logfiles;
			}else if(is_string($logfiles)){
				$this->logs = array('default' => $logfiles);
			}else{
				return false;
			}
			
			$timezone = (is_string($timezone)) ? $timezone : date_default_timezone_get();
			date_default_timezone_set($timezone);
			
			$this->activateLog();
		}
		
		/**	
		 * __destruct function
		 * --------------------
		 * Deactivate the log
		 */
		
		public function __destruct()
		{
			$this->deactivateLog();
		}
		
		/**	
		 * entry function
		 * -------------
		 * Adds a single line of content to the log file
		 * 
		 * @param String $content 
		 */
		
		public function entry($entry, $meta = array(), $timestamp = true, $log = 'default')
		{
			if(is_bool($meta)){ // Treat as timestamp var
				$timestamp = $meta;
			}
			
			// Clear last entry;
			$this->entry = '';
			
			// Add timestamp
			if($timestamp){
				$this->entry = '[' . date(self::TIMESTAMP_FORMAT, time()) . ']: ';
			}
			
			// Add meta data, if available
			if(is_array($meta) && count($meta)){
				foreach($meta as $m){
					$this->entry .= '[' . $m . ']';
				}
				$this->entry .= ' - ';
			}
			
			// Add content and linebreak
			$this->entry .= $entry . "\n";
			
			if($this->log_status && is_string($log)){
				
				$fp = @fopen($this->logs[$log],'a') or die('Can\'t open log file: ' . $log);
				
				if($fp){
					fwrite($fp, $this->entry);
				}else{
					$this->log_status = self::LOG_FAILED;
				}
				
				fclose($fp);
			}
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
		 * lastEntry function
		 * -------------
		 * Clears the log entirely
		 */
		
		public function lastEntry()
		{
			return $this->entry;
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