<?php
/**
 * Main Configure
 * ----
 *
 */
require_once dirname(__FILE__).'/conf.class.php';
require_once 'form/form.class.php';
require_once 'third/qauth/twitteroauth/twitteroauth.php';

class ConfTwitterApp extends ConfApp
{

	function init( )
	{
		$this->twitter = $this->Ctrl->appFactory('twitter');
		$this->uid = $this->Ctrl->getSession()->get('user.id');
	}

	function snipConf( $opt )
	{
		$pare = $this->twitter->getOAuthToken( $this->uid );
		if($pare == false)
		{
			list($t,$s) = $this->twitter->newOAuthToken('http://localhost'.$this->Ctrl->getConf('site.url').'/app/conf.twitter/from/'.$opt['from']);
			$_SESSION['con_key'] = $t;
			$_SESSION['con_secret'] = $s;
			$title = "連携する";
			$url = $this->Ctrl->getConf('twitter.authurl').$t;
		}else{
			$title = "解除する";
			$url = $this->Ctrl->getConf('site.url').'/app/conf.twitter/destroy/true/from/'.$opt['from'];
		}
		return compact('title','url');
	}


	function run( )
	{
		// For Twitter Call Back
		// Twitter will replay
		//oauth_token=GXRejSGTlj7lEXXRUV7azpsHIRfpIcRaDVXL304A&oauth_verifier=uAF0gARsbRbrLRjIyYSQE3UoRkdcZMILSWIeQrbbwQ
		if( !$this->Request->isEmpty('oauth_verifier') )
		{
			$token        = $this->Request->get('oauth_token');
			$token_secret = $this->Request->get('oauth_verifier');
			$access_token = $this->twitter->verifieOAuthToken( $token_secret, $_SESSION['con_key'], $_SESSION['con_secret']);
			$this->twitter->saveOAuth( $this->uid, $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$this->Ctrl->redirect( $this->Request->get('from'), array('message'=>'登録しました') );
		}

		if( !$this->Request->isEmpty('destroy') )
		{
			$this->twitter->deleteOAuth( $this->uid );
			$this->Ctrl->redirect( $this->Request->get('from'), array('message'=>'解除しました') );
		}

	}
}
?>
