<?php
use OpenTok\OpenTok;
use OpenTok\Role;

class Create extends CAction
{
	public function run()
	{
		// Get the controller
		$controller = $this -> getController();

		// Get subscriber's id
		$subid = $controller -> subscriber -> id;

		// Get publisher's id
		$pubid = $controller -> publisher -> id;

		// Initialize room model
		$roomModel = new RoomModel();

		// Find room by attributes
		$room = $roomModel -> room(
			$pubid, $subid
		) -> find();

		// Initialize open tok library
        $conf = include_once Yii::app()->basePath."/config/opentok.php";
        $opentok = new OpenTok($conf['API_KEY'], $conf['API_SECRET']);

		if($room) {
			$OpenTokID = $room -> opentok_id;
			$roomID = $room -> id;
		} else {
			$session = $opentok -> createSession(array(
                'location'=>$controller->ip
            ));
			$OpenTokID = $session->getSessionId();

			$roomModel -> sessionid = $pubid;
			$roomModel -> to_sessionid = $subid;
			$roomModel -> opentok_id = $OpenTokID;

			$roomModel -> save();
			$roomID = $roomModel -> id;
		}

		$informer = new InformerModel();

		$inform = $informer -> issetInform($pubid, $subid) -> count();
		if(!$inform) {
			$informer -> sessionid = $pubid;
			$informer -> to_sessionid = $subid;
			$informer -> save();
		}

        $token = $opentok -> generateToken($OpenTokID, array(
            'role'       => Role::PUBLISHER,
            'expireTime' => Yii::app() -> params['tokenTime']
        ));

		$controller -> render("index", array(
			"publisher" => $controller -> publisher,
			"subscriber" => $controller -> subscriber,
			"token" => $token,
			"opentokid" => $OpenTokID,
			"roomid" => $roomID,
            "apiKey"=>$conf['API_KEY'],
		));
	}
}