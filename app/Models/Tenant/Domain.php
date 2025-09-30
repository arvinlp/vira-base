<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Domain extends BaseDomain
{
    use HasDatabase, HasDomains;
}