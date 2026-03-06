<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function download()
    {
        $databaseName = config('database.connections.mysql.database');
        $fileName = 'backup-' . $databaseName . '-' . date('Y-m-d_H-i-s') . '.sql';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            
            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . config('database.connections.mysql.database');

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                
                // Structure
                $createTable = DB::select('SHOW CREATE TABLE `' . $tableName . '`')[0]->{'Create Table'};
                fwrite($handle, "\n\n" . $createTable . ";\n\n");

                // Data
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $keys = array_keys($rowArray);
                    $values = array_values($rowArray);
                    
                    $escapedValues = array_map(function($value) {
                        if (is_null($value)) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, $values);

                    $sql = "INSERT INTO `$tableName` (`" . implode('`, `', $keys) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
                    fwrite($handle, $sql);
                }
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/sql');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}
