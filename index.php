<?php 

require_once 'functions.php';
require_once 'api/api.php';

//Define as principais variáveis do aplicativo.

$app_id = 'APP_ID'; 					//inserir App ID/API Key do Aplicativo.
$app_secret = 'APP_SECRET';				//inserir App Secret do Aplicativo.
$canvas_url = 'CANVAS_URL';				//inserir Url do Canvas Page.
$album_name = 'YOUR_ALBUM_NAME';			//inserir nome para o álbum.
$album_description = 'YOUR_ALBUM_DESCRIPTION';		//inserir descrição para o álbum.


// Atribuimos valores para o objeto Facebook.
$facebook = new Facebook(array(
  'appId' => $app_id,
  'secret' => $app_secret,
  'cookie' => true,
)); 


// Código enviado quando o usuario está autenticado no aplicativo.
$code = $_REQUEST["code"]; 		

       //Obter o access_token com permissão publish_stream.
       if(empty($code))
         {
           $dialog_url= "http://www.facebook.com/dialog/oauth?"
           . "client_id=" . $app_id 
           . "&redirect_uri=" . urlencode($canvas_url)
	   . "&scope=publish_stream";
           echo("<script>top.location.href='" . $dialog_url . 
           "'</script>");
       } 
       else {
         $token_url= "https://graph.facebook.com/oauth/"
         . "access_token?"
         . "client_id=" .  $app_id 
         . "&redirect_uri=" . urlencode($canvas_url)
         . "&client_secret=" . $app_secret
         . "&code=" . $code;
         $response = webcheck($token_url);
         $params = null;
         parse_str($response, $params);
         $access_token = $params['access_token'];

         // Criar um novo álbum.
         $graph_url = "https://graph.facebook.com/me/albums?"
         . "access_token=". $access_token;
   
         $postdata = http_build_query(
         array(
          'name' => $album_name,
          'message' => $album_description
            )
          );
         $opts = array('http' =>
         array(
          'method'=> 'POST',
          'header'=>
            'Content-type: application/x-www-form-urlencoded',
          'content' => $postdata
          )
         );
         $context  = stream_context_create($opts);
         $result = json_decode(webcheck($graph_url, false, 
           $context));

         // Obter o ID novo álbum.
         $album_id = $result->id;

         //Mostrar foto formulário de upload e postar no URL Gráfico.


         $graph_url = "https://graph.facebook.com/". $album_id
           . "/photos?access_token=" . $access_token;
         echo '<html><body>';
         echo '<form enctype="multipart/form-data" action="'
         .$graph_url. ' "method="POST">';
         echo 'Adicionado foto para o album: ' . $album_name .'<br/><br/>';
         echo 'Escolha a foto: ';
         echo '<input name="source" type="file"><br/><br/>';
         echo 'Diga algo sobre a foto: ';
         echo '<input name="message" type="text"
            value=""><br/><br/>';
         echo '<input type="submit" value="Upload" /><br/>';
         echo '</form>';
         echo '</body></html>';

	//Aṕos a imagem ser enviada, será exibida a resposta do proprio GRAPH API.
	
      }
	
?>
