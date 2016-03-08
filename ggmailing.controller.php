<?php
/* Copyright (C) ForPeople <http://forpeople.co.kr> */

/**
 * @class  ggmailingController
 * @author GG (xeadmin@forppl.com)
 * @brief  ggmailing module admin controller class
 **/

class ggmailingController extends ggmailing 
{
	function init() 
	{
	}

	function curl_request_async($url, $params, $type='POST', $output)  
	{  
	    foreach ($params as $key => &$val)  
	    {  
	        if (is_array($val))
	        {  
	        	$val = implode(',', $val);  
	        }
	        $post_params[] = $key.'='.urlencode($val);  
	    }  
	    $post_string = implode('&', $post_params);  
	  
	    $parts=parse_url($url);  
	  
	    if ($parts['scheme'] == 'http')  
	    {  
	        $fp = fsockopen($parts['host'], isset($parts['port'])?$parts['port']:80, $errno, $errstr, 30);  
	    }  
	    elseif ($parts['scheme'] == 'https')  
	    {  
	        $fp = fsockopen("ssl://" . $parts['host'], isset($parts['port'])?$parts['port']:443, $errno, $errstr, 30);  
	    }  
	  
	    // Data goes in the path for a GET request  
	    if('GET' == $type)
	    {
	    	$parts['path'] .= '?'.$post_string;  
	    }
	  
	    $out = "$type ".$parts['path']." HTTP/1.1\r\n";  
	    $out.= "Host: ".$parts['host']."\r\n";  
	    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";  
	    $out.= "Content-Length: ".strlen($post_string)."\r\n";  
	    $out.= "Connection: Close\r\n\r\n";  
	    // Data goes in the request body for a POST request  
	    if ('POST' == $type && isset($post_string))
	    {
	    	$out.= $post_string;  
	    }
	    fwrite($fp, $out);  
	    if($output == 'json')
	    {
	    	// header 부분 걷어냄
		    while (!feof($fp))
		    {
		        $buffer .= fread($fp,1024);
		    }
			if($buffer)
			{
				$pos = strpos($buffer, "\r\n\r\n");
				$buffer = substr($buffer, $pos + 4);
		    	return $buffer;
		    }
	    }
	    fclose($fp);
	} 

	function triggerWard(&$obj)
	{
		$oModuleModel = getModel('module');
		$config = $oModuleModel->getModuleConfig('ggmailing');
		// 와드 사용여부 체크
		if(!$config->type_ward || $config->type_ward == 'F') return false;

		$num = 2000;
		$obj->is_sendok = 'D';
		$args = new stdClass();
		$args->ggmailing_document_srl = $obj->document_srl;
		$output = executeQueryArray('widgets.ggward.getWardMember',$args);
		if(!$output->toBool()) return $output;

		if($output->data) {
			$obj->sender_nickname = $config->type_ward_nick ? $config->type_ward_nick : $config->ggmailing_serv_url;
			$obj->sender_email = 'NOREPLY@woorimail.com';
			
			// 게시글 제목 구하기, 내용은 태그 포함 그대로 전송
			$oDocumentModel = getModel('document');
			$oDocument = $oDocumentModel->getDocument($obj->document_srl);
			$obj->title = '[댓글알림] '.$oDocument->getTitle();
			
			$obj->foots = '<p><a href="'.getFullUrl('','mid',$obj->mid,'document_srl',$obj->document_srl,'comment_srl',$obj->comment_srl).'" target="_blank">[바로가기]</a></p><br /><br /><p>* 본 메일은 '.getFullUrl('').' 의 {nickname} 님의 요청에 의해서 전송되었습니다. 더이상 알림을 받고 싶지 않으시다면 해당 사이트에 가셔서 취소 버튼을 누르시면 됩니다.</p>';
			$obj->content = $obj->content . $obj->foots;
		}

		foreach($output->data as $key => $val) {
			//수신거부 제외
			$obj->ggmailing_nickname = str_replace(',','.',$val->ggmailing_nickname);
			$obj->ggmailing_email = str_replace(',','.',$val->ggmailing_email);
			$obj->ggmailing_member_regdate = str_replace(',','.',$val->ggmailing_member_regdate);
			$ggoutput = executeQueryArray('ggmailing.getDonotsend',$obj);

			// 자기 자신의 글은 알림 받지 않음
			if(!$ggoutput->data && $obj->nick_name != $val->ggmailing_nickname && $obj->email_address != $val->ggmailing_email) {
				// 받는닉네임 세팅
				$obj->receive_nickname .= str_replace(',','',$val->ggmailing_nickname) . ',';
				// 받는이메일 세팅
				$obj->receive_email .= str_replace(',','',$val->ggmailing_email) . ',';
				// 회원등록일 세팅
				$obj->receive_member_regdate .= str_replace(',','',$val->ggmailing_member_regdate) . ',';

				if((($key+1) % $num == 0) && $num) {
					executeQuery('ggmailing.insertGgmailingAdminSend',$obj);
					$obj->receive_nickname = '';
					$obj->receive_email = '';
					$obj->receive_member_regdate = '';
				}//endif
			} //endif
		}//endforeach

		if($num && $obj->receive_nickname && $obj->receive_email) {
			executeQuery('ggmailing.insertGgmailingAdminSend',$obj);
		}
		$this->procGgmailingSendOK('D');

	}

