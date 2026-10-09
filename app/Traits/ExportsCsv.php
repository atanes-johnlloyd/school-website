<?php

namespace App\Traits;

trait ExportsCsv
{
    /**
     * Force a cell value to be treated as text by Excel/Sheets.
     * Uses the ="..." formula trick so long digit strings (LRN, phone, etc.)
     * don't get mangled into scientific notation.
     */
    protected function textCell(?string $value): string
    {
        if ($value === null || $value === '') return '';
        return '="' . str_replace('"', '""', $value) . '"';
    }

    /**
     * Standard streamed CSV response with BOM for Excel UTF-8 support.
     */
    protected function csvResponse(string $filename, array $headers, callable $writer): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $writer) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $headers);
            $writer($handle);
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}