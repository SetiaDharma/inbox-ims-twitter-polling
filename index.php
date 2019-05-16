<?php
    require "./db_connect.php";
    require "./config.php";

    while(true){
        $done = false;
        $insert = 0;

        while(!$done){
            if(isset($next_cursor) && $insert > 0){
                sleep(60);
                $direct_messages = $connection->get("direct_messages/events/list", ["count" => 50, "cursor" => $next_cursor]); // get list of direct messages
            } elseif($insert == 0 || !$done) {
                $direct_messages = $connection->get("direct_messages/events/list", ["count" => 50]);
            }

            $insert = 0;

            if(isset($direct_messages->next_cursor)){
                $next_cursor = $direct_messages->next_cursor;
            } else {
                $next_cursor = null;
            }
            
            if(!$done && isset($direct_messages->events)){
                $events = $direct_messages->events;

                foreach ($events as $key => $event) {
                    $sender_id = $event->message_create->sender_id;

                    if($sender_id == $ID){
                        continue;
                    }
                    
                    $message_id = $event->id;
                    $query = "SELECT * FROM tb_inbox WHERE tb_inbox.`chat_id` = '$message_id'";
                    $result = $mysqli->query($query);

                    if($result->num_rows > 0){
                        $done = true;
                        break;
                    } else {
                        $message = $event->message_create->message_data->text;
                        $urls = $event->message_create->message_data->entities->urls;
                        
                        $type = "msg";
                        
                        foreach($urls as $url){
                            $regex = "/http[s]?:\/\/(www.)?(goo.gl|google.com)?\/?maps\/?/i";
                            $result = preg_match($regex, $url->expanded_url);
                            
                            if(boolval($result)){
                                $type = "loc";
                                break;
                            }
                        }
                        
                        if(isset($event->message_create->message_data->attachment)){
                            $url = "";
                            $message = explode(" ", $message);
                            
                            $attachment = $event->message_create->message_data->attachment;
                            
                            if($attachment->media->type == "video" || $attachment->media->type == "animated_gif"){
                                $video_variant = $attachment->media->video_info->variants[0];
                                $url = $video_variant->url;
                                $url = explode("?", $url);
                                $url = $url[0];
                                $type = "file";
                            } elseif($attachment->media->type == "photo"){
                                $url = $attachment->media->media_url_https;
                                $type = "img"; 
                            }

                            array_pop($message);
                            $message = implode(" ", $message);
                            $message = empty($message) ? $url : $message." ".$url;
                        }
                        
                        $mysqli->query("INSERT INTO tb_inbox(chat_id,in_msg,type,sender_id) VALUES ('$message_id','$message','$type',' $sender_id');");
                        $inbox_id = $mysqli->insert_id;
                        ++$insert;
                    }
                }

                if($done){
                    break;
                }
            } else {
                break;
            }
        };
    }
?>