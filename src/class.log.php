<?php

    use Psr\Log\LoggerInterface;

	/* -------------------------------
	 * Class Name: Log
	 * Desc: A small class to log data to a .log file. Useful for debugging, error logging, access loggin and more.
	 * -------------------------------
	 */

	class Log implements LoggerInterface {
		
		// Private
		private $logs,
		$log;
		
		// Public
		public $active,
		$entry,
		$code,
		$error,
		$status;
		
		// Constant
		const LOG_INACTIVE = 0,
		LOG_ACTIVE = 1,
		LOG_FAILED = 2,
		
		SPACER = 50,
		
		DEBUG = 100,
		INFO = 200,
		NOTICE = 250,
		WARNING = 300,
		ERROR = 400,
		CRITICAL = 500,
		ALERT = 550,
		EMERGENCY = 600,
		
		TIMESTAMP_FORMAT = 'Y-m-d H:i:s';
		
		
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
			$this->active = $this->logs['default'];
			
			if(!flock($this->log, LOCK_UN)){
				$this->error = 'Couldn\'t get lock.';
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
			fflush($this->log);
			fclose($this->log);
			$this->deactivateLog();
		}
		
		/**	
		 * __toString function
		 * --------------------
		 * Returns the active logging file and the current logging status
		 */
		
		public function __toString()
		{
			return $this->active . ' - ' . $this->status;	
		}
		
		/**	
		 * entry function
		 * -------------
		 * Adds a single line of content to the log file
		 * 
		 * @param String $content 
		 */
		
		public function entry($level, $entry, $meta = array(), $timestamp = true)
		{
			if(is_bool($meta)){ // Treat as timestamp var
				$timestamp = $meta;
			}else{
				$meta = $this->getContext($meta);
			}
			
			// Add timestamp (clears the last entry either way)
			$this->entry = ($timestamp) ? '['  . date(self::TIMESTAMP_FORMAT, time()) . ']' : '';
			
			// Add context
			$this->entry .= ($meta) ? $meta : '';
			
			// Add level
			if($level !== self::SPACER){
				$this->entry .= ' ' . $level . ': ';
			}
			
			// Add content and linebreak, if object, toString
			if(is_object($entry) && method_exists($entry, '__toString')){
				$entry = $entry->__toString();
			}
			$this->entry .= $entry . "\n";
			
			if($this->status){
							
				if($this->log){
					fwrite($this->log, $this->entry);
				}else{
					$this->status = self::LOG_FAILED;
					$this->error = 'Writing to the log file, ' . $active . ', failed.';
				}
			}
		}
		
		/**	
		 * getContext function
		 * -------------
		 * Analyses the context given to log entries and returns a suitable string.
		 */
		 
		 private function getContext(array $context = array()){
			 
			 if(count($context)){
				 
				 $str = '';
				 foreach($context as $key => $value){
					 if($value instanceof Exception){
						 // do something
					 }
					 $str .= '[' . $value . ']';
				 }
				 return $str;
				 
			 }else{
				 return false; 
			 }
		 }
		
		/**	
		 * clear function
		 * -------------
		 * Clears the log entirely
		 */
		
		public function clear()
		{
			ftruncate($this->log, 0);
		}
		
		/**	
		 * switchTo function
		 * -------------
		 * Switches to a differnt log file
		 */
		
		public function switchTo($log)
		{
			fflush($this->log);
			fclose($this->log);
			
			if(is_string($log)){
				$this->log = fopen($this->logs[$log],'a');
				
				if(!flock($this->log, LOCK_UN)){
					$this->error = 'Couldn\'t get lock.';
					return false;
				}
				
				$this->active = $this->logs[$log];
			}
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
		
		/**	
		 * RFC 5424 functions
		 * -------------
		 * In compliance with the PS3 standard
		 *
		 * 1. Emergency function
		 */
		
		public function emergency($message, array $context = array())
		{
				$this->entry(self::EMERGENCY, $message, $context, $timestamp = true);
		}
		
		/**
		 * 2. Alert function
		 */
		
    public function alert($message, array $context = array())
		{
				$this->entry(self::ALERT, $message, $context, $timestamp = true);
		}
		
		/**
		 * 3. Critical function
		 */
		
    public function critical($message, array $context = array())
		{
				$this->entry(self::CRITICAL, $message, $context, $timestamp = true);
		}
		
		/**
		 * 4. Error function
		 */
		
    public function error($message, array $context = array())
		{
				$this->entry(self::ERROR, $message, $context);
		}
		
		/**
		 * 5. Warning function
		 */
		
    public function warning($message, array $context = array())
		{
				$this->entry(self::WARNING, $message, $context);
		}
		
		/**
		 * 6. Notice function
		 */
		
    public function notice($message, array $context = array())
		{
				$this->entry(self::NOTICE, $message, $context);
		}
		
		/**
		 * 7. Info function
		 */
		
    public function info($message, array $context = array())
		{
				$this->entry(self::INFO, $message, $context);
		}
		
		/**
		 * 8. Debug function
		 */
		
    public function debug($message, array $context = array())
		{
				$this->entry(self::DEBUG, $message, $context);
		}
		
		/**
		 * Extra. Log function
		 */
		
    public function log($level, $message, array $context = array())
		{
				
				if($level !== self::DEBUG || $level !== self::INFO || $level !== self::NOTICE || $level !== self::WARNING || $level !== self::ERROR || $level !== self::CRITICAL || $level !== self::ALERT || $level !== self::EMERGENCY){
					// Throw exception
				}
				
				$this->entry($level, $message, $context, $timestamp = true);
		}
		
			
	}
	
?>