	function triggerBoardmailing(&$obj) 
	{
		$oModuleModel = getModel('module');
		$config = $oModuleModel->getModuleConfig('ggmailing');
		
		// 게시판 메일링 사용 여부 체크
		if(!$config->type_board_mailing || $config->type_board_mailing == 'F') return false;

		$num = 2000;
		$obj->is_sendok = 'M';
		$args = new stdClass();
		$args->ggmailing_module_srl = $obj->module_srl;

		$output = executeQueryArray('ggmailing.getBoardMemberCount',$args);
		if(!$output->toBool()) return $output;
		
		if($output->data) {
			
			$obj->sender_nickname = $config->type_board_mailing_nick ? $config->type_board_mailing_nick : $config->ggmailing_serv_url;
			$obj->sender_email = 'NOREPLY@woorimail.com';
			
			//게시판명 구하기
			$ggmodule_info = Context::get('module_info');
			$obj->board_title = $ggmodule_info->browser_title;

			//설정한 제목과 내용으로 치환
			$gg_b_title = str_replace('{board_title}',$obj->board_title,strip_tags($config->input_board_mailing_subject));
			$gg_b_title = str_replace('{nick_name}',$obj->nick_name,$gg_b_title);
			$gg_b_content = str_replace('{board_title}',$obj->board_title,$config->input_board_mailing_content);
			$gg_b_content = str_replace('{nick_name}',$obj->nick_name,$gg_b_content);
			$gg_b_content = str_replace('{board_content}',$obj->content,$gg_b_content);
			$obj->title = $config->input_board_mailing_subject?$gg_b_title:'[메일링] '.$obj->title;
			$obj->content = $config->input_board_mailing_content?$gg_b_content:'<p>'.$obj->board_title.' 게시판에 '.$obj->nick_name.' 님의 새 글이 등록되었습니다.</p>';
			
			$obj->foots = '<p><a href="'.getFullUrl('','mid',$obj->mid,'document_srl',$obj->document_srl,'comment_srl',$obj->comment_srl).'" target="_blank">[바로가기]</a></p><br /><br /><p>* 본 메일은 '.getFullUrl('').' 의 {nickname} 님께서 신청하신 메일링에 의해서 전송되었습니다. 더이상 메일링 알림을 받고 싶지 않으시다면 해당 사이트에 가셔서 메일링 취소 버튼을 누르시면 됩니다.</p>';
			$obj->content = $obj->content . $obj->foots;
		}

		foreach($output->data as $key => $val) {
			//수신거부 제외
			$obj->ggmailing_nickname = str_replace(',','.',$val->ggmailing_nickname);
			$obj->ggmailing_email = str_replace(',','.',$val->ggmailing_email);
			$obj->ggmailing_member_regdate = str_replace(',','.',$val->ggmailing_member_regdate);
			$ggoutput = executeQueryArray('ggmailing.getDonotsend',$obj);
			// 자기 자신의 글은 알림 받지 않음
			if(!$ggoutput->data && $obj->nick_name != $val->ggmailing_nickname && $obj->email_address != $val->ggmailing_email) {
				// 받는닉네임 세팅
				$obj->receive_nickname .= str_replace(',','',$val->ggmailing_nickname) . ',';
				// 받는이메일 세팅
				$obj->receive_email .= str_replace(',','',$val->ggmailing_email) . ',';
				// 회원등록일 세팅
				$obj->receive_member_regdate .= str_replace(',','',$val->ggmailing_member_regdate) . ',';

				if((($key+1) % $num == 0) && $num) {
					executeQuery('ggmailing.insertGgmailingAdminSend',$obj);
					$obj->receive_nickname = '';
					$obj->receive_email = '';
					$obj->receive_member_regdate = '';
				}//endif
			} //endif
		}//endforeach
		if($num && $obj->receive_nickname && $obj->receive_email) {
			executeQuery('ggmailing.insertGgmailingAdminSend',$obj);
		}
		$this->procGgmailingSendOK('M');

	} //end function

