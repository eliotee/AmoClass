<?php

	class amocrm{

		private $login;
		private $hash;
		private $code;
		private $domain;
		private $status;
		private function getCurl($link, $json = false){

			$curl=curl_init(); 
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
			curl_setopt($curl,CURLOPT_URL,$link);
			curl_setopt($curl,CURLOPT_HEADER,false);
			curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
			if ($json == true){
				$out = json_decode(curl_exec($curl));
			}else
			{
				$out = curl_exec($curl);
			}
			
			return $out;

		}

		private function postCurl($link, $array, $json){

			$curl=curl_init(); 
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
			curl_setopt($curl,CURLOPT_URL,$link);
			curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
			curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($array));
			curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
			curl_setopt($curl,CURLOPT_HEADER,false);
			curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

			try { 
				if ($json == true){
					$out = json_decode(curl_exec($curl));
					return $out;

				}
				else{
					$out = curl_exec($curl);
					return $out;
				}
				
				} catch (Exception $e){

				$this->status = 'Ошибка соединения';
				}

		}


		function __construct($login, $hash, $subdomain){
			$this->login = $login;
	
			$this->hash = $hash;
			$user=array(
  					'USER_LOGIN'=>$this->login, #Ваш логин (электронная почта)
  					'USER_HASH'=>$this->hash #Хэш для доступа к API (смотрите в профиле пользователя)
						);
			$this->domain='https://'.$subdomain . '.amocrm.ru'; #Наш аккаунт - поддомен
			$link=$this->domain . '/private/api/auth.php?type=json';
			$out = $this->postCurl($link, $user, true);
			
			if ($out->response->auth == 1){


				$this->status = 'Подключились';
			}
			else{


				$this->status = 'Ошибка авторизации';
			}
			
		}
		function __toString(){

			return $this->status;
		}
		function getContactByLead($id){
			$link = $this->domain . '/private/api/v2/json/links/list/?links[0][from]=leads&links[0][from_id]=' . $id . '&links[0][to]=contacts';
			$out = $this->getCurl($link, true);
			return $out->response->links[0]->to_id;



		}

		function getInfoAccount(){
			$link = $this->domain . '/private/api/v2/json/accounts/current';
			return $this->getCurl($link, true);



		}



		function getLeadsByContact($id){

			$link = $this->domain . '/private/api/v2/json/links/list/?links[0][from]=contact&links[0][from_id]=' . $id . '&links[0][to]=leads';
			$out =  $this->getCurl($link, true);
			foreach ($out->response->links as $link){

				$leads[] = $link->to_id;
			}
			return $leads;




		}
		function getLead($id){
			$link=  $this->domain . '/private/api/v2/json/leads/list?id=' . $id;
			$out =  $this->getCurl($link, true);
			return $out;


		}
		function editLead($array){
			$link=  $this->domain . '/private/api/v2/json/leads/set';
			$out = $this->postCurl($link, $array, true);
			return $out;


		}






	}

?>