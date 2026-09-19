<?php

declare(strict_types=1);

namespace App\Enums;

enum FulfillmentStatus: string
{
    // Fisik Delivery
    case Packing = 'packing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';

    // Fisik Pickup
    case ReadyForPickup = 'ready_for_pickup';
    case PickedUp = 'picked_up';

    // Jasa
    case InProgressService = 'in_progress';
    case ReviewService = 'review';
    case RevisionService = 'revision';
    case CompletedService = 'completed';

    // Digital
    case AwaitingPayment = 'awaiting_payment';
    case DownloadReady = 'download_ready';

    public function label(): string
    {
        return match($this) {
            self::Packing => 'Sedang Dikemas',
            self::Shipped => 'Dalam Pengiriman',
            self::Delivered => 'Pesanan Diterima',
            self::ReadyForPickup => 'Siap Diambil',
            self::PickedUp => 'Sudah Diambil',
            self::InProgressService => 'Sedang Dikerjakan',
            self::ReviewService => 'Menunggu Review',
            self::RevisionService => 'Revisi',
            self::CompletedService => 'Selesai',
            self::AwaitingPayment => 'Menunggu Pembayaran',
            self::DownloadReady => 'Siap Diunduh',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Packing, self::InProgressService, self::AwaitingPayment, self::RevisionService => 'yellow',
            self::Shipped, self::ReadyForPickup, self::ReviewService => 'blue',
            self::Delivered, self::PickedUp, self::CompletedService, self::DownloadReady => 'green',
        };
    }
}
