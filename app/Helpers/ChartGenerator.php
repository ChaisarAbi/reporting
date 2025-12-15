<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ChartGenerator
{
    /**
     * Check if GD functions are available
     */
    private static function isGdAvailable()
    {
        return function_exists('imagecreatetruecolor') && 
               function_exists('imagecolorallocate') && 
               function_exists('imagefill') &&
               function_exists('imageline') &&
               function_exists('imagepng') &&
               function_exists('imagedestroy');
    }

    /**
     * Generate bar chart for monthly statistics
     */
    public static function generateMonthlyBarChart($monthlyStats, $width = 800, $height = 400)
    {
        try {
            if (empty($monthlyStats) || count($monthlyStats) === 0) {
                return null;
            }

            // Check if GD is available
            if (!self::isGdAvailable()) {
                return null;
            }

            $image = imagecreatetruecolor($width, $height);
            if (!$image) {
                return null;
            }
        
            // Background color (white)
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $backgroundColor);
        
            // Chart area dimensions
            $chartX = 80;
            $chartY = 50;
            $chartWidth = $width - 100;
            $chartHeight = $height - 100;
            
            // Grid and axis colors
            $gridColor = imagecolorallocate($image, 220, 220, 220);
            $axisColor = imagecolorallocate($image, 100, 100, 100);
            $textColor = imagecolorallocate($image, 50, 50, 50);
            
            // Draw grid lines
            $gridLines = 5;
            for ($i = 0; $i <= $gridLines; $i++) {
                $y = (int)($chartY + ($chartHeight / $gridLines * $i));
                imageline($image, $chartX, $y, $chartX + $chartWidth, $y, $gridColor);
            }
            
            // Draw axes
            imageline($image, $chartX, (int)$chartY, $chartX, (int)($chartY + $chartHeight), $axisColor); // Y-axis
            imageline($image, $chartX, (int)($chartY + $chartHeight), (int)($chartX + $chartWidth), (int)($chartY + $chartHeight), $axisColor); // X-axis
            
            // Find max value for scaling
            $maxValue = 0;
            foreach ($monthlyStats as $stat) {
                if ($stat->total_reports > $maxValue) {
                    $maxValue = $stat->total_reports;
                }
            }
            
            if ($maxValue == 0) $maxValue = 1;
            
            // Draw bars
            $barCount = count($monthlyStats);
            $barWidth = ($chartWidth - 20) / $barCount;
            $barSpacing = 10;
            $actualBarWidth = $barWidth - $barSpacing;
            
            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            foreach ($monthlyStats as $index => $stat) {
                $barHeight = ($stat->total_reports / $maxValue) * ($chartHeight - 20);
                $x1 = $chartX + ($index * $barWidth) + $barSpacing / 2;
                $y1 = $chartY + $chartHeight - $barHeight;
                $x2 = $x1 + $actualBarWidth;
                $y2 = $chartY + $chartHeight;
                
                // Gradient color for bars
                $color1 = imagecolorallocate($image, 59, 130, 246); // Blue
                $color2 = imagecolorallocate($image, 96, 165, 250); // Lighter blue
                
                // Draw bar with gradient
                for ($i = (int)$x1; $i < (int)$x2; $i++) {
                    $ratio = ($i - $x1) / $actualBarWidth;
                    $r = (int)(59 + (96 - 59) * $ratio);
                    $g = (int)(130 + (165 - 130) * $ratio);
                    $b = (int)(246 + (250 - 246) * $ratio);
                    $color = imagecolorallocate($image, $r, $g, $b);
                    imageline($image, $i, (int)$y1, $i, (int)$y2, $color);
                }
                
                // Draw value on top of bar
                $valueText = (string)$stat->total_reports;
                $textWidth = imagefontwidth(3) * strlen($valueText);
                $textX = (int)($x1 + ($actualBarWidth / 2) - ($textWidth / 2));
                imagestring($image, 3, $textX, (int)($y1 - 20), $valueText, $textColor);
                
                // Draw month label
                $monthName = $monthNames[$stat->month - 1] ?? $stat->month;
                $labelText = $monthName . ' ' . $stat->year;
                $labelWidth = imagefontwidth(2) * strlen($labelText);
                $labelX = (int)($x1 + ($actualBarWidth / 2) - ($labelWidth / 2));
                imagestring($image, 2, $labelX, (int)($y2 + 5), $labelText, $textColor);
            }
            
            // Draw Y-axis labels
            for ($i = 0; $i <= $gridLines; $i++) {
                $value = (int)($maxValue / $gridLines * $i);
                $y = (int)($chartY + $chartHeight - ($chartHeight / $gridLines * $i));
                $valueText = (string)$value;
                $textWidth = imagefontwidth(3) * strlen($valueText);
                imagestring($image, 3, (int)($chartX - $textWidth - 10), (int)($y - 6), $valueText, $textColor);
            }
            
            // Draw chart title
            $title = "Monthly Breakdown Statistics";
            $titleWidth = imagefontwidth(4) * strlen($title);
            $titleX = (int)(($width / 2) - ($titleWidth / 2));
            imagestring($image, 4, $titleX, 15, $title, $textColor);
            
            // Draw Y-axis label
            $yLabel = "Total Reports";
            $yLabelWidth = imagefontwidth(3) * strlen($yLabel);
            imagestringup($image, 3, 20, (int)($chartY + $chartHeight / 2 + $yLabelWidth / 2), $yLabel, $textColor);
            
            // Save image to temporary file
            $filename = 'chart_monthly_' . time() . '.png';
            $tempPath = storage_path('app/temp/' . $filename);
            
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            
            imagepng($image, $tempPath);
            imagedestroy($image);
            
            return $tempPath;
        } catch (\Exception $e) {
            // Log error if needed
            error_log('Chart generation error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate bar chart for machine breakdown frequency
     */
    public static function generateMachineBreakdownChart($machineBreakdowns, $width = 800, $height = 400)
    {
        try {
            if (empty($machineBreakdowns) || count($machineBreakdowns) === 0) {
                return null;
            }
            
            // Check if GD is available
            if (!self::isGdAvailable()) {
                return null;
            }
            
            $image = imagecreatetruecolor($width, $height);
            if (!$image) {
                return null;
            }
            
            // Background color (white)
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $backgroundColor);
            
            // Chart area dimensions
            $chartX = 100;
            $chartY = 50;
            $chartWidth = $width - 120;
            $chartHeight = $height - 100;
            
            // Colors
            $gridColor = imagecolorallocate($image, 220, 220, 220);
            $axisColor = imagecolorallocate($image, 100, 100, 100);
            $textColor = imagecolorallocate($image, 50, 50, 50);
            
            // Color palette for bars
            $colors = [
                [59, 130, 246],   // Blue
                [239, 68, 68],    // Red
                [16, 185, 129],   // Green
                [245, 158, 11],   // Yellow
                [139, 92, 246],   // Purple
                [236, 72, 153],   // Pink
                [6, 182, 212],    // Cyan
                [132, 204, 22],   // Lime
                [249, 115, 22],   // Orange
                [99, 102, 241],   // Indigo
            ];
            
            // Draw grid lines
            $gridLines = 5;
            for ($i = 0; $i <= $gridLines; $i++) {
                $y = $chartY + ($chartHeight / $gridLines * $i);
                imageline($image, $chartX, $y, $chartX + $chartWidth, $y, $gridColor);
            }
            
            // Draw axes
            imageline($image, $chartX, $chartY, $chartX, $chartY + $chartHeight, $axisColor);
            imageline($image, $chartX, $chartY + $chartHeight, $chartX + $chartWidth, $chartY + $chartHeight, $axisColor);
            
            // Find max value for scaling
            $maxValue = 0;
            foreach ($machineBreakdowns as $breakdown) {
                if ($breakdown->breakdown_count > $maxValue) {
                    $maxValue = $breakdown->breakdown_count;
                }
            }
            
            if ($maxValue == 0) $maxValue = 1;
            
            // Draw bars
            $barCount = count($machineBreakdowns);
            $barWidth = ($chartWidth - 20) / $barCount;
            $barSpacing = 10;
            $actualBarWidth = $barWidth - $barSpacing;
            
            foreach ($machineBreakdowns as $index => $breakdown) {
                $barHeight = ($breakdown->breakdown_count / $maxValue) * ($chartHeight - 20);
                $x1 = $chartX + ($index * $barWidth) + $barSpacing / 2;
                $y1 = $chartY + $chartHeight - $barHeight;
                $x2 = $x1 + $actualBarWidth;
                $y2 = $chartY + $chartHeight;
                
                // Get color from palette
                $colorIndex = $index % count($colors);
                $color = imagecolorallocate($image, 
                    $colors[$colorIndex][0], 
                    $colors[$colorIndex][1], 
                    $colors[$colorIndex][2]
                );
                
                // Draw bar
                imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
                
                // Draw value on top
                $valueText = (string)$breakdown->breakdown_count;
                $textWidth = imagefontwidth(3) * strlen($valueText);
                $textX = $x1 + ($actualBarWidth / 2) - ($textWidth / 2);
                imagestring($image, 3, $textX, $y1 - 20, $valueText, $textColor);
                
                // Draw machine name (truncated if too long)
                $machineName = isset($breakdown->machine) && isset($breakdown->machine->name) ? $breakdown->machine->name : 'Unknown';
                if (strlen($machineName) > 12) {
                    $machineName = substr($machineName, 0, 10) . '..';
                }
                $labelWidth = imagefontwidth(2) * strlen($machineName);
                $labelX = $x1 + ($actualBarWidth / 2) - ($labelWidth / 2);
                imagestring($image, 2, $labelX, $y2 + 5, $machineName, $textColor);
            }
            
            // Draw Y-axis labels
            for ($i = 0; $i <= $gridLines; $i++) {
                $value = (int)($maxValue / $gridLines * $i);
                $y = $chartY + $chartHeight - ($chartHeight / $gridLines * $i);
                $valueText = (string)$value;
                $textWidth = imagefontwidth(3) * strlen($valueText);
                imagestring($image, 3, $chartX - $textWidth - 10, $y - 6, $valueText, $textColor);
            }
            
            // Draw chart title
            $title = "Machine Breakdown Frequency";
            $titleWidth = imagefontwidth(4) * strlen($title);
            $titleX = ($width / 2) - ($titleWidth / 2);
            imagestring($image, 4, $titleX, 15, $title, $textColor);
            
            // Draw Y-axis label
            $yLabel = "Breakdown Count";
            $yLabelWidth = imagefontwidth(3) * strlen($yLabel);
            imagestringup($image, 3, 40, $chartY + $chartHeight / 2 + $yLabelWidth / 2, $yLabel, $textColor);
            
            // Draw X-axis label
            $xLabel = "Machine Name";
            $xLabelWidth = imagefontwidth(3) * strlen($xLabel);
            $xLabelX = $chartX + $chartWidth / 2 - $xLabelWidth / 2;
            imagestring($image, 3, $xLabelX, $height - 25, $xLabel, $textColor);
            
            // Save image to temporary file
            $filename = 'chart_machine_' . time() . '.png';
            $tempPath = storage_path('app/temp/' . $filename);
            
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            
            imagepng($image, $tempPath);
            imagedestroy($image);
            
            return $tempPath;
        } catch (\Exception $e) {
            // Log error if needed
            error_log('Machine chart generation error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate horizontal bar chart for event type frequency
     */
    public static function generateEventTypeChart($eventTypeFrequency, $width = 800, $height = 400)
    {
        try {
            if (empty($eventTypeFrequency) || count($eventTypeFrequency) === 0) {
                return null;
            }
            
            // Check if GD is available
            if (!self::isGdAvailable()) {
                return null;
            }
            
            $image = imagecreatetruecolor($width, $height);
            if (!$image) {
                return null;
            }
            
            // Background color (white)
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $backgroundColor);
            
            // Chart area dimensions
            $chartX = 200;
            $chartY = 60;
            $chartWidth = $width - 220;
            $chartHeight = $height - 80;
            
            // Colors
            $gridColor = imagecolorallocate($image, 220, 220, 220);
            $textColor = imagecolorallocate($image, 50, 50, 50);
            $bgBarColor = imagecolorallocate($image, 240, 240, 240);
            
            // Color palette
            $colors = [
                [59, 130, 246],   // Blue
                [239, 68, 68],    // Red
                [16, 185, 129],   // Green
                [245, 158, 11],   // Yellow
                [139, 92, 246],   // Purple
                [236, 72, 153],   // Pink
                [6, 182, 212],    // Cyan
                [132, 204, 22],   // Lime
                [249, 115, 22],   // Orange
                [99, 102, 241],   // Indigo
            ];
            
            // Calculate total frequency for percentages
            $totalFrequency = 0;
            foreach ($eventTypeFrequency as $event) {
                $totalFrequency += $event->frequency;
            }
            
            if ($totalFrequency == 0) $totalFrequency = 1;
            
            // Find max frequency for scaling
            $maxFrequency = 0;
            foreach ($eventTypeFrequency as $event) {
                if ($event->frequency > $maxFrequency) {
                    $maxFrequency = $event->frequency;
                }
            }
            
            if ($maxFrequency == 0) $maxFrequency = 1;
            
            // Calculate bar height
            $itemCount = count($eventTypeFrequency);
            $barHeight = min(25, ($chartHeight - 20) / $itemCount);
            $spacing = 8;
            
            // Draw each bar
            foreach ($eventTypeFrequency as $index => $event) {
                $y = $chartY + ($index * ($barHeight + $spacing));
                
                // Draw event name
                $eventName = (isset($event->name) ? $event->name : 'Unknown') . ' (' . (isset($event->category) ? $event->category : 'N/A') . ')';
                if (strlen($eventName) > 30) {
                    $eventName = substr($eventName, 0, 28) . '..';
                }
                imagestring($image, 3, 10, $y + ($barHeight / 2) - 8, $eventName, $textColor);
                
                // Draw background bar
                imagefilledrectangle($image, $chartX, $y, $chartX + $chartWidth, $y + $barHeight, $bgBarColor);
                
                // Draw colored bar
                $barWidth = ($event->frequency / $maxFrequency) * $chartWidth;
                $colorIndex = $index % count($colors);
                $color = imagecolorallocate($image, 
                    $colors[$colorIndex][0], 
                    $colors[$colorIndex][1], 
                    $colors[$colorIndex][2]
                );
                
                imagefilledrectangle($image, $chartX, $y, $chartX + $barWidth, $y + $barHeight, $color);
                
                // Draw frequency and percentage
                $percentage = round(($event->frequency / $totalFrequency) * 100, 1);
                $valueText = $event->frequency . ' (' . $percentage . '%)';
                $textWidth = imagefontwidth(3) * strlen($valueText);
                $textX = $chartX + $barWidth + 10;
                if ($textX + $textWidth > $width - 10) {
                    $textX = $chartX + $barWidth - $textWidth - 5;
                }
                imagestring($image, 3, $textX, $y + ($barHeight / 2) - 8, $valueText, $textColor);
            }
            
            // Draw chart title
            $title = "Event Type Frequency";
            $titleWidth = imagefontwidth(4) * strlen($title);
            $titleX = ($width / 2) - ($titleWidth / 2);
            imagestring($image, 4, $titleX, 15, $title, $textColor);
            
            // Save image to temporary file
            $filename = 'chart_event_' . time() . '.png';
            $tempPath = storage_path('app/temp/' . $filename);
            
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            
            imagepng($image, $tempPath);
            imagedestroy($image);
            
            return $tempPath;
        } catch (\Exception $e) {
            // Log error if needed
            error_log('Event type chart generation error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate pie chart for part usage
     */
    public static function generatePartUsageChart($partUsage, $width = 600, $height = 400)
    {
        try {
            if (empty($partUsage) || count($partUsage) === 0) {
                return null;
            }
            
            // Check if GD is available
            if (!self::isGdAvailable()) {
                return null;
            }
            
            $image = imagecreatetruecolor($width, $height);
            if (!$image) {
                return null;
            }
            
            // Background color (white)
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $backgroundColor);
            
            // Colors
            $textColor = imagecolorallocate($image, 50, 50, 50);
            
            // Color palette
            $colors = [
                [59, 130, 246],   // Blue
                [239, 68, 68],    // Red
                [16, 185, 129],   // Green
                [245, 158, 11],   // Yellow
                [139, 92, 246],   // Purple
                [236, 72, 153],   // Pink
                [6, 182, 212],    // Cyan
                [132, 204, 22],   // Lime
                [249, 115, 22],   // Orange
                [99, 102, 241],   // Indigo
            ];
            
            // Calculate total quantity
            $totalQuantity = 0;
            foreach ($partUsage as $part) {
                $totalQuantity += $part->total_quantity;
            }
            
            if ($totalQuantity == 0) $totalQuantity = 1;
            
            // Pie chart dimensions
            $centerX = $width / 2;
            $centerY = $height / 2;
            $radius = min($centerX, $centerY) - 50;
            
            // Draw pie chart
            $startAngle = 0;
            foreach ($partUsage as $index => $part) {
                $percentage = $part->total_quantity / $totalQuantity;
                $endAngle = $startAngle + (360 * $percentage);
                
                $colorIndex = $index % count($colors);
                $color = imagecolorallocate($image, 
                    $colors[$colorIndex][0], 
                    $colors[$colorIndex][1], 
                    $colors[$colorIndex][2]
                );
                
                // Draw pie slice
                for ($angle = $startAngle; $angle < $endAngle; $angle += 0.5) {
                    $rad1 = deg2rad($angle);
                    $rad2 = deg2rad(min($angle + 0.5, $endAngle));
                    
                    $points = [
                        $centerX, $centerY,
                        $centerX + cos($rad1) * $radius, $centerY + sin($rad1) * $radius,
                        $centerX + cos($rad2) * $radius, $centerY + sin($rad2) * $radius
                    ];
                    
                    imagefilledpolygon($image, $points, $color);
                }
                
                // Draw legend
                $legendX = 20;
                $legendY = 20 + ($index * 25);
                
                // Color box
                imagefilledrectangle($image, $legendX, $legendY, $legendX + 15, $legendY + 15, $color);
                imagerectangle($image, $legendX, $legendY, $legendX + 15, $legendY + 15, $textColor);
                
                // Part name and percentage
                $partName = isset($part->name) ? $part->name : 'Unknown';
                if (strlen($partName) > 20) {
                    $partName = substr($partName, 0, 18) . '..';
                }
                $legendText = $partName . ' - ' . round($percentage * 100, 1) . '% (' . $part->total_quantity . ')';
                imagestring($image, 3, $legendX + 20, $legendY, $legendText, $textColor);
                
                $startAngle = $endAngle;
            }
            
            // Draw chart title
            $title = "Part Usage Distribution";
            $titleWidth = imagefontwidth(4) * strlen($title);
            $titleX = ($width / 2) - ($titleWidth / 2);
            imagestring($image, 4, $titleX, $height - 30, $title, $textColor);
            
            // Save image to temporary file
            $filename = 'chart_part_' . time() . '.png';
            $tempPath = storage_path('app/temp/' . $filename);
            
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            
            imagepng($image, $tempPath);
            imagedestroy($image);
            
            return $tempPath;
        } catch (\Exception $e) {
            // Log error if needed
            error_log('Part usage chart generation error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Clean up temporary chart files
     */
    public static function cleanupTempFiles($ageInMinutes = 60)
    {
        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            return;
        }
        
        $files = glob($tempDir . '/*.png');
        $cutoffTime = time() - ($ageInMinutes * 60);
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffTime) {
                unlink($file);
            }
        }
    }
}
