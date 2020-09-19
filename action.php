<?php
/**
 * Referrers for DokuWiki
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Terence J. Grant<tjgrant@tatewake.com>
 */

if (!defined('DOKU_INC')) {
    die();
}
if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
}
require_once(DOKU_PLUGIN.'action.php');
include_once(DOKU_PLUGIN.'referrers/code.php');

class action_plugin_referrers extends DokuWiki_Action_Plugin
{
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, '_addHeaders');
    }

    public function _addHeaders(&$event, $param)
    {
        global $INFO;

        if (!($this->getConf('re_dont_count_admin') && $INFO['isadmin']) &&
            !($this->getConf('re_dont_count_users') && $_SERVER['REMOTE_USER'])) {
            re_log_referrers_new($this->getConf('re_URL_ignore'));
        }
    }
}
