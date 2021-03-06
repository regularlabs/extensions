<?php
/**
 * @package         Snippets
 * @version         5.0.4
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_snippets'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once JPATH_LIBRARIES . '/regularlabs/helpers/functions.php';

RLFunctions::loadLanguage('com_snippets');

jimport('joomla.filesystem.file');

// return if Regular Labs Library plugin is not installed
if (!JFile::exists(JPATH_PLUGINS . '/system/regularlabs/regularlabs.php'))
{
	$msg = JText::_('SNP_REGULAR_LABS_LIBRARY_NOT_INSTALLED')
		. ' ' . JText::sprintf('SNP_EXTENSION_CAN_NOT_FUNCTION', JText::_('COM_SNIPPETS'));
	JFactory::getApplication()->enqueueMessage($msg, 'error');

	return;
}

// give notice if Regular Labs Library plugin is not enabled
$regularlabs = JPluginHelper::getPlugin('system', 'regularlabs');
if (!isset($regularlabs->name))
{
	$msg = JText::_('SNP_REGULAR_LABS_LIBRARY_NOT_ENABLED')
		. ' ' . JText::sprintf('SNP_EXTENSION_CAN_NOT_FUNCTION', JText::_('COM_SNIPPETS'));
	JFactory::getApplication()->enqueueMessage($msg, 'notice');
}

// load the Regular Labs Library language file
RLFunctions::loadLanguage('plg_system_regularlabs');

// Dependency
require_once JPATH_LIBRARIES . '/regularlabs/fields/dependency.php';
RLFieldDependency::setMessage('/plugins/editors-xtd/snippets/snippets.php', 'SNP_THE_EDITOR_BUTTON_PLUGIN');
RLFieldDependency::setMessage('/plugins/system/snippets/snippets.php', 'SNP_THE_SYSTEM_PLUGIN');

$controller = JControllerLegacy::getInstance('Snippets');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
