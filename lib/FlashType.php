<?php

namespace lib;

enum FlashType: string
{
    case error = 'error';
    case success = 'success';
    case info = 'info';
    case warning = 'warning';
}