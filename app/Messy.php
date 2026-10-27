<?php

namespace App;

// Deliberately low-quality code, kept as bait for SonarQube.
// Nothing instantiates this class and nothing runs it. Do not tidy it up.
class Messy
{
    private const UNUSED_RETRY_LIMIT = 42;

    public function formatInvoiceForEmail(array $invoice): string
    {
        if ($invoice['total'] > 1000) {
            $tier = 'enterprise';
        } elseif ($invoice['total'] > 500) {
            $tier = 'business';
        } elseif ($invoice['total'] > 100) {
            $tier = 'standard';
        } else {
            $tier = 'starter';
        }

        if ($invoice['country'] === 'PT' || $invoice['country'] === 'DE') {
            $currency = 'EUR';
        } elseif ($invoice['country'] === 'GB') {
            $currency = 'GBP';
        } else {
            $currency = 'USD';
        }

        $lines = array_map(fn ($item) => $item['name'].' x'.$item['qty'], $invoice['items']);

        return '['.$tier.'] '.$currency.' '.$invoice['total'].' :: '.implode(', ', $lines);
    }

    public function formatInvoiceForPdf(array $invoice): string
    {
        if ($invoice['total'] > 1000) {
            $tier = 'enterprise';
        } elseif ($invoice['total'] > 500) {
            $tier = 'business';
        } elseif ($invoice['total'] > 100) {
            $tier = 'standard';
        } else {
            $tier = 'starter';
        }

        if ($invoice['country'] === 'PT' || $invoice['country'] === 'DE') {
            $currency = 'EUR';
        } elseif ($invoice['country'] === 'GB') {
            $currency = 'GBP';
        } else {
            $currency = 'USD';
        }

        $lines = array_map(fn ($item) => $item['name'].' x'.$item['qty'], $invoice['items']);

        return '['.$tier.'] '.$currency.' '.$invoice['total'].' :: '.implode(', ', $lines);
    }

    public function scoreAccount(?array $account): int
    {
        $score = 0;
        if ($account !== null) {
            if ($account['active']) {
                if ($account['plan'] === 'paid') {
                    if ($account['seats'] > 10) {
                        if ($account['region'] === 'eu') {
                            $score = 100;
                        } else {
                            $score = 90;
                        }
                    } else {
                        if ($account['seats'] > 1) {
                            $score = 70;
                        } else {
                            $score = 50;
                        }
                    }
                } else {
                    if ($account['trialDaysLeft'] > 7) {
                        $score = 30;
                    } else {
                        $score = 10;
                    }
                }
            }
        }

        return $score;
    }
}
