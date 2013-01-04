<?php
	session_start();

	class FacebookStatus {

		public function getLatest() {
			$externamUserId = $_SESSION['activeUserId'];
			$status = file_get_contents('https://graph.facebook.com/'.$externamUserId.'/statuses?access_token=AAACEdEose0cBAPBscI9qpz9RHShCKOyQjlONpujQ6mqEb4DAGFdBuE3lOaNqKHVcIMhwsb4pcgcHVcmNiefXiPRnrecPjdy4LtNCuiCLqu23H4PZB');
			$statuses = json_decode($status);

			foreach ($statuses->data as $data) {
			    return "{$data->message}"; // first row only
			}
		}
	}