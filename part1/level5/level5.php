<!-- PARTE 5 -->
<?php
$data = '{
  "contract_modifications": [
    {
      "contract_id": 1,
      "end_date": "2018-12-25",
      "id": 1
    },
    {
      "contract_id": 1,
      "end_date": "2019-12-25",
      "id": 2,
      "provider_id": 3,
      "start_date": "2018-12-25"
    }
  ],
  "contracts": [
    {
      "end_date": "2019-12-25",
      "green": false,
      "id": 1,
      "provider_id": 1,
      "start_date": "2017-12-25",
      "user_id": 1
    },
    {
      "end_date": "2018-12-25",
      "green": true,
      "id": 2,
      "provider_id": 1,
      "start_date": "2017-12-25",
      "user_id": 2
    },
    {
      "end_date": "2018-12-25",
      "green": false,
      "id": 3,
      "provider_id": 2,
      "start_date": "2017-12-25",
      "user_id": 3
    }
  ],
  "providers": [
    {
      "cancellation_fee": true,
      "id": 1,
      "price_per_kwh": 0.15
    },
    {
      "cancellation_fee": true,
      "id": 2,
      "price_per_kwh": 0.145
    },
    {
      "cancellation_fee": false,
      "id": 3,
      "price_per_kwh": 0.145
    }
  ],
  "users": [
    {
      "id": 1,
      "yearly_consumption": 4000
    },
    {
      "id": 2,
      "yearly_consumption": 2000
    },
    {
      "id": 3,
      "yearly_consumption": 5000
    }
  ]
}';

$data = json_decode($data);

$contracts = $data->contracts;
$contract_modifications = $data->contract_modifications;
$providers = $data->providers;
$users = $data->users;
$bills = array();

foreach($contracts as $index1 => $contract){
    foreach($contract_modifications as $index2 => $contract_modification){
        if($contract_modification->contract_id == $contract->id){
            
            isset($contract_modification->green) ? $contract->green = $contract_modification->green : '';
            isset($contract_modification->provider_id) ? $contract->provider_id = $contract_modification->provider_id : '';
            isset($contract_modification->start_date) ? $contract->start_date = $contract_modification->start_date : '';
            isset($contract_modification->user_id) ? $contract->user_id = $contract_modification->user_id : '';
            isset($contract_modification->end_date) ? $contract->end_date = $contract_modification->end_date : '';
        }
    }
    
    foreach($providers as $index3 => $provider){
        if($provider->id == $contract->provider_id){
            // $contract_length = $contract->contract_length;
            $price_per_kwh = $provider->price_per_kwh;
        }
        
        if($provider->cancellation_fee){
            $plus = 50;
        }else{
            $plus = 0;
        }
    }
    
    foreach($users as $index4 => $user){
        if($user->id == $contract->user_id){
            
            $d1 = new DateTime($contract->end_date);
            $d2 = new DateTime($contract->start_date);
            $fecha = $d2->diff($d1);
            
            switch ($fecha->y) {
              case ($fecha->y <= 1):
                $precio = $price_per_kwh * $user->yearly_consumption * 0.9;
                break;
              case ($fecha->y > 1 && $fecha->y <= 3):
                $precio = $price_per_kwh * $user->yearly_consumption * 0.8;
                break;
              case ($fecha->y > 3):
                $precio = $price_per_kwh * $user->yearly_consumption * 0.75;
                break;
              default:
                # code...
                break;
            }
            
            if($contract->green){
                $rebaja = $user->yearly_consumption * 0.05;
                $precio = $precio - $rebaja;
            }
            
            $insurance_fee = 0.05 * 365 * $precio / 100;
            $provider_fee = $precio - $insurance_fee + $plus;
            $selectra_fee = $precio * 12.5 / 100;
            
            $comision = array(
                            "insurance_fee" => $insurance_fee,
                            "provider_fee" => $provider_fee,
                            "selectra_fee" => $selectra_fee
                          );
                          
            $bills['bills'][] = array(
                      "comision" => $comision,
                      "id" => $index3,
                      "price" => $precio,
                      "user_id" => $user->id
                    );
        }
    }
}

$bills = json_encode($bills);
print_r($bills);

?>