<?php
/**
 * @package        EasySocial
 * @copyright      Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * EasySocial is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.file');

require_once $file;
require_once JPATH_ROOT . '/components/com_content/helpers/route.php';

class plgKunenaEasySocial extends EasySocialPlugins
{
	/**
	 * plgKunenaEasySocial constructor.
	 *
	 * @param $subject
	 * @param $config
	 *
	 * @since Kunena
	 */
	public function __construct(&$subject, $config)
	{
		// Do not load if Kunena version is not supported or Kunena is offline
		if (!(class_exists('KunenaForum') && KunenaForum::isCompatible('3.0') && KunenaForum::installed()))
		{
			return true;
		}

		$file = JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/plugins.php';

		if (!JFile::exists($file))
		{
			if (\Joomla\CMS\Plugin\PluginHelper::isEnabled('kunena', 'community'))
			{
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->update('`#__extensions`');
				$query->where($db->quoteName('element') . ' = ' . $db->quote('community'));
				$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
				$query->where($db->quoteName('folder') . '= ' . $db->quote('kunena'));
				$query->set($db->quoteName('enabled') . '=0');
				$db->setQuery($query);
				$db->execute();
			}

			return;
		}

		parent::__construct($subject, $config);

		$this->loadLanguage('plg_kunena_community.sys', JPATH_ADMINISTRATOR) || $this->loadLanguage('plg_kunena_community.sys', KPATH_ADMIN);
	}

	/**
	 * Get Kunena login integration object.
	 *
	 * @return boolean|KunenaLogin|KunenaLoginEasySocial
	 * @since Kunena
	 */
	public function onKunenaGetLogin()
	{
		if (!$this->params->get('login', 1))
		{
			return;
		}

		require_once __DIR__ . "/login.php";

		return new KunenaLoginEasySocial($this->params);
	}

	/**
	 * Get Kunena avatar integration object.
	 *
	 * @return boolean|KunenaAvatar
	 * @since Kunena
	 */
	public function onKunenaGetAvatar()
	{
		if (!$this->params->get('avatar', 1))
		{
			return;
		}

		require_once __DIR__ . "/avatar.php";

		return new KunenaAvatarEasySocial($this->params);
	}

	/**
	 * Get Kunena profile integration object.
	 *
	 * @return boolean|KunenaProfile
	 * @since Kunena
	 */
	public function onKunenaGetProfile()
	{
		if (!$this->params->get('profile', 1))
		{
			return;
		}

		require_once __DIR__ . "/profile.php";

		return new KunenaProfileEasySocial($this->params);
	}

	/**
	 * Get Kunena private message integration object.
	 *
	 * @return boolean|KunenaPrivate
	 * @since Kunena
	 */
	public function onKunenaGetPrivate()
	{
		if (!$this->params->get('private', 1))
		{
			return;
		}

		require_once __DIR__ . "/private.php";

		return new KunenaPrivateEasySocial($this->params);
	}

	/**
	 * Get Kunena activity stream integration object.
	 *
	 * @return boolean|KunenaActivity
	 * @since Kunena
	 */
	public function onKunenaGetActivity()
	{
		if (!$this->params->get('activity', 1))
		{
			return;
		}

		require_once __DIR__ . "/activity.php";

		return new KunenaActivityEasySocial($this->params);
	}
}
