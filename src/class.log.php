<?php

	/* -------------------------------
	 * Class Name: Log
	 * Desc: A small class to log data to a .log file. Useful for debugging, error logging, access loggin and more.
	 * -------------------------------
	 */

	class Log {
		
		private $logs;
		private $log;
		
		public $active;
		public $entry;
		public $error;
		public $status;
		
		const LOG_INACTIVE = 0;
		const LOG_ACTIVE = 1;
		const LOG_FAILED = 2;
		
		const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';
		
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
			
			$this->log = fopen($this->logs['default'],'a');
			
			if(!flock($this->log, LOCK_UN)){
				$this->error = 'Couldn\'t get lock.';
				return false;
			}else{
				$this->error = 'Locked';
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
			fclose($this->log);
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
			
			if($this->status && is_string($log)){
							
				if($this->log){
					fwrite($this->log, $this->entry);
				}else{
					$this->status = self::LOG_FAILED;
					$this->error = 'Writing to the log file, ' . $active . ', failed.';
				}
			}
		}
		
		/**	
		 * clearLog function
		 * -------------
		 * Clears the log entirely
		 */
		
		public function clear($log = 'default')
		{
			ftruncate($this->log, 0);
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
			$this->status = self::LOG_INACTIVE;
		}
		
		/**	
		 * activateLog function
		 * -------------
		 * activates logging
		 */
		
		public function activateLog()
		{
			$this->status = self::LOG_ACTIVE;
		}
			
	}
	
?>