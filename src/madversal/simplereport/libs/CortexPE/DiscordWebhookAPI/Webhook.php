<?php



/**

 *

 *  _____      __    _   ___ ___

 * |   \ \    / /__ /_\ | _ \_ _|

 * | |) \ \/\/ /___/ _ \|  _/| |

 * |___/ \_/\_/   /_/ \_\_| |___|

 *

 * This program is free software: you can redistribute it and/or modify

 * it under the terms of the GNU Lesser General Public License as published by

 * the Free Software Foundation, either version 3 of the License, or

 * (at your option) any later version.

 *

 * This program is distributed in the hope that it will be useful,

 * but WITHOUT ANY WARRANTY; without even the implied warranty of

 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

 * GNU Lesser General Public License for more details.

 *

 * You should have received a copy of the GNU Lesser General Public License

 * along with this program.  If not, see <https://www.gnu.org/licenses/>.

 *

 * Written by @CortexPE <https://CortexPE.xyz>

 * Intended for use on SynicadeNetwork <https://synicade.com>

 */



declare(strict_types = 1);



namespace madversal\simplereport\libs\CortexPE\DiscordWebhookAPI;





use madversal\simplereport\libs\CortexPE\DiscordWebhookAPI\task\DiscordWebhookSendTask;

use pocketmine\Server;



class Webhook {

	/** @var string */

	protected $url;



	public function __construct(string $url){

		$this->url = $url;

	}



	public function getURL(): string{

		return $this->url;

	}



	public function isValid(): bool{

		return filter_var($this->url, FILTER_VALIDATE_URL) !== false;

	}



	public function send(Message $message): void{

		$ch = curl_init($this->getURL());

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

		$response = [curl_exec($ch), curl_getinfo($ch, CURLINFO_RESPONSE_CODE)];

		$server = Server::getInstance();

		if(!in_array($response[1], [200, 204])){

			$server->getLogger()->error("[DiscordWebhookAPI] Got error ({$response[1]}): " . $response[0]);

		}

		curl_close($ch);

	}

}

