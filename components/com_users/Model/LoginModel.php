<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Users\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\FormModel;
use Joomla\CMS\Uri\Uri;

/**
 * Rest model class for Users.
 *
 * @since  1.6
 */
class LoginModel extends FormModel
{
	/**
	 * Method to get the login form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param   array    $data      An optional array of data for the form to interrogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form	A Form object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_users.login', 'login', array('load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array  The default data is an empty array.
	 *
	 * @since   1.6
	 * @throws  \Exception
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered login form data.
		$app  = Factory::getApplication();
		$data = $app->getUserState('users.login.form.data', array());

		$input = $app->input->getInputForRequestMethod();

		// Check for return URL from the request first
		if ($return = $input->get('return', '', 'BASE64'))
		{
			$data['return'] = base64_decode($return);

			if (!Uri::isInternal($data['return']))
			{
				$data['return'] = '';
			}
		}

		$app->setUserState('users.login.form.data', $data);

		$this->preprocessData('com_users.login', $data);

		return $data;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  \Exception
	 */
	protected function populateState()
	{
		// Get the application object.
		$params = Factory::getApplication()->getParams('com_users');

		// Load the parameters.
		$this->setState('params', $params);
	}

	/**
	 * Override Joomla\CMS\MVC\Model\AdminModel::preprocessForm to ensure the correct plugin group is loaded.
	 *
	 * @param   Form    $form   A Form object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  \Exception if there is an error in the form event.
	 */
	protected function preprocessForm(Form $form, $data, $group = 'user')
	{
		parent::preprocessForm($form, $data, $group);
	}
}
