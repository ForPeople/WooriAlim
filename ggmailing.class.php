<?php
/* Copyright (C) ForPeople <http://forpeople.co.kr> */

/**
 * @class  ggmailingClass
 * @author GG (xeadmin@forppl.com)
 * @brief  ggmailing module class
 **/

class ggmailing extends ModuleObject {

	function moduleInstall() {
		$oModuleController = &getController('module');
		$args->module = 'ggmailing';
		$oModuleController->insertModule($args);
		$oModuleController->insertActionForward('ggmailing', 'view', 'dispGgmailingAdminIndex');
		$oModuleController->insertTrigger('document.insertDocument','ggmailing','controller','triggerInsertGgmailing','after');
		$oModuleController->insertTrigger('comment.insertComment','ggmailing','controller','triggerWard','after');
		$oModuleController->insertTrigger('ncenterlite._insertNotify','ggmailing','controller','triggerNotiliteGgmailing','after');
		return new Object();
	}

	function checkUpdate() {
		$oModuleModel = &getModel('module');
		$act = $oModuleModel->getActionForward('dispGgmailingAdminIndex');
		if(!$act) return true;
		if(!$oModuleModel->getTrigger('document.insertDocument','ggmailing','controller','triggerInsertGgmailing','after')) return true;
		if(!$oModuleModel->getTrigger('comment.insertComment','ggmailing','controller','triggerWard','after')) return true;
		if(!$oModuleModel->getTrigger('ncenterlite._insertNotify','ggmailing','controller','triggerNotiliteGgmailing','after')) return true;
		return false;
	}

	function moduleUpdate() {
		$oModuleModel = &getModel('module');
		$oModuleController = &getController('module');
		if(!$oModuleModel->getTrigger('document.insertDocument','ggmailing','controller','triggerInsertGgmailing','after')) {
			$oModuleController->insertTrigger('document.insertDocument','ggmailing','controller','triggerInsertGgmailing','after');
		}
		if(!$oModuleModel->getTrigger('comment.insertComment','ggmailing','controller','triggerWard','after')) {
			$oModuleController->insertTrigger('comment.insertComment','ggmailing','controller','triggerWard','after');
		}
		if(!$oModuleModel->getTrigger('ncenterlite._insertNotify','ggmailing','controller','triggerNotiliteGgmailing','after')) {
			$oModuleController->insertTrigger('ncenterlite._insertNotify','ggmailing','controller','triggerNotiliteGgmailing','after');
		}
		$this->moduleInstall();
		return new Object(0, 'success_updated');
	}
	
	function moduleUninstall() {
		$oModuleModel = &getModel('module');
		$oModuleController = &getController('module');

		if($oModuleModel->getTrigger('document.insertDocument','ggmailing','controller','triggerInsertGgmailing','after')) {
			$oModuleController->deleteTrigger('document.insertDocument','ggmailing','controller','triggerInsertGgmailing','after');
		}
		if($oModuleModel->getTrigger('comment.insertComment','ggmailing','controller','triggerWard','after')) {
			$oModuleController->deleteTrigger('comment.insertComment','ggmailing','controller','triggerWard','after');
		}
		if($oModuleModel->getTrigger('ncenterlite._insertNotify','ggmailing','controller','triggerNotiliteGgmailing','after')) {
			$oModuleController->deleteTrigger('ncenterlite._insertNotify','ggmailing','controller','triggerNotiliteGgmailing','after');
		}
		return new Object();
	}

	function recompileCache() {
	}
}
?>