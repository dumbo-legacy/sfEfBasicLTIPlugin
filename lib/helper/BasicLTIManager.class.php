<?php
/**
 * BasicLTIManager class.
 *
 * This class is based in Basic LTI UTIL developed by Andy Smith
 *
 * @package    sfEfBasicLTIPlugin
 * @subpackage helper
 * @author     Yaismel Miranda <yaismelmp@googlemail.com>
 * @version    SVN: $Id: BasicLTIManager.class.php $
 */
class BasicLTIManager
{
  protected $organization = null;
  protected $resource;
  protected $user;
  protected $context;

  public function __construct($params = array())
  {
    $this->resource = $params['resource'];
    $this->user = $params['user'];
    $this->context = $params['context'];

    if (isset($params['org']))
    {
      $this->organization = $params['org'];
    }
  }

  public function buildRequestParams()
  {
    $request_params = array(
      'resource_link_id' => $this->resource->id,
      'resource_link_title' => $this->resource->name,
      'resource_link_description' => $this->resource->description,
      'user_id' => $this->user->id,
      'roles' => 'Instructor',
      'context_id' => $this->context->id,
      'context_label' => $this->context->label,
      'context_title' => $this->context->title,
      'launch_presentation_locale' => $this->user->language,
    );

    if ($this->resource->send_name)
    {
      $request_params['lis_person_name_full'] = $this->user->fullname;
    }

    if ($this->resource->send_email)
    {
      $request_params['lis_person_contact_email_primary'] = $this->user->email;
    }

    if ($this->resource->custom_parameters)
    {
      $custom = $this->split_custom_parameters($this->resource->custom_parameters);
      $request_params = array_merge($custom, $request_params);
    }

    if (!isset($this->resource->open_in_popup))
    {
      $this->resource->open_in_popup = false;
    }

    return $request_params;
  }

  public function split_custom_parameters($customstr)
  {
    $map_keyname = function($key){
      $newkey = "";
      $key = strtolower(trim($key));
      foreach (str_split($key) as $ch){
        if ( ($ch >= 'a' && $ch <= 'z') || ($ch >= '0' && $ch <= '9') ){
            $newkey .= $ch;
        }
        else{
            $newkey .= '_';
        }
      }
      return $newkey;
    };

    $lines = preg_split("/[\n;]/", $customstr);
    $retval = array();
    foreach ($lines as $line) {
        $pos = strpos($line,"=");
        if ( $pos === false || $pos < 1 ) continue;
        $key = trim(substr($line, 0, $pos));
        $val = trim(substr($line, $pos+1));
        $key = $map_keyname($key);
        $retval['custom_'.$key] = $val;
    }
    return $retval;
  }

  public function signParameters($oldparms, $endpoint, $method, $oauth_consumer_key, $oauth_consumer_secret, $submit_text, $org_id = false, $org_desc = false)
  {
      global $last_base_string;
      $parms = $oldparms;
      $parms['lti_version'] = 'LTI-1p0';
      $parms['lti_message_type'] = 'basic-lti-launch-request';

      if ( $org_id ) $parms['tool_consumer_instance_guid'] = $org_id;
      if ( $org_desc ) $parms['tool_consumer_instance_description'] = $org_desc;

      $parms['ext_submit'] = $submit_text;

      $test_token = '';

      $hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
      $test_consumer = new OAuthConsumer($oauth_consumer_key, $oauth_consumer_secret, NULL);

      $acc_req = OAuthRequest::from_consumer_and_token($test_consumer, $test_token, $method, $endpoint, $parms);

      $acc_req->sign_request($hmac_method, $test_consumer, $test_token);

      // Pass this back up 'out of band' for debugging
      $last_base_string = $acc_req->get_signature_base_string();

      $newparms = $acc_req->get_parameters();

      return $newparms;
  }

