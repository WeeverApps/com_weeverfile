<?php
/*	
*	Weever Apps Login Component for Joomla
*	(c) 2012 Weever Apps Inc. <http://www.weeverapps.com/>
*
*	Author: 	Robert Gerald Porter 	<rob@weeverapps.com>
*				Aaron Song 				<aaron@weeverapps.com>
*	Version: 	0.2
*   License: 	GPL v3.0
*
*   This extension is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   This extension is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details <http://www.gnu.org/licenses/>.
*
*/
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class WeeverLoginController extends JController
{

	public function checkUser()
	{
	
		echo 'Joomla root URI is ' . JURI::root() . "\n";
		die();
		
		$path	= JURI::root().'images/groupphotos/';
		$retval = 'error';
		
		$wp_upload_dir = wp_upload_dir();
	
		if ( isset( $_GET['base64string'] ) and isset( $_REQUEST['base64'] ) ) {
			$retval = 'trying to load base64...';
			$filename = md5(rand(1000,9999)) . time();
		 	$dest_image = $wp_upload_dir['path'] . '/' . $filename;
			$retval .= 'trying to save to ' . $dest_image . '...';
			$fp = fopen( $dest_image, 'w' );
			$enc = chunk_split(preg_replace('~data:image/([^\;.]+);base64,~', '', $_REQUEST['base64']));
			$enc = str_replace(' ','+',$enc); // for iOS native base64 issues
			$result = fwrite( $fp, base64_decode($enc) );
			if ( ! $result ) $retval .= 'unable to fwrite file - len of decoded: ' . strlen(base64_decode($_REQUEST['base64']));
			fclose( $fp );
	
		} else {
			$result = 'trying to get streamed file...';
			$handler = new UploadedFileXhr();
	
			$filename = md5($handler->getName()) . time();
		 	$dest_image = $wp_upload_dir['path'] . '/' . $filename;
			$result .= 'saving to ' . $dest_image;
	 		$result = $handler->save( $dest_image );
		}
	
	}

}


$controller = new WeeverLoginController();
$controller->execute(JRequest::getWord('task'));
$controller->redirect();
