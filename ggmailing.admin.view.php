<?php
/* Copyright (C) ForPeople <https://forpeople.co.kr> */

/**
 * @class  ggmailingAdminView
 * @author GG (xeadmin@forppl.com)
 * @brief  ggmailing module admin view class
 **/

class ggmailingAdminView extends ggmailing 
{
	function init() 
	{
		$module_srl = Context::get('module_srl');
		if(!$module_srl && $this->module_srl) {
			$module_srl = $this->module_srl;
			Context::set('module_srl', $module_srl);
		}

		$oModuleModel = getModel('module');

		if($module_srl) {
			$module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
			$this->module_info = $module_info;
			Context::set('module_info',$module_info);
		}

		$groupOutput = executeQueryArray('ggmailing.getGroups');
		if(!$groupOutput->data) $groupOutput->data = array();
		Context::set('group_list', $groupOutput->data);

		$config = $oModuleModel->getModuleConfig('ggmailing');
		$this->config = $config;
		Context::set('config', $config);
	
		$template_path = sprintf("%stpl/",$this->module_path);
		$this->setTemplatePath($template_path);
	}

	function dispGgmailingAdminDonotsend()
	{
		$args = Context::getRequestVars();
		if(!$args->page) $args->page = '1';
		Context::set('args',$args);
		
		if($args->search_type=='ggmailing_email') {
			$args->ggmailing_email = $args->search_keyword;
		} elseif($args->search_type=='ggmailing_nickname') {
			$args->ggmailing_nickname = $args->search_keyword;
		} elseif($args->search_type=='ggmailing_member_srl') {
			$args->ggmailing_member_srl = $args->search_keyword;
		}

		$output = executeQueryArray('ggmailing.getDonotsendList',$args);
		if(!$output->toBool()) return $output;

		Context::set('ds_info',$output->data);
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		$this->setTemplateFile('donotsend_list');
	}

	function dispGgmailingAdminBoardMailing() 
	{
		$args = Context::getRequestVars();
		if(!$args->page) $args->page = '1';

		Context::set('args',$args);
		
		if($args->search_type=='ggemail') {
			$args->ggmailing_email = $args->search_keyword;
		} elseif($args->search_type=='ggmid') {
			$args->ggmailing_mid = $args->search_keyword;
		}

		$output = executeQueryArray('ggmailing.getBoardMemberList',$args);
		if(!$output->toBool()) return $output;
		
		Context::set('bm_info',$output->data);
		
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		$this->setTemplateFile('boardmailing');
	}

	function dispGgmailingAdminIndex() 
	{
		$this->setTemplateFile('index');
	}

	function dispGgmailingAdminNotice() 
	{
		$this->setTemplateFile('notice');
	}

	function dispGgmailingAdminList() 
	{
		$args = Context::getRequestVars();
		Context::set('args',$args);
		$oGgmailingAdminModel = &getAdminModel('ggmailing');
		$output = $oGgmailingAdminModel->getGgmailingAdminList($args);

		Context::set('mail_info', $output->data);

		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		$this->setTemplateFile('list');
	}

	function dispGgmailingAdminSend() 
	{
		$args = Context::getRequestVars();
		Context::set('args',$args);
		$oGgmailingAdminModel = &getAdminModel('ggmailing');
		$output = $oGgmailingAdminModel->getGgmailingAdminSend($args);

		Context::set('mail_info', $output->data);

		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		$this->setTemplateFile('send');
	}

	function dispGgmailingAdminConfig() 
	{
		$args = Context::getRequestVars();
		Context::set('args',$args);

		$this->setTemplateFile('config');
	}

	function dispGgmailingAdminInsertmembers() 
	{
		$args = Context::getRequestVars();

		Context::set('args',$args);
		$oMemberGroup = &getModel('member');

		$groups = $oMemberGroup->getGroups();
		Context::set('groups',$groups);
		
		if(!$args->ggmailing_group) $args->ggmailing_group = $args->cms_db_group;

		if($args->search_type=='nickname') {
			$args->ggmailing_nickname = $args->search_keyword;
		} elseif($args->search_type=='email') {
			$args->ggmailing_email = $args->search_keyword;
		}

		$oGgmailingAdminModel = &getAdminModel('ggmailing');
		$output = $oGgmailingAdminModel->getGgmailingAdminMemberList($args);
		Context::set('mail_info',$output->data);

		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		$this->setTemplateFile('insertmembers');
	}

	function dispGgmailingAdminInsert() 
	{
		$is_update = Context::get('ggmailing_document_srl');
		$args = new stdClass();
		if($is_update) {
			$args->ggmailing_document_srl = $is_update;
			$oGgmailingAdminModel = getAdminModel('ggmailing');
			$output = $oGgmailingAdminModel->getGgmailingAdminEmail($args);
			foreach($output->data as $val) {
				Context::set('gg_args', $val);
			}
			$output = $oGgmailingAdminModel->getGgmailingAdminGateway($args);
			foreach($output->data as $val) {
				Context::set('gw_args', $val);
			}
		}

		$this->setTemplateFile('mailing');
	}

	function dispGgmailingAdminPreview() 
	{
		$args = Context::getRequestVars();
		$oGgmailingAdminModel = &getAdminModel('ggmailing');
		$output = $oGgmailingAdminModel->getGgmailingAdminEmail($args);
		Context::set('mail_info', $output->data);

		$this->setTemplateFile('preview');
	}

	function dispGgmailingAdminSmsInsert() 
	{
		$is_update = Context::get('ggmailing_sms_document_srl');
		if($is_update) {
			$args->ggmailing_sms_document_srl = $is_update;
			$oGgmailingAdminModel = getAdminModel('ggmailing');
			$output = $oGgmailingAdminModel->getGgmailingAdminSms($args);
			foreach($output->data as $key => $val) {
				Context::set('args', $val);
			}
		}

		$this->setTemplateFile('sms');
	}

	function dispGgmailingAdminSmsList() 
	{
		$args = Context::getRequestVars();
		Context::set('args',$args);
		$oGgmailingAdminModel = &getAdminModel('ggmailing');
		$output = $oGgmailingAdminModel->getGgmailingAdminSmsList($args);

		Context::set('sms_info', $output->data);

		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		$this->setTemplateFile('list_sms');
	}

	function dispGgmailingAdminSmsSend() 
	{
		$args = Context::getRequestVars();

		Context::set('args',$args);
		$oGgmailingAdminModel = getAdminModel('ggmailing');
		$output = $oGgmailingAdminModel->getGgmailingAdminSmsSend($args);

		Context::set('sms_info', $output->data);

		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		$this->setTemplateFile('send_sms');
	}
}
?>
