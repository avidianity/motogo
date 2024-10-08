<?php

namespace App\Processors;

use App\Enums\Role;
use App\Exceptions\Processors\CustomerRegistrationException;
use App\Interfaces\Processor;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CustomerRegistration implements Processor
{
    protected ?array $data = null;

    public function withData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function process()
    {
        if (!$this->data) {
            throw new CustomerRegistrationException('Data is required');
        }

        return DB::transaction(function () {
            try {
                /**
                 * @var User
                 */
                $customer = User::make($this->data)->fill([
                    'role'=> Role::CUSTOMER,
                    'blocked' => false,
                    'approved' => false,
                ]);
                $customer->save();


                return $customer;
            } catch (Exception $e) {
                throw new CustomerRegistrationException($e->getMessage(), $e);
            }
        });
    }
}
