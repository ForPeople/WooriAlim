<?php
/* Copyright (C) ForPeople <http://forpeople.co.kr> */

/**
 * @class  ggmailingAdminModel
 * @author GG (xeadmin@forppl.com)
 * @brief  ggmailing module admin model class
 **/

class ggmailingAdminModel extends ggmailing {

	function init() {
	}

	function getGgmailingAdminList($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminList', $args);
		return $output;
	}

	function getGgmailingAdminSend($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSend', $args);
		return $output;
	}
	function getGgmailingAdminSmsList($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSmsList', $args);
		return $output;
	}
	function getGgmailingAdminSms($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSms', $args);
		return $output;
	}

	function getGgmailingAdminSmsSend($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSmsSend', $args);
		return $output;
	}
	function getEmailAddrList($args){
		$output = executeQueryArray('ggmailing.getEmailAddrList', $args);
		return $output;
	}
	function getEmailAddrAllowList($args){
		$output = executeQueryArray('ggmailing.getEmailAddrAllowList', $args);
		return $output;
	}
	function getGroupEmailAddrList($args){
		$output = executeQueryArray('ggmailing.getGroupEmailAddrList', $args);
		return $output;
	}
	function getGroupEmailAddrAllowList($args){
		$output = executeQueryArray('ggmailing.getGroupEmailAddrAllowList', $args);
		return $output;
	}
	function getGgmailingAdminEmail($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminEmail', $args);
		return $output;
	}
	function getGgmailingAdminSendEmail($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSendEmail', $args);
		return $output;
	}
	function getGgmailingAdminMemberList($args){
		$output = executeQueryArray('ggmailing.getGgmailingAdminMemberList', $args);
		return $output;
	}
	function getGgmailingAdminGateway($args){
		$output = executeQueryArray('ggmailing.getGateway', $args);
		return $output;
	}
}
?>
