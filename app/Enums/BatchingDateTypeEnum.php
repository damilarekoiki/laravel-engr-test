<?php
namespace App\Enums;

enum BatchingDateTypeEnum: string
{
    case ENCOUNTER_DATE = 'encounter_date';
    case SUBMISSION_DATE = 'submission_date';
}