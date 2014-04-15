<?php
/**
 * sfEfBasicLTIPlugin configuration.
 *
 * @package    sfEfBasicLTIPlugin
 * @subpackage config
 * @author     Yaismel Miranda <yaismelmp@googlemail.com>
 * @version    SVN: $Id: sfEfBasicLTIPluginConfiguration.class.php $
 */
class sfEfBasicLTIPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (in_array('basicLTI', sfConfig::get('sf_enabled_modules', array())))
    {
      $this->dispatcher->connect('routing.load_configuration', array('sfEfBasicLTIRouting', 'addRouteForBasicLTI'));
    }
  }
}
