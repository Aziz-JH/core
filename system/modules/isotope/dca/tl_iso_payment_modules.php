<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Winans Creative 2009, Intelligent Spark 2010, iserv.ch GmbH 2010
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Table tl_iso_payment_modules
 */
$GLOBALS['TL_DCA']['tl_iso_payment_modules'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_payment_options'),
		'enableVersioning'            => true,
		'closed'					  => true,
		'onload_callback'			  => array
		(
			array('tl_iso_payment_modules', 'checkPermission'),
			array('IsotopeBackend', 'initializeSetupModule'),
			array('tl_iso_payment_modules', 'loadShippingModules'),
		),
		'ondelete_callback'			  => array
		(
			array('tl_iso_payment_modules', 'archiveRecord'),
		),
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name', 'type'),
			'format'                  => '%s <span style="color:#b3b3b3; padding-left:3px;">[%s]</span>',
			'label_callback'		  => array('IsotopeBackend', 'addPublishIcon'),

		),
		'global_operations' => array
		(
			'back' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['MSC']['backBT'],
				'href'					=> 'mod=&table=',
				'class'					=> 'header_back',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"',
			),
			'new' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['new'],
				'href'					=> 'act=create',
				'class'					=> 'header_new',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"',
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'buttons' => array
			(
				'button_callback'     => array('tl_iso_payment_modules', 'moduleOperations'),
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'			=> array('type', 'protected'),
		'default'				=> '{type_legend},type,name',
		'cash'					=> '{type_legend},type,name,label;{note_legend:hide},note;{config_legend},new_order_status,minimum_total,maximum_total,countries,shipping_modules,product_types;{price_legend:hide},price,tax_class;{expert_legend:hide},guests,protected;{enabled_legend},enabled',
		'paypal'				=> '{type_legend},type,name,label;{note_legend:hide},note;{config_legend},new_order_status,postsale_mail,minimum_total,maximum_total,countries,shipping_modules,product_types;{gateway_legend},paypal_account;{price_legend:hide},price,tax_class;{template_legend},button;{expert_legend:hide},guests,protected;{enabled_legend},debug,enabled',
		'paypalpayflowpro'		=> '{type_legend},type,name,label;{note_legend:hide},note;{config_legend},new_order_status,allowed_cc_types,requireCCV,minimum_total,maximum_total,countries,shipping_modules,product_types;{gateway_legend},payflowpro_user,payflowpro_vendor,payflowpro_partner,payflowpro_password,payflowpro_transType;{price_legend:hide},price,tax_class;{template_legend},button;{expert_legend:hide},guests,protected;{enabled_legend},debug,enabled',
		'postfinance'			=> '{type_legend},type,name,label;{note_legend:hide},note;{config_legend},new_order_status,postsale_mail,minimum_total,maximum_total,countries,shipping_modules,product_types;{gateway_legend},postfinance_pspid,postfinance_secret,postfinance_method;{price_legend:hide},price,tax_class;{expert_legend:hide},guests,protected;{enabled_legend},debug,enabled',
		'authorizedotnet'		=> '{type_legend},type,name,label;{note_legend:hide},note;{config_legend},new_order_status,allowed_cc_types,requireCCV,minimum_total,maximum_total,countries,shipping_modules,product_types;{gateway_legend},authorize_login,authorize_trans_key,authorize_trans_type,authorize_delimiter;{price_legend:hide},price,tax_class;{expert_legend:hide},guests,protected;{enabled_legend},debug,enabled',
		'epay_window'			=> '{type_legend},type,name,label;{note_legend:hide},note;{config_legend},new_order_status,trans_type,minimum_total,maximum_total,countries,shipping_modules,product_types;{gateway_legend},epay_merchantnumber,epay_secretkey;{price_legend:hide},price,tax_class;{expert_legend:hide},guests,protected;{enabled_legend},enabled',
		'cybersource'			=> '{type_legend},type,name,label;{note_legend:hide},note;{config_legend},new_order_status,minimum_total,maximum_total,countries,shipping_modules,product_types;{gateway_legend},cybersource_merchant_id,cybersource_trans_key,cybersource_trans_type;{price_legend:hide},price,tax_class;{expert_legend:hide},guests,protected;{enabled_legend},debug,enabled'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'protected'						=> 'groups',
	),

	// Fields
	'fields' => array
	(
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['type'],
			'default'                 => 'cc',
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'default'				  => 'cash',
			'options_callback'        => array('tl_iso_payment_modules', 'getModules'),
			'reference'               => &$GLOBALS['TL_LANG']['PAY'],
			'eval'                    => array('helpwizard'=>true, 'submitOnChange'=>true)
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50')
		),
		'label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['label'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
		),
		'note' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['note'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE'),
		),
		'new_order_status' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['new_order_status'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'                 => 'pending',
			'options_callback'        => array('tl_iso_payment_modules', 'getOrderStatus'),
			'reference'               => &$GLOBALS['TL_LANG']['ORDER'],
			'eval'                    => array('includeBlankOption'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
		),
		'price' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['price'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>16, 'rgxp'=>'surcharge', 'tl_class'=>'w50'),
		),
		'tax_class' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['tax_class'],
			'filter'				=> true,
			'inputType'				=> 'select',
			'foreignKey'			=> 'tl_iso_tax_class.name',
			'attributes'			=> array('legend'=>'tax_legend'),
			'eval'					=> array('includeBlankOption'=>true, 'tl_class'=>'w50'),
		),
		'allowed_cc_types' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['allowed_cc_types'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'					  => array('multiple'=>true, 'tl_class'=>'clr'),
			'options_callback'		  => array('tl_iso_payment_modules', 'getAllowedCCTypes')
		),
		'trans_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['trans_type'],
			'exclude'                 => true,
			'default'				  => 'capture',
			'inputType'				  => 'select',
			'options'				  => array('capture', 'auth'),
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50'),
			'reference'				  => $GLOBALS['TL_LANG']['tl_iso_payment_modules'],
		),
		'postsale_mail' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['postsale_mail'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'              => 'tl_iso_mail.name',
			'eval'					  => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
		),
		'minimum_total' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['minimum_total'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'                 => 0,
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'price', 'tl_class'=>'clr w50'),
		),
		'maximum_total' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['maximum_total'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'                 => 0,
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'price', 'tl_class'=>'w50'),
		),
		'countries' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['countries'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getCountries(),
			'eval'                    => array('multiple'=>true, 'size'=>8, 'tl_class'=>'clr'),
		),
		'shipping_modules' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['shipping_modules'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'eval'                    => array('multiple'=>true, 'size'=>8, 'tl_class'=>'clr'),
		),
		'product_types' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['product_types'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'foreignKey'			  => 'tl_iso_producttypes.name',
			'eval'                    => array('multiple'=>true, 'size'=>8, 'tl_class'=>'clr'),
		),
		'paypal_account' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['paypal_account'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'email', 'tl_class'=>'w50'),
		),
		'payflowpro_user' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payflowpro_user'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
		),
		'payflowpro_vendor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payflowpro_vendor'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
		),
		'payflowpro_partner' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payflowpro_partner'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
		),
		'payflowpro_password' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payflowpro_password'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'hideInput'=>true),
		),
		'payflowpro_transType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['payflowpro_transType'],
			'exclude'                 => true,
			'default'				  => 'Sale',
			'inputType'               => 'select',
			'options'				  => array('Sale', 'Authorization'),
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'reference'				  => &$GLOBALS['TL_LANG']['tl_payment_module']['payflowpro_transTypes']
		),
		'postfinance_pspid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['postfinance_pspid'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
		),
		'postfinance_secret' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['postfinance_secret'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
		),
		'postfinance_method' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['postfinance_method'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'                 => 'POST',
			'options'                 => array('POST', 'GET'),
			'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'tl_class'=>'w50'),
		),
		'authorize_login' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['authorize_login'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'authorize_trans_key' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['authorize_trans_key'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'authorize_trans_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['authorize_trans_type'],
			'exclude'                 => true,
			'default'				  => 'AUTH_CAPTURE',
			'inputType'               => 'select',
			'options'				  => array('AUTH_CAPTURE', 'AUTH_ONLY'),
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'reference'				  => $GLOBALS['TL_LANG']['ISO_PAY']['authorizedotnet']['modes']
		),
		'cybersource_merchant_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['cybersource_merchant_id'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255)
		),
		'cybersource_trans_key' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['cybersource_trans_key'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'style'=>'height: 60px;')
		),
		'cybersource_trans_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['cybersource_trans_type'],
			'exclude'                 => true,
			'default'				  => 'AUTH_ONLY',
			'inputType'               => 'select',
			'options'				  => array('AUTH_CAPTURE', 'AUTH_ONLY'),
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'reference'				  => $GLOBALS['TL_LANG']['ISO_PAY']['authorizedotnet']['modes']
		),
		'authorize_delimiter' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['authorize_delimiter'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>1)
		),
		'epay_merchantnumber' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['epay_merchantnumber'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>7, 'tl_class'=>'w50'),
		),
		'epay_secretkey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['epay_secretkey'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
		),
		'requireCCV' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['requireCCV'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),
		'button' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['button'],
			'exclude'				  => true,
			'inputType'				  => 'fileTree',
			'eval'					  => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'extensions'=>'jpg,jpeg,png,gif'),
		),
		'guests' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['guests'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),
		'protected' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['protected'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('multiple'=>true)
		),
		'debug' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['debug'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),
		'enabled' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['enabled'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		),
	)
);


