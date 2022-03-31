<?php
namespace App\Models\Traits;

use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;

trait MyBillable
{
    use Billable;

    /**
     *  Override stripe configuration based on User's country preference
     *
     * @param  array  $options
     * @return \Stripe\StripeClient
     */
    public function stripe(array $options = [])
    {
        if (true) {
            config(['cashier.key' => 'pk_test_DP6w7JVPSpYp5HhNkHLR43bw']);
            config(['cashier.secret' => 'sk_test_8xtCY25aFec4DUcmVmzhcwQT']);
        }

        return Cashier::stripe($options);
    }
}