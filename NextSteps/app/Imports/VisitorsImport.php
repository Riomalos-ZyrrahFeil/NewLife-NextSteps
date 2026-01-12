<?php

namespace App\Imports;

use App\Models\Visitor;
use App\Models\Location;
use App\Models\MessageStatus;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class VisitorsImport implements ToModel, WithHeadingRow
{
  /**
   * Map each row from Excel to the Visitor model.
   */
  public function model(array $row)
  {
    if (empty($row['last_name']) || empty($row['first_name'])) {
      return null;
    }

    $contactNumber = preg_replace('/[^0-9]/', '', $row['contact_number'] ?? '');
    $contactNumber = substr($contactNumber, 0, 20);

    $age = is_numeric($row['age']) ? (int)$row['age'] : null;
    $middleName = ($row['middle_name'] === '-' || empty($row['middle_name'])) 
      ? null 
      : $row['middle_name'];

    // Handle Excel Date Conversion
    try {
      $rawTimestamp = $row['timestamp'];

      if (is_numeric($rawTimestamp)) {
        $timestamp = Carbon::instance(Date::excelToDateTimeObject($rawTimestamp));
      } else {
        $timestamp = Carbon::parse($rawTimestamp);
      }
      
      $firstVisitDate = $timestamp->toDateString();
      $firstVisitTime = $timestamp->toTimeString();
    } catch (\Exception $e) {
      $firstVisitDate = now()->toDateString();
      $firstVisitTime = now()->toTimeString();
    }

    $rawLocation = $row['time_of_service_attended'] ?? 'Main';
    $locationName = trim(preg_replace(
      '/^\d{1,2}:\d{2}\s?(am|pm)\s?/i', 
      '', 
      $rawLocation
    ));
    
    $location = Location::firstOrCreate(['location_name' => $locationName]);

    $visitor = Visitor::create([
      'last_name'        => $row['last_name'],
      'first_name'       => $row['first_name'],
      'middle_name'      => $middleName,
      'age'              => $age,
      'gender'           => $row['gender'] ?? 'Unknown',
      'contact_number'   => $contactNumber,
      'first_visit_date' => $firstVisitDate,
      'first_visit_time' => $firstVisitTime,
      'location_id'      => $location->location_id,
    ]);

    MessageStatus::create([
      'visitor_id' => $visitor->visitor_id,
      'status'     => 'Not Texted'
    ]);

    return $visitor;
  }
}