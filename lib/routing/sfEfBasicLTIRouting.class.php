<?php
/**
 *
 * @package    sfEfBasicLTIPlugin
 * @subpackage routing
 * @author     Yaismel Miranda <yaismelmp@googlemail.com>
 * @version    SVN: $Id: sfEfBasicLTIRouting.class.php 25546 2009-12-17 23:27:55Z Yaismel $
 */
class sfEfBasicLTIRouting
{
  static public function addRouteForBasicLTI(sfEvent $event)
  {
    $r = $event->getSubject();
    //$r->prependRoute('basiclti_launch', new sfRoute('/launch', array('module' => 'basicLTI', 'action' => 'launch')));
    $r->prependRoute('basiclti', new sfDoctrineRouteCollection(array(
      'name'                => 'basiclti',
      'model'               => 'basiclti',
      'module'              => 'basicLTI',
      'prefix_path'         => 'basiclti',
      'with_wildcard_routes' => true,
      'collection_actions'  => array('filter' => 'post', 'batch' => 'post'),
      'requirements'        => array('id' => '[\w\d_]+'),
    )));
  }
}
