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
	
	/* --------------------------------
	 * Copyright (c) 2013 Joey van Ommen
	 *
	 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to
	 * deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
	 * sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
	 *
	 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	 * THE SOFTWARE.
	 * --------------------------------
	 */
?>