<?php

if (! function_exists('thousandSeparator')) {
    /**
     * Format a number with thousand separators using dot.
     *
     * @param int|float|string $number
     * @return string
     */
    function thousandSeparator($number)
    {
        // Remove any commas from the number, if present
        $number = str_replace(',', '', $number);
        $number = str_replace('.', '', $number);

        // Force the input to be treated as a float
        $number = floatval($number);

        // Format number with dot as thousand separator
        return number_format($number, 0, ',', '.');
    }
}

if (! function_exists('saveTenderLog')) {
    /**
     * Save a log entry in trx_tender__log table.
     *
     * @param string $action
     * @param array $data
     * @return void
     */
    function saveTenderLog($data = [])
    {
        \App\Models\TrxTenderLog::create([
            'name' => !empty($data['name'])?$data['name']:'-',
            'description' => !empty($data['description'])?$data['description']:'-',
            'trx_tender_id' => !empty($data['trx_tender_id'])?$data['trx_tender_id']:'-', // assuming you have authenticated users
            'created_at' => now(),
        ]);
    }
}

if (!function_exists('thousandToNumber')) {
    /**
     * Convert a formatted string with dots as thousand separators into a number.
     *
     * @param string $value
     * @return int|float
     */
    function thousandToNumber($value) {
        // Remove dots from the string
        $value = str_replace('.', '', $value);

        // Convert the resulting string to an integer
        return (int) $value;
    }
}

if (!function_exists('rfqNoKzToPrinciple')) {
    /**
     * Generate a custom formatted unique number.
     *
     * @return string
     */
    function rfqNoKzToPrinciple()
    {
        $year = date('Y'); // Get current year
        $month = date('n'); // Get current month as an integer (1-12)
        
        // Mapping of month number to Roman numeral
        $monthsRoman = [
            1 => 'I', 
            2 => 'II', 
            3 => 'III', 
            4 => 'IV', 
            5 => 'V', 
            6 => 'VI', 
            7 => 'VII', 
            8 => 'VIII', 
            9 => 'IX', 
            10 => 'X', 
            11 => 'XI', 
            12 => 'XII'
        ];

        $monthRoman = $monthsRoman[$month]; // Convert month to Roman numeral

        // Fetch the last used sequence number from the database
        $lastNumber = \App\Models\TrxTenderRfq::max('sequence_number');
        $sequence = $lastNumber ? $lastNumber + 1 : 1; // Increment the last number or start from 1 if none found

        // Format the number according to the specified pattern
        $number = "{$sequence}/KZ-RFQ/{$monthRoman}/{$year}";
        
        // Check if the generated number already exists and regenerate if necessary
        while (\App\Models\TrxTenderRfq::where('rfq_no', $number)->exists()) {
            $sequence++; // Increment sequence number if the number already exists
            $number = "{$sequence}/KZ-RFQ/{$monthRoman}/{$year}";
        }
        
        // Store the new sequence number in the database (assuming YourModel has 'sequence_number' field)
        // \App\Models\TrxTenderRfq::create(['sequence_number' => $sequence, 'rfq_no' => $number]);
        
        return $number;
    }
}

if (!function_exists('priceToWords')) {
    /**
     * Convert a numeric price into words.
     *
     * @param int|string $price
     * @return string
     */
    function priceToWords($price)
    {
        // Define word representations for numbers
        $ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
        $thousands = ['', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'];

        // Handle edge case for zero
        if ($price == 0) {
            return 'zero';
        }

        // Split number and handle negative
        $isNegative = $price < 0;
        $price = abs($price);
        
        // Convert the number to words
        $numWords = [];

        // Split number into groups of three digits
        $chunkCount = 0;
        do {
            $chunk = $price % 1000;
            if ($chunk != 0) {
                $chunkWords = [];

                // Handle hundreds
                if ($chunk >= 100) {
                    $chunkWords[] = $ones[(int)($chunk / 100)] . ' hundred';
                    $chunk %= 100;
                }

                // Handle tens and ones
                if ($chunk >= 20) {
                    $chunkWords[] = $tens[(int)($chunk / 10)];
                    $chunk %= 10;
                } elseif ($chunk >= 10) {
                    $chunkWords[] = $teens[$chunk - 10];
                    $chunk = 0;
                }
                if ($chunk > 0) {
                    $chunkWords[] = $ones[$chunk];
                }

                // Add thousand/million/billion etc. suffix
                $chunkWords = array_filter($chunkWords); 
                $chunkWords[] = $thousands[$chunkCount];
                $numWords[] = implode(' ', $chunkWords);
            }

            // Move to the next group
            $price = (int)($price / 1000);
            $chunkCount++;
        } while ($price > 0);

        // Reverse the order of words and handle negative
        $numWords = array_reverse($numWords);
        $words = implode(' ', $numWords);
        return $isNegative ? "negative $words" : $words;
    }
}
