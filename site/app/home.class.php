<?php
/**
 * User Home
 */
class HomeApp extends NyaaControllerApp
{
	function init( )
	{
		$user = $this->Ctrl->appFactory('user');
		$this->uid = $uid  = $this->Ctrl->getSession()->get('user.id');
		if( empty($uid) )
			$this->Ctrl->redirect('#login', array('message'=>'timeout'));

		$this->twitter = $this->Ctrl->appFactory('twitter');

	}

	function formTweet( $Req )
	{
		if(!$Req->isEmpty('tweet'))
		{
			$this->twitter->tweet( $this->uid, $Req->get('tweet') );
		}
		return $this->Ctrl->resultFactory(array(
			'status'=>'redirect',
			'redirect'=> '#home'
		));
	}

	function run( )
	{
		$Tpl = $this->getTemplater( );

		require_once 'form/form.class.php';
		$form = new NyaaForm( );
		$form->addHidden('__APPLY_FROM__', 'home');
		$form->addHidden('__FORM__', 'home.tweet');
		$form->addInput( NyaaFormInput::factory(array(
			'type'=>'textarea',
			'name'=>'tweet'
		)));
		$form->addInput( NyaaFormInput::factory(array(
			'type'=>'submit',
			'label'=>'Tweet',
			'name'=>'submit'
		)));

		$Tpl->set('tweetForm', $form);


		$Tpl->set('id', $this->Ctrl->getSession( )->get('user.id'));

		$url = "http://api.twitter.com/1/statuses/home_timeline.xml";
		$xml = simplexml_load_string( $this->twitter->twitterApi($this->uid, $url, 'GET', array('count'=>10)) );
		$ret = array();
		$ret["twieet"] = array();
		foreach( $xml->status as $status )
		{
			$arr = array();
			$arr['text'] = preg_replace('#@([a-zA-Z0-9_]+)#', '<a href="http://twitter.com/\1" target="_blank">@\1</a>', $status->text);
			$arr['name'] = $status->user->name;
			$arr['screen_name'] = $status->user->screen_name;
			$arr['created_at'] = $status->user->created_at;
			$arr['img'] = $status->user->profile_image_url;
			$arr['source'] = $status->source;
			$ret['twieet'][] = $arr;
		}
		$Tpl->set('twieet', $ret['twieet']);
		return $Tpl->fetch('home.html');
	}
}
?>
