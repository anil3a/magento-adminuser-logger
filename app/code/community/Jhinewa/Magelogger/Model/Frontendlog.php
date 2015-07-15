<?php 


class Jhinewa_Magelogger_Model_FrontendLog {

	public function hookToFrontendControllerActionPredispatch($observer = null)
	{
		if (!Mage::helper('activecodeline_actionlogger')->_canLogFrontendActions()) {
			return;
		}
	 
		$log = Mage::getModel('activecodeline_actionlogger/frontend');
	 
		$log->setActionName(Mage::app()->getRequest()->getActionName());
		$log->setControllerName(Mage::app()->getRequest()->getControllerName());
	 
		if (Mage::helper('activecodeline_actionlogger')->_canLogRequestParams()) {
			if($params = Mage::app()->getRequest()->getParams()) {
				$log->setParams(Mage::helper('core')->encrypt(serialize($params)));
			}
		}
	 
		$log->setClientIp(Mage::app()->getRequest()->getClientIp());
		$log->setControllerModule(Mage::app()->getRequest()->getControllerModule());
	 
		if ($customer = Mage::getSingleton('customer/session')->getCustomer()) {
			$log->setCustomerId($customer->getId());
		} else {
			$log->setCustomerId(0);
		}
	 
		try {
			$log->save();
		} catch (Exception $e) {
			Mage::log('file: '.__FILE__.', line: '.__LINE__, 'msg: '.$e->getMessage());
		}
	}



}
