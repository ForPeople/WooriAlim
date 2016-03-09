<?php
/* Copyright (C) ForPeople <http://forpeople.co.kr> */

/**
 * @class  ggmailingView
 * @author GG (xeadmin@forppl.com)
 * @brief  ggmailing module view class
 **/

class ggmailingView extends ggmailing {

	function init() {
		$module_srl = Context::get('module_srl');
		if(!$module_srl && $this->module_srl) {
			$module_srl = $this->module_srl;
			Context::set('module_srl', $module_srl);
		}
		$oModuleModel = &getModel('module');
		$config = $oModuleModel->getModuleConfig('ggmailing');
		$this->config = $config;
		Context::set('config', $config);

		if($module_srl) {
			$module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
			$this->module_info = $module_info;
			Context::set('module_info',$module_info);
		}

		$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		if(!is_dir($template_path)||!$this->module_info->skin) {
			$this->module_info->skin = 'default';
			$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		}
		$this->setTemplatePath($template_path);
	}

	function dispGgmailingDonotsend() {
		$args = Context::getRequestVars();
		$obj->ggmailing_nickname = $args->nick_name;
		$obj->ggmailing_email = $args->email;
		//member_srl 구하기
		$oModuleModel = getModel('member');
		$args->member_srl = $oModuleModel->getMemberSrlByNickName($args->nick_name);
		if(!$args->member_srl) $args->member_srl = '0';
		$obj->ggmailing_member_srl = $args->member_srl;
		$args->fix_regdate = date('YmdHis',strtotime($args->regdate));
		$obj->ggmailing_member_regdate = $args->fix_regdate;
		$obj->regdate = date('YmdHis');
		//executeQuery('ggmailing.insertBoardMember',$obj);
		$output = $oModuleModel->getMemberInfoByMemberSrl($args->member_srl);

		if(!$args->nick_name || !$args->fix_regdate || !$args->email) {
			$donotsend->message = '정보가 부족합니다.';
		} else {
			//현재 차단중인지 확인
			$output = executeQueryArray('ggmailing.getDonotsend',$obj);
			//차단중이 아니라면
			if($output->data) {
				$donotsend->message = '이미 수신거부 되어 있습니다.';
			} else {
				executeQuery('ggmailing.insertDonotsend',$obj);
				$donotsend->message = '정상적으로 수신거부 되었습니다.';
			}
		}
		Context::set('args',$args);
		Context::set('donotsend',$donotsend);
		$this->setTemplateFile('donotsend');
	}

	function dispGgmailingRequest() {
		$args = Context::getRequestVars();
		$oModuleModel = &getModel('module');
		$config = $oModuleModel->getModuleConfig('ggmailing');
		$this->config = $config;
		Context::set('config', $config);

		$dmn = getFullUrl('');
		$dmn = parse_url($dmn);
		$domain = substr($dmn['host'] . $dmn['path'], 0, -1);
		$domain = str_replace('www.','',$domain);
		
		$ggmailing_serv_url = $config->ggmailing_serv_url;

		if($config->ggmailing_ssl == 'N' || !$config->ggmailing_ssl)
		{ $ggmailing_ssl = 'http://'; $ggmailing_ssl_port = ''; } 
		elseif($config->ggmailing_ssl == 'Y')
		{ $ggmailing_ssl = 'https://'; $ggmailing_ssl_port = ':' . $config->ggmailing_ssl_port; }

		$url = $ggmailing_ssl . $ggmailing_serv_url . $ggmailing_ssl_port . '/index.php';
		
		$post_data = array(
				'act' => 'dispWwapimanagerRequest',
				'authkey' => $config->ggmailing_authkey,
				'mid' => 'auth_woorimail',
				'domain' => $domain,
				'type' => 'ggmailing',
				'notice_mid' => $args->notice_mid,
				'ggmailing_document_srl' => $args->ggmailing_document_srl,
				'ggmailing_send_srl' => $args->ggmailing_send_srl
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($config->ggmailing_ssl == 'Y')
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		}
		$response = curl_exec($ch);
		//$authcheck = json_decode($response);
		curl_close($ch);

		Context::set('args',$args);
		Context::set('response',$response);
		$this->setTemplateFile('request');
	}
}
?>