/**
 * tl_iso_payment_modules class.
 *
 * @extends Backend
 */
class tl_iso_payment_modules extends Backend
{

	public function checkPermission($dc)
	{
		// Hide archived (used and deleted) modules
		$arrModules = $this->Database->execute("SELECT id FROM tl_iso_payment_modules WHERE archive<2")->fetchEach('id');

		if (!count($arrModules))
		{
			$arrModules = array(0);
		}

		$GLOBALS['TL_DCA']['tl_iso_payment_modules']['list']['sorting']['root'] = $arrModules;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'edit':
			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array($this->Input->get('id'), $arrModules))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' payment module ID "'.$this->Input->get('id').'"', 'tl_iso_payment_modules checkPermission()', TL_ACCESS);
					$this->redirect($this->Environment->script.'?act=error');
				}
				break;

			case 'editAll':
			case 'copyAll':
			case 'deleteAll':
				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $arrModules);
				$this->Session->setData($session);
				break;
		}
	}


	/**
	 * Record is deleted, archive if necessary
	 */
	public function archiveRecord($dc)
	{
	}


	/**
	 * Return a string of more buttons for the current payment module.
	 *
	 * @todo Collect additional buttons from payment modules.
	 *
	 * @access public
	 * @param array $arrRow
	 * @return string
	 */
	public function moduleOperations($arrRow)
	{
		$strClass = $GLOBALS['ISO_PAY'][$arrRow['type']];

		if (!strlen($strClass) || !$this->classFileExists($strClass))
			return '';

		try
		{
			$objModule = new $strClass($arrRow);
			return $objModule->moduleOperations();
		}
		catch (Exception $e) {}

		return '';
	}

	public function getAllowedCCTypes(DataContainer $dc)
	{
		$objModuleType = $this->Database->prepare("SELECT * FROM tl_iso_payment_modules WHERE id=?")->limit(1)->execute($dc->id);

		if(!$objModuleType->numRows)
			return array();

		$strClass = $GLOBALS['ISO_PAY'][$objModuleType->type];

		if(!strlen($strClass) || !$this->classFileExists($strClass))
			return array();

		$arrCCTypes = array();
		$objModule = new $strClass($objModuleType->fetchAssoc());

		foreach($objModule->getAllowedCCTypes() as $type)
		{
			$arrCCTypes[$type] = $GLOBALS['TL_LANG']['CCT'][$type];
		}

		return $arrCCTypes;
	}

	public function getOrderStatus($dc)
	{
		$objModule = $this->Database->prepare("SELECT * FROM tl_iso_payment_modules WHERE id=?")->limit(1)->execute($dc->id);

		$strClass = $GLOBALS['ISO_PAY'][$objModule->type];

		if (!strlen($strClass) || !$this->classFileExists($strClass))
			return array();

		try
		{
			$objModule = new $strClass($arrRow);
			return $objModule->statusOptions();
		}
		catch (Exception $e) {}

		return array();
	}


	/**
	 * Get a list of all payment modules available.
	 *
	 * @access public
	 * @return array
	 */
	public function getModules()
	{
		$arrModules = array();

		if (is_array($GLOBALS['ISO_PAY']) && count($GLOBALS['ISO_PAY']))
		{
			foreach( $GLOBALS['ISO_PAY'] as $module => $class )
			{
				$arrModules[$module] = (strlen($GLOBALS['TL_LANG']['PAY'][$module][0]) ? $GLOBALS['TL_LANG']['PAY'][$module][0] : $module);
			}
		}

		return $arrModules;
	}


	/**
	 * Load shipping modules into the DCA. options_callback would not work due to numeric array keys.
	 *
	 * @access public
	 * @param object $dc
	 * @return void
	 */
	public function loadShippingModules($dc)
	{
		$arrModules = array(-1=>$GLOBALS['TL_LANG']['tl_iso_payment_modules']['no_shipping']);

		$objShippings = $this->Database->execute("SELECT * FROM tl_iso_shipping_modules ORDER BY name");

		while( $objShippings->next() )
		{
			$arrModules[$objShippings->id] = $objShippings->name;
		}

		$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['shipping_modules']['options'] = array_keys($arrModules);
		$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['shipping_modules']['reference'] = $arrModules;
	}
}
