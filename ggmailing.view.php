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
		$config = $oModuleModel->getModuleConfig('ggshop');
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
}
?>