	function triggerNotiliteGgmailing(&$obj)
	{
		$oModuleModel = getModel('module');
		$config = $oModuleModel->getModuleConfig('ggmailing');
		
		// 게시판 메일링 사용 여부 체크
		if(!$config->type_xe_notilite || $config->type_xe_notilite == 'F') return false;

		$obj->is_sendok = 'N';
		$obj->sender_nickname = $config->type_xe_notilite_nick ? $config->type_xe_notilite_nick : $config->ggmailing_serv_url;
		$obj->sender_email = 'NOREPLY@woorimail.com';
		
		// 알림 내용 조합 ncenterlite 의 ncenterlite.model.php 파일 참조
		switch($obj->type)
		{
			case 'D':
				$type = '글';
			break;
			case 'C':
				$type = '댓글';
			break;
			// 메시지. 쪽지
			case 'E':
				$type = '쪽지';
			break;
			case 'T':
				$type = '테스트';
			break;
		}
		
		$target_member = $obj->target_nick_name;

		switch($obj->target_type)
		{
			case 'C':
				$str = sprintf('<strong>%s</strong>님이 회원님의 %s에 <strong>"%s" 댓글</strong>을 남겼습니다.', $target_member, $type, $obj->target_summary);
			break;
			case 'M':
				$str = sprintf('<strong>%s</strong>님이 <strong>"%s" %s</strong>에서 회원님을 언급하였습니다.', $target_member,  $obj->target_summary, $type);
			break;
			// 메시지. 쪽지
			case 'E':
				if(version_compare(__XE_VERSION__, '1.7.4', '>='))
				{
					$str = sprintf('<strong>%s</strong>님께서 <strong>"%s"</strong> 메세지를 보내셨습니다.',$target_member, $obj->target_summary);
				}
				else
				{
					$str = sprintf('<strong>%d</strong>개의 읽지 않은 <strong>메시지</strong>가 있습니다.', $obj->target_summary);
				}
			break;
			case 'P':
				$str = sprintf('<strong>%s</strong>님이 <strong>"%s"</strong>게시판에 <strong>%s</strong>글을 남겼습니다.', $target_member, $obj->target_browser, $obj->target_summary);
			break;
			case 'S':
				if($obj->target_browser)
				{
					$str = sprintf('<strong>%s</strong>님이 <strong>"%s"</strong>게시판에 <strong>"%s"</strong>글을 남겼습니다.', $target_member, $obj->target_browser, $obj->target_summary);
				}
				else
				{
					$str = sprintf('<strong>%s</strong>님이 <strong>"%s"</strong>글을 남겼습니다.', $target_member, $obj->target_summary);
				}
			break;
			case 'V':
				$str = sprintf('<strong>%s</strong>님이 <strong>"%s"</strong>글을 추천하였습니다.', $target_member, $obj->target_summary);
			break;
			case 'T':
				$str = sprintf('<strong>%s</strong>',$obj->target_summary);
			break;
		}

		$obj->document_srl = getNextSequence();
		$obj->title = '[알림] '.strip_tags(cut_str(str_replace('"','',$str),'97','...'));
		$obj->content = '<p>'.$str.'</p><br /><p><a href="'.$obj->target_url.'" target="_blank">[바로가기]</a></p><br /><br /><p>* 본 메일은 '.getFullUrl('').' 의 알림센터 기능에 의해서 전송되었습니다. 더이상 알림을 받고 싶지 않으시다면 해당 사이트에 가셔서 회원정보 보기 > 내 알림 설정 > 사용안함으로 설정하시면 됩니다.</p>';

		// 받는 사람 정보 구하기
		$oMemberModel = getModel('member');
		$ggMemberInfo = $oMemberModel->getMemberInfoByMemberSrl($obj->member_srl);
		$obj->ggmailing_nickname = str_replace(',','.',$ggMemberInfo->nick_name);
		$obj->ggmailing_email = str_replace(',','.',$ggMemberInfo->email_address);
		$obj->ggmailing_member_regdate = str_replace(',','.',$ggMemberInfo->regdate);

		//수신거부 제외
		$ggoutput = executeQueryArray('ggmailing.getDonotsend',$obj);

		if(!$ggoutput->data) {
			// 받는닉네임 세팅
			$obj->receive_nickname = str_replace(',','',$obj->ggmailing_nickname) . ',';
			// 받는이메일 세팅
			$obj->receive_email = str_replace(',','',$obj->ggmailing_email) . ',';
			// 회원등록일 세팅
			$obj->receive_member_regdate = str_replace(',','',$obj->ggmailing_member_regdate) . ',';
		} //endif
		
		if($obj->receive_nickname != '' && $obj->receive_email != '') {
			$output = executeQuery('ggmailing.insertGgmailingAdminSend',$obj);
		}
		$this->procGgmailingSendOK('N');
	} //end function

