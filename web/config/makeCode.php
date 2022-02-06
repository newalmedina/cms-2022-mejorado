<?php

use Carbon\Carbon;

return [
    'category_prefix' => 'CAT-' .  Carbon::now()->format('y') . '-',
    'product_prefix' => 'PROD-' .  Carbon::now()->format('y') . '-',
    'user_prefix' => 'USE-',

];
