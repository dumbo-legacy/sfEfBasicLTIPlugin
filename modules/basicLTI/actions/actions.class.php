<?php

require_once dirname(__FILE__).'/../lib/BasebasicLTIActions.class.php';
require_once dirname(__FILE__).'/../lib/basicLTIGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/basicLTIGeneratorHelper.class.php';

/**
 * basicLTI actions.
 *
 * @package    sfEfBasicLTIPlugin
 * @subpackage basicLTI
 * @author     Yaismel Miranda <yaismelmp@googlemail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class basicLTIActions extends BasebasicLTIActions
{
  public function executeLaunch(sfWebRequest $request)
  {
    $resource_id = $request->getParameter('id');

    $resource = BasicLTIparamsBuilder::getLMSResource($resource_id);
    $context = BasicLTIparamsBuilder::getLMSContext();
    $user = BasicLTIparamsBuilder::getLMSUser();

    $blti_manager =  new BasicLTIManager(array(
      'resource' => $resource,
      'context'  => $context,
      'user'    => $user,
    ));
    $params = $blti_manager->buildRequestParams();

    $params['ext_lms'] = 'LMS';

    $params['oauth_callback'] = 'about:blank';

    $params = $blti_manager->signParameters($params, $resource->url, "POST", $resource->key, $resource->secret, 'Enviar datos');

    $content = $blti_manager->postLaunchHTML($params, $resource->url, false,
        "width=\"100%\" height=\"500\" scrolling=\"auto\" frameborder=\"1\" transparency");

    return $this->renderText($content);
  }

  public function executeTest(sfWebRequest $request)
  {
    $external_tool = $request->getParameter('tool_id', 2);

    $resource = BasicLTIparamsBuilder::getLMSResource($external_tool);
    $context = BasicLTIparamsBuilder::getLMSContext();
    $user = BasicLTIparamsBuilder::getLMSUser();

    $blti_manager =  new BasicLTIManager(array('resource' => $resource, 'context' => $context, 'user' => $user));
    return $this->renderText($blti_manager->launch());
  }
}
