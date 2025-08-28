<?php

namespace lib\Telegram;

enum TelegramParseModeEnum: string
{
    case HTML = 'HTML';
    case MARKDOWN = 'Markdown';
}
