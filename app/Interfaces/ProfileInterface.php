<?php

namespace App\Interfaces;

interface ProfileInterface
{
    public function my_account();
    public function my_account_update($request);
    public function my_profile();
    public function my_password_update($request);

    public function check_password($request);
}
