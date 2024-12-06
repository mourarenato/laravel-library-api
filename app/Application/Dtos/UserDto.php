<?php

namespace App\Application\Dtos;

use App\Domain\Entities\Models\UserDocument;

class UserDto extends BaseDto
{
    public int $id;

    public string $token;

    public ?UserDocument $userDocument;

    public int $templateId;
}
