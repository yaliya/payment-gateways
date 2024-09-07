<?php

namespace App\Contracts;

interface RefundProcessorInterface
{
    public function refund(): RefundProcessorInterface;
}