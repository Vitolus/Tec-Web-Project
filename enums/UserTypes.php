<?php

namespace Enums;

enum UserTypes: string  {
    case USER = "user";
    case INSTRUCTOR = "instructor";
    case ADMIN = "admin";
}