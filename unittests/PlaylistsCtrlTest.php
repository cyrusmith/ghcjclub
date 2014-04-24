<?php

/**
 * Created by PhpStorm.
 * User: Artist
 * Date: 24.04.14
 * Time: 15:17
 */
class PlaylistsCtrlTest extends BackendTest
{
    /**
     * @var DataBase
     */
    private $db;

    /**
     * @var RDS
     */
    private $rds;

    protected function setUp()
    {
        parent::setUp();
        $this->db = ObjectsPool::get('DataBase');
        $this->rds = ObjectsPool::get('RDS');
    }

    public function provideNewPlaylists()
    {
        return array(
            array("Super track", 1, "yes", array('1', '2', '3')) // Name, userId, public, id треков
        );
    }

    /**
     * @dataProvider provideNewPlaylists
     */
    public function testNewAndFill($name, $userId, $public, $trackIds)
    {
        //Тест создания
        TestUtils::launchRoute("POST", "playlists/new", array(
            "name" => $name,
            "userId" => $userId,
            "public" => $public
        ));
        $playlistId = TestUtils::getLastJson();
        self::assertGreaterThan(0, $playlistId);

        //Тест заполнения треками
        TestUtils::launchRoute("GET", "playlists/tracks/set", array(
            "trackIds" => $trackIds,
            "listId" => $playlistId,
        ));
        $count = $this->db->select('playlists_have_tracks', 'count(*) as count', 'listId = ' . $playlistId, DB_SELECT_ONE);
        self::assertGreaterThan(0, $count);

        //Тест списка треков
        TestUtils::launchRoute("GET", "playlists/tracks/list", array(
            "listId" => $playlistId,
        ));
        $actualList = TestUtils::getLastJson();
        self::assertSame($trackIds, $actualList);

        //Тест списка листов
        $this->rds->userId = 1;
        TestUtils::launchRoute("GET", "playlists/list", array(
            "userId" => $userId,
            "fullInfo" => "true"
        ));
        $playlists = TestUtils::getLastJson();

        $gotIt = false;
        foreach ($playlists as $playlist) {
            if ($playlist['name'] == $name) {
                $gotIt = true;
                break;
            }
        }
        if (!$gotIt) {
            $this->fail("Name was not found");
        }

        //Апдейт
        $newName = $name . " new one";
        TestUtils::launchRoute("POST", "playlists/update", array(
            "id" => $playlistId,
            "name" => $newName,
            "public" => $public,
            "userId" => $userId,
        ));
        $dbName = $this->db->select('playlists', 'name', 'id = ' . $playlistId, DB_SELECT_ONE);
        self::assertEquals($newName, $dbName);

        //Удаление
        TestUtils::launchRoute("GET", "playlists/delete", array(
            "id" => $playlistId,
        ));
        $cLists = $this->db->select('playlists', 'count(*)', 'id = ' . $playlistId, DB_SELECT_ONE);
        self::assertEquals(0, $cLists);

        $cTracks = $this->db->select('playlists_have_tracks', 'count(*) as count', 'listId = ' . $playlistId, DB_SELECT_ONE);
        self::assertEquals(0, $cTracks);

    }


}
 