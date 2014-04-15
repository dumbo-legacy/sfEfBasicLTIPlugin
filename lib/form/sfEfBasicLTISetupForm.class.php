<?php

/**
 * sfEfBasicLTISetup form.
 *
 * @package    sfEfBasicLTIPlugin
 * @subpackage form
 * @author     Yaismel Miranda <yaismelmp@googlemail.com>
 * @version    SVN: $Id: sfEfBasicLTISetupForm.class.php ympons $
 */
class sfEfBasicLTISetupForm extends BaseForm
{
  public function  configure()
  {
    parent::configure();

    $this->setWidgets(array(
      'url'    => new sfWidgetFormInput(),
      'key'    => new sfWidgetFormInput(),
      'secret' => new sfWidgetFormInput(),
    ));

    $this->widgetSchema->setLabels(array(
      'url' => 'Remote Tool URL',
      'key' => 'Remote Tool Key',
      'secret' => 'Remote Tool Secret',
    ));

    $this->widgetSchema->setNameFormat("basiclti[%s]");
  }
}

