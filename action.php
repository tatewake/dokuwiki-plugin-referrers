<?php 
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');
include_once(DOKU_PLUGIN.'referrers/code.php');

class action_plugin_referrers extends DokuWiki_Action_Plugin {

	/**
	 * return some info
	 */
	function getInfo(){
		return array(
			'author' => 'Terence J. Grant',
			'email'  => 'tjgrant@tatewake.com',
			'date'   => '2009-05-26',
			'name'   => 'Referrers Plugin',
			'desc'   => 'Enable referrer logging for your site on the page ":wiki:referrers".',
			'url'    => 'http://tjgrant.com/wiki/software:dokuwiki:plugin:referrers',
		);
	}
	
	/**
	 * Register its handlers with the DokuWiki's event controller
	 */
	function register(Doku_Event_Handler $controller) {
	    $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE',  $this, '_addHeaders');
	}

	function _addHeaders (&$event, $param) {
		global $INFO;

		if($this->getConf('re_dont_count_admin') && $INFO['isadmin']) return;
		if($this->getConf('re_dont_count_users') && $_SERVER['REMOTE_USER']) return;

		re_log_referrers_new($this->getConf('re_URL_ignore'));
	}
}
?>