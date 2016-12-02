<?php

class CRM_Civisocial_Page_PrivacyPolicy extends CRM_Core_Page {

  public function run() {
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    $this->assign('websiteRoot', $root);
    parent::run();
  }

}
