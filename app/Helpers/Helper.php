<?php

function isActiveMenu($pattern)
{
    return request()->is($pattern) ? 'active' : '';
}
