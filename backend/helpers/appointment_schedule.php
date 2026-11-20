<?php

function normalizeAppointmentSlot($dateValue, $timeValue): array
{
    $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');

    if (!is_string($dateValue) || !is_string($timeValue)) {
        throw new InvalidArgumentException('Ngày hoặc giờ khám không hợp lệ.');
    }

    $dateValue = trim($dateValue);
    $timeValue = trim($timeValue);
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $dateValue, $timezone);
    $dateErrors = DateTimeImmutable::getLastErrors();

    if (
        !$date ||
        ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) ||
        $date->format('Y-m-d') !== $dateValue
    ) {
        throw new InvalidArgumentException('Ngày khám không hợp lệ.');
    }

    if (preg_match('/^\d{2}:\d{2}$/', $timeValue)) {
        $timeValue .= ':00';
    } elseif (!preg_match('/^\d{2}:\d{2}:00$/', $timeValue)) {
        throw new InvalidArgumentException('Giờ khám không hợp lệ.');
    }

    $allowedTimes = [
        '07:00:00',
        '07:40:00',
        '08:20:00',
        '09:00:00',
        '09:40:00',
        '10:20:00',
        '13:00:00',
        '13:40:00',
        '14:20:00',
        '15:00:00',
        '15:40:00',
        '16:20:00',
    ];

    if (!in_array($timeValue, $allowedTimes, true)) {
        throw new InvalidArgumentException('Giờ khám không thuộc khung giờ làm việc.');
    }

    $appointmentDateTime = DateTimeImmutable::createFromFormat(
        '!Y-m-d H:i:s',
        "{$dateValue} {$timeValue}",
        $timezone
    );

    if (!$appointmentDateTime || $appointmentDateTime <= new DateTimeImmutable('now', $timezone)) {
        throw new InvalidArgumentException('Không thể chọn khung giờ đã qua.');
    }

    return [
        'date' => $dateValue,
        'time' => $timeValue,
    ];
}