  public function postLaunchHTML($newparms, $endpoint, $debug=false, $iframeattr=false, $launchtype=1) {
      global $last_base_string;

      $get_string = function($key, $value)
      {
        return $key;
      };

      $r = "<div id=\"ltiLaunchFormSubmitArea\">\n";
      if ( $iframeattr ) {
          $r = "<form action=\"".$endpoint."\" name=\"ltiLaunchForm\" id=\"ltiLaunchForm\" method=\"post\" target=\"basicltiLaunchFrame\" encType=\"application/x-www-form-urlencoded\">\n" ;
      } else {
          $r = "<form action=\"".$endpoint."\" name=\"ltiLaunchForm\" id=\"ltiLaunchForm\" method=\"post\" encType=\"application/x-www-form-urlencoded\">\n" ;
      }
      $submit_text = $newparms['ext_submit'];
      foreach($newparms as $key => $value ) {
          $key = htmlspecialchars($key);
          $value = htmlspecialchars($value);
          if ( $key == "ext_submit" ) {
              $r .= "<input type=\"submit\" name=\"";
          } else {
              $r .= "<input type=\"hidden\" name=\"";
          }
          $r .= $key;
          $r .= "\" value=\"";
          $r .= $value;
          $r .= "\"/>\n";
      }
      if ( $debug ) {
          $r .= "<script language=\"javascript\"> \n";
          $r .= "  //<![CDATA[ \n" ;
          $r .= "function basicltiDebugToggle() {\n";
          $r .= "    var ele = document.getElementById(\"basicltiDebug\");\n";
          $r .= "    if(ele.style.display == \"block\") {\n";
          $r .= "        ele.style.display = \"none\";\n";
          $r .= "    }\n";
          $r .= "    else {\n";
          $r .= "        ele.style.display = \"block\";\n";
          $r .= "    }\n";
          $r .= "} \n";
          $r .= "  //]]> \n" ;
          $r .= "</script>\n";
          $r .= "<a id=\"displayText\" href=\"javascript:basicltiDebugToggle();\">";
          $r .= $get_string("toggle_debug_data","basiclti")."</a>\n";
          $r .= "<div id=\"basicltiDebug\" style=\"display:none\">\n";
          $r .=  "<b>".$get_string("basiclti_endpoint","basiclti")."</b><br/>\n";
          $r .= $endpoint . "<br/>\n&nbsp;<br/>\n";
          $r .=  "<b>".$get_string("basiclti_parameters","basiclti")."</b><br/>\n";
          foreach($newparms as $key => $value ) {
              $key = htmlspecialchars($key);
              $value = htmlspecialchars($value);
              $r .= "$key = $value<br/>\n";
          }
          $r .= "&nbsp;<br/>\n";
          $r .= "<p><b>".$get_string("basiclti_base_string","basiclti")."</b><br/>\n".$last_base_string."</p>\n";
          $r .= "</div>\n";
      }
      $r .= "</form>\n";
      if ($iframeattr && (!$this->resource->open_in_popup)) {
          $r .= "<iframe name=\"basicltiLaunchFrame\"  id=\"basicltiLaunchFrame\" src=\"\"\n";
          $r .= $iframeattr . ">\n<p>".$get_string("frames_required","basiclti")."</p>\n</iframe>\n";
      }
      else{
        $r .= "<p>Aplicacion en otra ventana</p>";
      }
      if ( ! $debug ) {
          $ext_submit = "ext_submit";
          $ext_submit_text = $submit_text;
          $r .= " <script type=\"text/javascript\"> \n" .
              "  //<![CDATA[ \n" .
              "    var openTool = function() { \n".
              "    document.getElementById(\"ltiLaunchForm\").style.display = \"none\";\n" .
              "    nei = document.createElement('input');\n" .
              "    nei.setAttribute('type', 'hidden');\n" .
              "    nei.setAttribute('name', '".$ext_submit."');\n" .
              "    nei.setAttribute('value', '".$ext_submit_text."');\n" .
              "    document.getElementById(\"ltiLaunchForm\").appendChild(nei);\n" .
              "    document.ltiLaunchForm.submit(); \n" .
              "    } \n";
          if ($launchtype==1){
              $r .= "openTool();";
          }
          $r .= "  //]]> \n" .
              " </script> \n";
      }
      $r .= "</div>\n";
      return $r;
  }

  public function launch($width = '100%', $height = '500px')
  {
    $params = $this->buildRequestParams();

    $params['ext_lms'] = 'LMS';

    $params['oauth_callback'] = 'about:blank';

    $params = $this->signParameters($params, $this->resource->url, "POST", $this->resource->key, $this->resource->secret, 'Enviar datos');

    $content = $this->postLaunchHTML($params, $this->resource->url, false,
        "width=\"$width\" height=\"$height\" scrolling=\"auto\" frameborder=\"1\" transparency");

    return $content;
  }

  public function __set($attribute, $value)
  {
    $this->$attribute = $value;
  }

  public function __get($attribute)
  {
    return $this->$attribute;
  }
}
