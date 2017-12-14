<!-- PARTE 3 -->
<?php
$data = '{
  "contracts": [
    {
      "contract_length": 2,
      "id": 1,
      "provider_id": 1,
      "user_id": 1
    },
    {
      "contract_length": 1,
      "id": 2,
      "provider_id": 1,
      "user_id": 2
    },
    {
      "contract_length": 1,
      "id": 3,
      "provider_id": 2,
      "user_id": 3
    }
  ],
  "providers": [
    {
      "id": 1,
      "price_per_kwh": 0.15
    },
    {
      "id": 2,
      "price_per_kwh": 0.145
    },
    {
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
$providers = $data->providers;
$users = $data->users;
$bills = array();

foreach($contracts as $index1 => $contract){
    foreach($providers as $index2 => $provider){
        if($provider->id == $contract->provider_id){
            $contract_length = $contract->contract_length;
            $price_per_kwh = $provider->price_per_kwh;
        }
    }

    foreach($users as $index3 => $user){
        if($user->id == $contract->user_id){

            switch ($contract_length) {
              case ($contract_length <= 1):
                $precio = $price_per_kwh * $user->yearly_consumption * 0.9;
                break;
              case ($contract_length > 1 && $contract_length <= 3):
                $precio = $price_per_kwh * $user->yearly_consumption * 0.8;
                break;
              case ($contract_length > 3):
                $precio = $price_per_kwh * $user->yearly_consumption * 0.75;
                break;
              default:
                # code...
                break;
            }

            $insurance_fee = 0.05 * 365 * $precio / 100;
            $provider_fee = $precio - $insurance_fee;
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