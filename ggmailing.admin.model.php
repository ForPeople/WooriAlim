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

	function getGgmailingAdminList($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminList', $args);
		return $output;
	}

	function getGgmailingAdminSend($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSend', $args);
		return $output;
	}
	function getGgmailingAdminSmsList($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSmsList', $args);
		return $output;
	}
	function getGgmailingAdminSms($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSms', $args);
		return $output;
	}

	function getGgmailingAdminSmsSend($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSmsSend', $args);
		return $output;
	}
	function getEmailAddrList($args = null){
		$output = executeQueryArray('ggmailing.getEmailAddrList', $args);
		return $output;
	}
	function getEmailAddrAllowList($args = null){
		$output = executeQueryArray('ggmailing.getEmailAddrAllowList', $args);
		return $output;
	}
	function getGroupEmailAddrList($args = null){
		$output = executeQueryArray('ggmailing.getGroupEmailAddrList', $args);
		return $output;
	}
	function getGroupEmailAddrAllowList($args = null){
		$output = executeQueryArray('ggmailing.getGroupEmailAddrAllowList', $args);
		return $output;
	}
	function getGgmailingAdminEmail($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminEmail', $args);
		return $output;
	}
	function getGgmailingAdminSendEmail($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminSendEmail', $args);
		return $output;
	}
	function getGgmailingAdminMemberList($args = null){
		$output = executeQueryArray('ggmailing.getGgmailingAdminMemberList', $args);
		return $output;
	}
	function getGgmailingAdminGateway($args = null){
		$output = executeQueryArray('ggmailing.getGateway', $args);
		return $output;
	}
}
?>
