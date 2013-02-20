PLog
=============

A simple PHP class to log data in a .log file. Great for logging custom scripts.

### About
-------
This PHP class was written as a light weight, easy-to-use way to log activity to a .log file. It's designed to instantly work within any PHP project, multiple log files and complete freedom in the format of the log entries. 

All 8 logging levels defined in
[RFC 5424](http://tools.ietf.org/html/rfc5424) are supported (DEBUG, INFO, NOTICE, WARNING,
ERROR, CRITICAL, ALERT, EMERGENCY) and this class implements the [PSR-3 interface](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md).

A working demo can be found here: http://joeyvo.me/PHP-log-class/

### Usage
-------
Create a log instance for one .log file, or multiple:

    $log = new Log('example.log');
  
    // OR
          
    $multi_logs = new Log(array(
       'default' => 'example.log', 
       'other' => 'other.log'
    ));
  
Add entries to a .log file:

    $log->warning('Basic entry, with timestamp');
        
		$log->warning('Entry with context', array('Meta', 'data', 'here'));   

		$log->log($log::DEBUG, 'Entry with custom level');
    
Clear the .log file completely (use with caution).

    $log->clear();

#### License
-------

Copyright (c) 2013 Joey van Ommen

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
