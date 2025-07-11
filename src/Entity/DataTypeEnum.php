<?php

namespace src\Entity;

enum DataTypeEnum: string
{
    case fetchedFromApi = 'fetchedFromApi';
    case createdManually = 'createdManually';
    case mockData = 'mockData';
}
