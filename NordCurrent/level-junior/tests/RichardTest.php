<?php


use Example\Api\Api;
use Example\Utility\Random;
use PHPUnit\Framework\TestCase;

class RichardTest extends TestCase
{

    /** test1 */
    public function testToCheckPlayersNicknameOnChange()
    {
        $randPlatform = rand(1, 8);
        //using static func that I added in src/utility/Random.php
        $randVersion = Random::generateRandomVersion();
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession(array(
            "name" => $name,
            "platform" => $randPlatform,
            "version" => $randVersion,
            "region" => "us"
        ));
        $response = json_decode($response, true);
        $api->setNick(array(
            "player-id" => $response['data'][0]['player-id'],
            "nickname" => "Marius"
        ));
        $result= $api->getPlayer(array(
            "player-id" => $response['data'][0]['player-id']
        ));
        $result = json_decode($result, true);
        $testNick = $result['data'][0]['nick'];
        $this->assertSame("Marius", $testNick, "Player's nickname doesn't change on setNick");
    }

    /** test2 */
    public function testToCheckPlayersNicknameChangeWhilePlayingTournament()
    {

        $randVersion = Random::generateRandomVersion();
        $randRegion = Random::generateRandomRegion();
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession(array(
            "name" => $name,
            "platform" => 5,
            "version" => $randVersion,
            "region" => $randRegion
        ));
        $response = json_decode($response, true);
        $api->registerLeaderboard(array(
            "player-id" => $response['data'][0]['player-id'],
            "session-id" => $response['data'][0]['session-id'],
            "leaderboard-id" => $response['data'][0]['leaderboards'][0]["id"]
        ));
        $api->setNick(array(
            "player-id" => $response['data'][0]['player-id'],
            "nickname" => "Tomas"
        ));

        //var_dump($response);
        $result = $api->getAllPlayer();
        $result = json_decode($result, true);
        $arraySize = (sizeof($result['data'][0]['players'])) - 1;
        $currentPlayer = $result['data'][0]['players'][$arraySize]['nick'];
        $this->assertSame("Tomas", $currentPlayer, "Player's nickname doesn't change while playing tournaments");

        //Was short in time, that's why just copied and pasted code and going to do for different platform
        $response = $api->openSession(array(
            "name" => $name,
            "platform" => 8,
            "version" => $randVersion,
            "region" => $randRegion
        ));
        $response = json_decode($response, true);
        $api->registerLeaderboard(array(
            "player-id" => $response['data'][0]['player-id'],
            "session-id" => $response['data'][0]['session-id'],
            "leaderboard-id" => $response['data'][0]['leaderboards'][0]["id"]
        ));
        $api->setNick(array(
            "player-id" => $response['data'][0]['player-id'],
            "nickname" => "Jonas"
        ));
        $result = $api->getAllPlayer();
        $result = json_decode($result, true);
        $arraySize = (sizeof($result['data'][0]['players'])) - 1;
        $currentPlayer = $result['data'][0]['players'][$arraySize]['nick'];
        $this->assertSame("Jonas", $currentPlayer, "Player's nickname doesn't change while playing tournaments");
    }
    /** test3 */
    // Didn't fully understand test case 3 description, what kind of result is expected to be checked

    /** test4 */
    public function testToCheckIfPlayerTokenUpdatedWhenRefreshingPlayer()
    {
        $randPlatform = rand(1, 8);
        $randVersion = Random::generateRandomVersion();
        $randRegion = Random::generateRandomRegion();
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession(array(
            "name" => $name,
            "platform" => $randPlatform,
            "version" => $randVersion,
            "region" => $randRegion
        ));
        $response = json_decode($response, true);
        $playerData= $api->getPlayer(array(
            "player-id" => $response['data'][0]['player-id']
        ));
        $playerData = json_decode($playerData, true);
        $firstToken = $playerData['data'][0]['session-id'];
        $api->refresh(array(
            "player-id" => $response['data'][0]['player-id'],
            "session-id" => $firstToken
        ));
        $playerData= $api->getPlayer(array(
            "player-id" => $response['data'][0]['player-id']
        ));
        $playerData = json_decode($playerData, true);
        $newToken = $playerData['data'][0]['session-id'];


        $this->assertNotEquals($firstToken, $newToken,"Player's token didn't change when refreshing player");
    }
    /** test5 */
    public function testToCheckIfPlayerTokenExistsAfterClosingSession(){
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession(array(
            "name" => $name,
            "platform" => 3,
            "version" => "1.2",
            "region" => "fr"
        ));
        $response = json_decode($response, true);
        $player_id = $response['data'][0]['player-id'];
        $token = $response['data'][0]['session-id'];
        $api->closeSession(array(
            "player-id" => $player_id,
            "session-id" => $token
        ));
        $this->assertEmpty($token, "Token still exists after closing session");
    }
    /** test6 */
    public function testToCheckPlayerCreationWithoutAnyPlatform()
    {
        $randVersion = Random::generateRandomVersion();
        $randRegion = Random::generateRandomRegion();
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession(array(
            "name" => $name,
            "version" => $randVersion,
            "region" => $randRegion
        ));
        $response = json_decode($response, true);
        $response = $response['status'];
        $this->assertSame("fail", $response, "System still creates player without any platform");
    }


}