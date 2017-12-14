<!-- PARTE 1 -->
<?php
$data = '{
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
      "provider_id": 1,
      "yearly_consumption": 4000
    },
    {
      "id": 2,
      "provider_id": 1,
      "yearly_consumption": 2000
    },
    {
      "id": 3,
      "provider_id": 2,
      "yearly_consumption": 5000
    }
  ]
}';

$data = json_decode($data);

$providers = $data->providers;
$users = $data->users;
$bills = array();

foreach($users as $index1 => $user){
    foreach($providers as $index2 => $provider){
        if($provider->id == $user->provider_id){
            $bills['bills'][] = array(
                      "id" => $index1,
                      "price" => $provider->price_per_kwh * $user->yearly_consumption,
                      "user_id" => $user->id
                    );
        }
    }
}

$bills = json_encode($bills);
print_r($bills);

?>