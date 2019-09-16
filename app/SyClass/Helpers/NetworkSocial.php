<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SyClass\Helpers;

use Abraham\TwitterOAuth\TwitterOAuth;
/**
 * Description of NetworkSocial
 *
 * @author johnm
 */
class NetworkSocial {
    
    /**
     * publicar en twitter automaticamente
     * 
     * @throws \Exception
     */
    public function publishTwitter($photo,$data, $inputTw, $deporteName){
        try{
            // creamos el tweet
            $str_t = "";
            // titulo
            $str_t .= $data->title_big."\n";
            // link + info
            $str_t .= "+ Info ".$data->link_more_information."\n";
            // fecha
            $str_t .= $data->date_formated."\n";
            // hastags
            $str_t .= "#MiParcheDeportivo #Valle #".str_replace(" ","",ucwords($deporteName))." #YoHagoDeportes #LigasDeportivas ".$inputTw;
            
            // verificamos
            if(strlen($str_t)>280){
                $str_t = substr($str_t,0,(strlen($str_t)-12));
            }
            if(strlen($str_t)>280){
                $str_t = substr($str_t,0,(strlen($str_t)-16));
            }
            if(strlen($str_t)>280){
                $str_t = substr($str_t,0,(strlen($str_t)-17));
            }
            
            $consumer_key = "vUnuAF70iBpqQorSzgFa8bdA1";
            $consumer_secret = "MDnrg4rZoS4CzqeBwmJx7rtrMuWGUJrJg5KsAUpbfsutFO9vKx";
            $access_token = "887858469323366400-EvQ3LWy8r657Ib8f3UPY0YHLaonzhph";
            $access_token_secret = "rP60hXtZAsfWCwfXDToTxqzwBZGQMpDohqWWA6ZuBPT0b";
            
            $connection = new TwitterOAuth(
                $consumer_key, 
                $consumer_secret, 
                $access_token,
                $access_token_secret
            );
            $media1 = $connection->upload(
                    'media/upload', 
                    ['media' =>$photo]
            );
            //$media2 = $connection->upload('media/upload', ['media' => '/path/to/file/kitten2.jpg']);
            $parameters = [
                'status' =>$str_t,
                'media_ids' => implode(',', [$media1->media_id_string])
            ];
            
            $result = $connection->post('statuses/update', $parameters);
            
            return array(
                "url"=>"https://twitter.com/Miparchedeporti/status/".$result->id
            );
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function publishFacebook(){
        try{
            $fb = new \Facebook\Facebook([
                'app_id' => '411529212677451',
                'app_secret' => '4952bc29f923da3d600252ba13a87b9a',
                'default_graph_version' => 'v3.0',
                //'default_access_token' => '{access-token}', // optional
            ]);
            
            $linkData = [
                'link' => 'http://www.example.com',
                'message' => 'User provided message',
                ];
            
            try {
                $helper = $fb->getRedirectLoginHelper();
                
                try{
                    $accessToken = $helper->getAccessToken();
                    echo "=>".var_dump($accessToken); 
                } catch (\Exception $exe) {
                    echo print_r($exe,true);
                }//exit;
                // Returns a `Facebook\FacebookResponse` object
                $response = $fb->post('/me/feed', $linkData,"1848827281827887|Rq6IYNUtVhoUEHGnQYo8SlB_P14");
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            $graphNode = $response->getGraphNode();

            echo 'Posted with id: ' . $graphNode['id'];

        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}
