<?php
	class FacebookStatus {

		public function getLatest() {
			$access_token = $_SESSION['fb_431206723613188_access_token'];
			$externalUserId = $_SESSION['activeUserId'];

			$status = file_get_contents('https://graph.facebook.com/me/statuses?limit=1&access_token='.$access_token);
			$statuses = json_decode($status);

			foreach ($statuses->data as $data) {
			    return "{$data->message}"; // first row only
			}
		}
	}