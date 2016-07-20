<?php

namespace Test\Controller\Admin;

use Admin\Controller\AdminController as BasicController;


class EpisodeController extends BasicController{

	/**
	 * ORM\Model
	 *
	 * @var
	 */
	public $model = 'Test\Model\Episode';

	/**
	 * Url
	 *
	 * @var
	 */
	public $url = 'episodes';

	/**
	 * View list
	 *
	 * @var array
	 */
	public $view_list = ['id','name'];

	/**
	 * View add
	 *
	 * @var array
	 */
	public $view_add = ['name'];

	/**
	 * View edit
	 *
	 * @var array
	 */
	public $view_edit = ['name'];

	/**
	 * View get
	 *
	 * @var array
	 */
	public $view_get = ['id','name'];

	/**
	 * View search
	 *
	 * @var array
	 */
	public $view_search = ['id','name'];

}

?>