<?php
class WebEx {

	var $webExID = '';
	var $password = '';
	var $siteID = '';
	var $partnerID = '';
	var $siteURL = '';

/*
	var $webExID = 'Wells_tutor2';
	var $password = 'User123';
	var $siteID = '622920';
	var $partnerID = '622be';
	var $siteURL = 'be-wells.webex.com/WBXService/preview/XMLService';
*/
	public function __construct($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}
	}
	

	// --------------------------------------------------------------------

	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	public function initialize($config = array())
	{

		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
		//$this->password = $config['password'];
		//return $this;
	}

	// --------------------------------------------------------------------

	private function transmit($payload) {
		
		// Generate XML Payload
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<serv:message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';




		$xml .= '<header>';
		$xml .= '<securityContext>';
		$xml .= '<webExID>'. $this->webExID .'</webExID>';
		$xml .= '<password>'. $this->password .'</password>';
		$xml .= '<siteID>'. $this->siteID .'</siteID>';
		$xml .= '<partnerID>'. $this->partnerID .'</partnerID>';
		$xml .= '</securityContext>';
		$xml .= '</header>';
		$xml .= '<body>';
		$xml .= '<bodyContent xsi:type="java:com.webex.service.binding.' . $payload['service']  . '">';		
		$xml .= $payload['xml'];
		$xml .= '</bodyContent>';				
		$xml .= '</body>';
		$xml .= '</serv:message>';
/*
$xxml = str_replace("<","&lt;",$xml);
$xxml = str_replace(">","&gt;",$xxml);
echo "<textarea style='width:800px; height:600px'>".$xxml."</textarea>";
*/
		// Separate $siteURL into Host and URI for Headers
        $host = substr($this->siteURL, 0, strpos($this->siteURL, "/"));
        $uri = strstr($this->siteURL, "/");	
		
		// Generate Request Headers
		$content_length = strlen($xml);
		$headers = array(
			"POST $uri HTTP/1.0",
			"Host: $host",
			"User-Agent: PostIt",
			"Content-Type: application/x-www-form-urlencoded",
			"Content-Length: ".$content_length,
			);
			
		// Post the Request
		$ch = curl_init('https://' . $this->siteURL);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);	
		$response = curl_exec($ch);

		/*** cUrl DEBUG ******************************
		print "<pre style='color:red'>\n";
		print_r(curl_getinfo($ch));  // get error info
		echo "\n\ncURL error number:" .curl_errno($ch); // print error info
		echo "\n\ncURL error:" . curl_error($ch); 
		print "</pre>\n";
		**********************************************/

		curl_close($ch); // close curl session




		return $response;
	}
	
	
	//public function user_AuthenticateUser()
	//public function user_CreateUser()
	//public function user_DelUser()
	//public function user_DelSessionTemplates()
	//public function user_GetloginTicket()
	//public function user_GetloginurlUser()
	//public function user_GetlogouturlUser()
	//public function user_GetUser()
	//public function user_LstsummaryUser()
	public function user_LstsummaryUser($startFrom = '1', $maximumNum = '', $listMethod = '', $orderOptions = '', $dateScope = '' ) {
		$xml = '<listControl>';
		if($startFrom) $xml .= '<startFrom>'. $startFrom .'</startFrom>';
		if($maximumNum) $xml .= '<maximumNum>'. $maximumNum .'</maximumNum>';
		if($listMethod) $xml .= '<listMethod>'. $listMethod .'</listMethod>';	
		$xml .= '</listControl>';
		
		if($orderOptions) {
			$xml .= '<order>';
			foreach ($orderOptions as $options) {
				$xml .= '<orderBy>'. $options['By'] .'</orderBy>';
				$xml .= '<orderAD>'. $options['AD'] .'</orderAD>';
			}
			$xml .= '</order>';
		}
		
		if($dateScope) {
			$xml .= '<dataScope>';
			if($dateScope['regDateStart']) $xml .= '<regDateStart>'. $dateScope['regDateStart'] .'</regDateStart>';
			if($dateScope['timeZoneID']) $xml .= '<timeZoneID>'. $dateScope['timeZoneID'] .'</timeZoneID>';
			if($dateScope['regDateEnd']) $xml .= '<regDateEnd>'. $dateScope['regDateEnd'] .'</regDateEnd>';
			$xml .= '</dataScope>';
		}

		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);	
	
		return $this->transmit($payload);
	}
	
	//public function user_SetUser()
	//public function user_UploadPMRIImage()

	
	//public function meeting_CreateMeeting();
	public function meeting_CreateMeeting($confName, $startDate="08/10/2012 10:00:00", $duration=50, $maxUserNumber=2, $email='clairechang@akacia.com.tw', $meetingPassword="Ak123", $enableReminder=true) {
		$xml = '';

		if(is_null($meetingPassword) == false and empty($meetingPassword)==false)
		{
			$xml .= '<accessControl><meetingPassword>'. $meetingPassword .'</meetingPassword></accessControl>';
		}
		else
		{
			$xml .= '<accessControl><meetingPassword/><enforcePassword>FALSE</enforcePassword></accessControl>';
		}

		$xml .= '<metaData>';
		if($confName) $xml .= '<confName>'. $confName .'</confName>';
		//if($meetingType) $xml .= '<meetingType>'. $meetingType .'</meetingType>';
		$xml .= '</metaData>';

		if ($maxUserNumber > 0) {
			$xml .= '<participants>';
			$xml .= '<maxUserNumber>'.$maxUserNumber.'</maxUserNumber>';
			$xml .= '</participants>';
		}
		$xml .= '<enableOptions>';
		$xml .= '<chat>true</chat>';
		$xml .= '<poll>true</poll>';
		$xml .= '<audioVideo>true</audioVideo>';
		$xml .= '<voip>true</voip>';
		$xml .= '<attendeeList>true</attendeeList>';
		$xml .= '<supportPanelists>true</supportPanelists>';
		$xml .= '</enableOptions>';


		if($email) {
		$xml .= '<remind>';
		$xml .= '<enableReminder>TRUE</enableReminder>';
		$xml .= '<sendEmail>TRUE</sendEmail>';
		$xml .= '<emails>';
		$xml .= '<email>'. $email .'</email>';
		$xml .= '</emails>';
		}

	//	if($hoursAhead) $xml .= '<hoursAhead>'. $hoursAhead .'</hoursAhead>';
	//	if($minutesAhead) $xml .= '<minutesAhead>'. $minutesAhead .'</minutesAhead>';
	//	if($mobile) $xml .= '<mobile>'. $mobile .'</mobile>';
	//	if($sendMobile) $xml .= '<sendMobile>'. $sendMobile .'</sendMobile>';
		$xml .= '</remind>';


		$xml .= '<schedule>';
		if($startDate) $xml .= '<startDate>'. $startDate .'</startDate>';
		if($duration) $xml .= '<duration>'. $duration .'</duration>';
		//$xml .= '<joinBeforeHost>true</joinBeforeHost>';
		$xml .= '</schedule>';



		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);					
	}

	//public function meeting_CreateTeleconferenceSession();
	//public function meeting_DelMeeting();
	//public function meeting_GethosturlMeeting();
	//public function meeting_GetMeeting();
	//public function meeting_GetTeleconferenceSession();
	//public function meeting_LstsummaryMeeting();
	//public function meeting_SetMeeting();

	public function meeting_SetMeeting($meetingkey, $confName = '' , $audioVideo=TRUE, $email="CYNTHIA@AKACIA.COM.TW") {
		//$xml = '<sessionKey>'. $sessionKey .'</sessionKey>';

		$xml = "";
		if($confName) {
			$xml .= '<metaData>';
			$xml .= '<confName>'. $confName .'</confName>';
			$xml .= '</metaData>';
		}

		if($enableOptions) $xml = '<enableOptions><audioVideo>'. $audioVideo .'</audioVideo></enableOptions>';

		if($email) {
			$xml .= '<remind>';
			$xml .= '<enableReminder>TRUE</enableReminder>';
			$xml .= '<emails>';
			$xml .= '<email>'. $email .'</email>';
			$xml .= '<email>CLAIRE@AKACIA.COM.TW</email>';
			$xml .= '</emails>';
			$xml .= '</remind>';
		}

		if($meetingkey) $xml .= '<meetingkey>'. $meetingkey .'</meetingkey>';

		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);					
	}

	

	//public function meeting_GetMeeting();
	public function meeting_GetMeeting($meetingkey) {
		$xml = '<meetingKey>'. $meetingkey .'</meetingKey>';

		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);					
	}



	//public function meeting_SetTeleconferenceSession();	

	//public function meeting_GetjoinurlMeeting()
	public function meeting_GetjoinurlMeeting($sessionKey, $attendeeName = '') {
		$xml = '<sessionKey>'. $sessionKey .'</sessionKey>';
		if($attendeeName) $xml = '<attendeeName>'. $attendeeName .'</attendeeName>';

		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);					
	}



	public function meeting_GethosturlMeeting($sessionKey, $attendeeName = '') {
		$xml = '<sessionKey>'. $sessionKey .'</sessionKey>';
		if($attendeeName) $xml = '<attendeeName>'. $attendeeName .'</attendeeName>';

		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);

		return $this->transmit($payload);					
	}
	
	//public function event_CreateEvent();
	//public function event_DelEvent();
	//public function event_GetEvent();
	//public function event_LstRecordedEvent();
	
	//public function event_LstsummaryProgram();
	public function event_LstsummaryProgram($programID = '') {
		if($programID)
			$xml = '<programID>' . $programID . '</programID>';
			
		$payload['xml'] = (!empty($xml)) ? $xml : '';
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);	
	
		return $this->transmit($payload);
	}
	//public function event_SendInvitationEmail();
	//public function event_SetEvent();
	//public function event_UploadEventImage();	
	
	//public function event_LstsummaryEvent()		
	public function event_LstsummaryEvent($startFrom = '1', $maximumNum = '', $listMethod = '', $orderOptions = '', $programID = '', $dateScope = '') {
		
		$xml = '<listControl>';
		if($startFrom) $xml .= '<startFrom>'. $startFrom .'</startFrom>';
		if($maximumNum) $xml .= '<maximumNum>'. $maximumNum .'</maximumNum>';
		if($listMethod) $xml .= '<listMethod>'. $listMethod .'</listMethod>';	
		$xml .= '</listControl>';
		
		if($orderOptions) {
			$xml .= '<order>';
			foreach ($orderOptions as $options) {
				$xml .= '<orderBy>'. $options['By'] .'</orderBy>';
				$xml .= '<orderAD>'. $options['AD'] .'</orderAD>';
			}
			$xml .= '</order>';
		}
		
		if($programID)
			$xml .= '<programID>' . $programID . '</programID>';
		
		if($dateScope) {
			$xml .= '<dateScope>';
			if($dateScope['startDateStart']) $xml .= '<startDateStart>'. $dateScope['startDateStart'] .'</startDateStart>';
			if($dateScope['startDateEnd']) $xml .= '<startDateEnd>'. $dateScope['startDateEnd'] .'</startDateEnd>';
			if($dateScope['endDateStart']) $xml .= '<endDateStart>'. $dateScope['endDateStart'] .'</endDateStart>';
			if($dateScope['endDateEnd']) $xml .= '<endDateEnd>'. $dateScope['endDateEnd'] .'</endDateEnd>';
			$xml .= '</dateScope>';
		}
		
		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);
	}
	
	/*
	 *  Meeting Attendee Services
	 */
	//public function attendee_CreateMeetingAttendee()
	public function attendee_CreateMeetingAttendee($sessionKey, $attendees) {
		$xml = '';


echo '<pre>';
print_r($sessionKey);
echo '</pre>';

		foreach($attendees as $attendee) {
			//$xml .= '<attendees>';
			$xml .= '<person>';
			foreach($attendee['info'] as $attr => $val){
				if(!is_array($val) && !empty($val))
					$xml .= '<'.$attr.'>'.$val.'</'.$attr.'>';				
			
				if(is_array($val)) {
						$xml .= '<'.$attr.'>';					
					foreach($val as $att => $val) {
						if(!empty($val))
							$xml .= '<'.$att.'>'.$val.'</'.$att.'>';						
					}
						$xml .= '</'.$attr.'>';					
				}
			}
			$xml .= '</person>';
		
			foreach($attendee['options'] as $attr => $val){
				if(!empty($val))
					$xml .= '<'.$attr.'>'.$val.'</'.$attr.'>';		
			}
			//$xml .= '</attendees>';
		}
		
		$xml .= '<sessionKey>'.$sessionKey.'</sessionKey>';
		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);			
	}
	
	//public function attendee_LstMeetingAttendee()
	public function attendee_LstMeetingAttendee($sessionKey) {
		$xml = '';
		$xml .= '<sessionKey>' . $sessionKey . '</sessionKey>';
		
		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);				
	}
				
	//public function attendee_RegisterMeetingAttendee()
	public function attendee_RegisterMeetingAttendee($attendees) {
		$xml = '';
		foreach($attendees as $attendee) {
			$xml .= '<attendees>';
			$xml .= '<person>';
			foreach($attendee['info'] as $attr => $val){
				if(!is_array($val) && !empty($val))
					$xml .= '<'.$attr.'>'.$val.'</'.$attr.'>';				
			
				if(is_array($val)) {
						$xml .= '<'.$attr.'>';					
					foreach($val as $att => $val) {
						if(!empty($val))
							$xml .= '<'.$att.'>'.$val.'</'.$att.'>';						
					}
						$xml .= '</'.$attr.'>';					
				}
			}
			$xml .= '</person>';
		
			foreach($attendee['options'] as $attr => $val){
				if(!empty($val))
					$xml .= '<'.$attr.'>'.$val.'</'.$attr.'>';		
			}
			$xml .= '</attendees>';
		}
		
		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);
		
		return $this->transmit($payload);		
	}
	
	//public function history_LsteventattendeeHistory()
	public function history_LstmeetingattendeeHistory($meetingKey = '', $orderOptions = '', $startTimeScope = '', $endTimeScope = '', $confName = '', $confID = '', $listControl = '', $inclAudioOnly = false) {
		$xml = '';	
		
		if($meetingKey) {
			$xml .= '<meetingKey>' . $meetingKey . '</meetingKey>';
			//$xml .= '<sessionKey>' . $meetingKey . '</sessionKey>';
		}

		if($orderOptions) {
			$xml .= '<order>';
			foreach ($orderOptions as $options) {
				$xml .= '<orderBy>'. $options['By'] .'</orderBy>';
				$xml .= '<orderAD>'. $options['AD'] .'</orderAD>';
			}
			$xml .= '</order>';
		}
		
		if($startTimeScope) {
			$xml .= '<startTimeScope>';
			$xml .= '<sessionStartTimeStart>'. $startTimeScope['sessionStartTimeStart'] .'</sessionStartTimeStart>';
			$xml .= '<sessionStartTimeEnd>'. $startTimeScope['sessionStartTimeEnd'] .'</sessionStartTimeEnd>';
			$xml .= '</startTimeScope>';
		}
		
		if($endTimeScope) {
			$xml .= '<endTimeScope>';
			$xml .= '<sessionEndTimeStart>'. $endTimeScope['sessionEndTimeStart'] .'</sessionEndTimeStart>';
			$xml .= '<sessionEndTimeEnd>'. $endTimeScope['sessionEndTimeEnd'] .'</sessionEndTimeEnd>';
			$xml .= '</endTimeScope>';			
		}					

		if($confName)
			$xml .= '<confName>' . $confName . '</confName>';

		if($confID)
			$xml .= '<confID>' . $confID . '</confID>';
			
		if($listControl) {
			$xml .= '<listControl>';
			$xml .= '<serv:startFrom>'. $listControl['startFrom'] .'</serv:startFrom>';
			$xml .= '<serv:maximumNum>'. $listControl['maximumNum'] .'</serv:maximumNum>';			
			$xml .= '<serv:listMethod>'. $listControl['listMethod'] .'</serv:listMethod>';
			$xml .= '</listControl>';				
		}

		if($inclAudioOnly)
			$xml .= '<inclAudioOnly>' . $inclAudioOnly . '</inclAudioOnly>';							

		$payload['xml'] = $xml;
		$payload['service'] =  str_replace("_", ".", __FUNCTION__);		
		return $this->transmit($payload);	
	}
	 
}
?>