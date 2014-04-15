<?php

class BasebasicLTIComponents extends sfComponents
{
  public function executeLaunch(sfWebRequest $request)
  {
    if (isset($this->tool_id))
    {
      $resource = BasicLTIparamsBuilder::getLMSResource($this->tool_id);
      $context = BasicLTIparamsBuilder::getLMSContext();
      $user = BasicLTIparamsBuilder::getLMSUser();

      if ($resource && $context && $user)
      {
        $basiclti_manager =  new BasicLTIManager(array('resource' => $resource, 'context' => $context, 'user' => $user));
        return $this->view_tool = $basiclti_manager->launch();
      }
    }

    $this->view_tool = 'Error loading...';
  }
}
