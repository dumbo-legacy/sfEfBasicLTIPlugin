<?php

/**
 * Pluginbasiclti form.
 *
 * @package    sfEfBasicLTIPlugin
 * @subpackage form
 * @author     Yaismel Miranda <yaismelmp@googlemail.com>
 * @version    SVN: $Id: PluginbasicltiForm.class.php ympons $
 */
abstract class PluginbasicltiForm extends BasebasicltiForm
{
  public function configure()
  {
    sfContext::getInstance()->getConfiguration()->getActive()->loadHelpers('I18N');

    sfValidatorBase::setDefaultMessage('required', __('Required field.', null, 'global'));
    sfValidatorBase::setDefaultMessage('invalid', __('Invalid field.', null, 'global'));

    unset($this['created_at'], $this['updated_at']);

    $this->validatorSchema->setPostValidator(new sfValidatorDoctrineUnique(array(
                'model' => 'basiclti',
                'column' => array('name')),
                array(
                'invalid' => __('There is already a external application with this name.', null, 'global')
    )));

    $this->widgetSchema['description'] = new sfWidgetFormTextarea();
    $this->widgetSchema['custom_parameters'] = new sfWidgetFormTextarea();

    $this->widgetSchema['name'] = new sfWidgetFormInputText();
    $this->validatorSchema['name'] = new sfValidatorRegex(array(
                'required'   => true,
                'pattern'    => '/^[0-9a-zA-Z_]{1}[0-9a-zA-Z_ ]{0,127}$/'),array(
                'required'   => __('Required field.', null, 'global')
    ));

    $this->validatorSchema['title'] = new sfValidatorRegex(array(
                'required'   => true,
                'pattern'    => '/^[0-9a-zA-Z_]{1}[0-9a-zA-Z_ ]{0,127}$/'),array(
                'required'   => __('Required field.', null, 'global')
    ));

    $this->validatorSchema['url'] = new sfValidatorUrl(array(
                'required'   => true,
          //      'pattern'    => '/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/[a-zA-Z0-9-]+)*(.[a-zA-Z]+)+$/',
                ),array(
                'required'   => __('Required field.', null, 'global')
    ));

    $this->validatorSchema['key'] = new sfValidatorRegex(array(
                'required'   => true,
                'pattern'    => '/^[0-9a-zA-Z]{1,127}$/'),array(
                'required'   => __('Required field.', null, 'global')
    ));

    $this->validatorSchema['secret'] = new sfValidatorString(array(
                'max_length' => 255),array(
                'required'   => __('Required field.', null, 'global')
    ));

    $this->validatorSchema['height'] = new sfValidatorInteger(array(
                'required'   => false,
                'min'        => 1,
                'max'        => 2700,),array(
                'min'        => __('Only numbers are allowed', null, 'global'),
                'max'        => __('Only numbers are allowed', null, 'global'),
                'invalid'        => __('Only numbers are allowed', null, 'global'),
    ));
  }
}
