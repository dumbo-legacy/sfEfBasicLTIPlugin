<?php

class BasicLTIparamsBuilder
{
  /**
   *  Return the LMS Organization
   */
  public static function getLMSOrganization()
  {
  /*TODO*/
  }

  /**
   *  Return the LMS Context
   */
  public static function getLMSContext()
  {
  /*TODO*/
  }

  /**
   *  Return the LMS Resource
   */
  public static function getLMSResource($id)
  {
  /*TODO*/
  }

  /**
   *  Return the LMS User
   */
  public static function getLMSUser()
  {
  /*TODO*/
  }


  /**
   *
   * Default Values
   */
  public static function getDefaultOrganization()
  {
    $organization = new stdClass();
    $organization->id = 'MDC';
    $organization->description = 'Miami Dade College';

    return $organization;
  }

  public static function getDefaultContext()
  {
    $context = new stdClass();
    $context->id = '456434513';
    $context->title = 'Design of Personal Environments';
    $context->label = 'SI182';

    return $context;
  }

  public static function getDefaultUser()
  {
    $user = new stdClass();
    $user->id = '292832126';
    $user->rol = 'Instructor';  // or Learner
    $user->fullname = 'Jane Q. Public';
    $user->email = 'user@school.edu';
    $user->sourcedid = 'school.edu:user';

    return user;
  }

  public static function getDefaultResource()
  {
    $resource = new stdClass();
    $resource->id = '120988f929-274612';
    $resource->name = 'Weekly Blog';
    $resource->description = 'Each student needs to reflect on the weekly reading.  These should be one paragraph long.';

    return $resource;
  }
}