	function procGgmailingSendOK($is_sendok)
	{
		$args->is_sendok = $is_sendok;
		$output = executeQueryArray('ggmailing.getGgmailingAdminSendEmail',$args);
		if(!$output->toBool()) {
			return $output;
		}
		$oGgmailingAdminController = getAdminController('ggmailing');

		foreach($output->data as $key => $val) {
			$ggmailing_send_srl = $val->ggmailing_send_srl;
			$is_sendok = $args->is_sendok;
			$oGgmailingAdminController->procGgmailingAdminSend($ggmailing_send_srl,$is_sendok);
		}

	} //end function
	
	function procGgmailingXeSend($obj)
	{
		$oModuleModel = getModel('module');
		$config = $oModuleModel->getModuleConfig('ggmailing');

		// XE 코어 연동 사용 여부 체크
		if(!$config->type_xe_send || $config->type_xe_send == 'F') return false;

		$obj->is_sendok = 'N';
		$args->ggmailing_module_srl = $obj->module_srl;
		
		$dmn = getFullUrl('');
		$dmn = parse_url($dmn);
		$domain = substr($dmn['host'] . $dmn['path'], 0, -1);
		$domain = str_replace('www.','',$domain);

		$ggmailing_serv_url = $config->ggmailing_serv_url;
		if($config->ggmailing_ssl == 'N' || !$config->ggmailing_ssl) { $ggmailing_ssl = 'http://'; $ggmailing_ssl_port = ''; } elseif($config->ggmailing_ssl == 'Y') { $ggmailing_ssl = 'https://'; $ggmailing_ssl_port = ':' . $config->ggmailing_ssl_port; }
		$url = $ggmailing_ssl . $ggmailing_serv_url . $ggmailing_ssl_port . '/index.php';
		$post_data = array(
				'act' => 'dispWwapimanagerRequest',
				'authkey' => $config->ggmailing_authkey,
				'mid' => 'auth_woorimail',
				'domain' => $domain,
				'type' => 'ggmailing'
		);
		// 비동기
		$curl = $this->curl_request_async($url, $post_data, $type='POST', $output='json');
		$authcheck = json_decode($curl);
		
		$obj->sender_nickname = $config->type_xe_send_nick ? $config->type_xe_send_nick : $domain;
		$obj->sender_email = 'NOREPLY@woorimail.com';

		$obj->document_srl = getNextSequence();
		//$obj->title = '';
		//$obj->content = '';

		//수신거부 제외
		$ggoutput = executeQueryArray('ggmailing.getDonotsend',$obj);

		if(!$ggoutput->data) {
			// 받는닉네임 세팅
			$obj->receive_nickname = str_replace(',','',$obj->ggmailing_nickname?$obj->ggmailing_nickname:'NoName') . ',';
			// 받는이메일 세팅
			$obj->receive_email = str_replace(',','',$obj->ggmailing_email) . ',';
			// 회원등록일 세팅
			$obj->receive_member_regdate = str_replace(',','',$obj->ggmailing_member_regdate) . ',';
		} //endif
		
		if($authcheck->ggauth_check == 'OK') {
			$is_Amount = $authcheck->event_point + $authcheck->free_point + $authcheck->etc_point + $authcheck->pay_point;
			if($is_Amount >=1) {
				if($obj->receive_nickname != '' && $obj->receive_email != '') {
					$output = executeQuery('ggmailing.insertGgmailingAdminSend',$obj);
				}
				$this->procGgmailingSendOK('N');
			}
		}
	}
}
?>