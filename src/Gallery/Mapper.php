<?php
namespace LVAC\Gallery;

class Mapper
{
    public function getAlbumList()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://api.flickr.com/services/rest/', [
            'query' => [
                'api_key' =>  '97f8064ffb867c6f54c1300307d830cd',
                'method' => 'flickr.photosets.getList',
                'user_id' => '108994068@N03',
            ]
            ]);
        $photosets = $response->xml()->photosets;
        $array = array();
        foreach ($photosets->photoset as $photoset) {
            $array[] = array(
                'title' => (string)$photoset->title,
                'farm' => (string)$photoset['farm'],
                'server' => (string)$photoset['server'],
                'id' => (string)$photoset['id'],
                'primary' => (string)$photoset['primary'],
                'secret' => (string)$photoset['secret'],
            );
        }
        return $array;
    }
}
