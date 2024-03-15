<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class AthleteController extends BaseController
{

    public function getAllData(Request $request)
    {
        // Fetch data from your database or other sources
        $sortModel = $request->input('sortModel');
        if ($sortModel) {
            $sortField = $sortModel[0]['colId']; // Assuming you're only sorting by one column
            $sortOrder = $sortModel[0]['sort'];
        } else {
            // Default sorting if no sortModel provided
            $sortField = 'id'; // Default sort field
            $sortOrder = 'asc'; // Default sort order
        }
         // Query your data source (e.g., database) to fetch the data
        $query = Athlete::query(); // Replace Athlete with your actual model name

        // Sort the data based on the sorting parameters
        if ($sortField) {
            $query->orderBy($sortField, $sortOrder);
        }

        // Fetch the sorted data
        $rows = $query->get();

        // Prepare response data
        $data = [
            'rows' => $rows,
            'lastRow' => count($rows),
        ];

        // Return the sorted data as JSON response
        return response()->json($data);




        // $rows = Athlete::all(); // Example: Get all data from the Data mode
        // $data['rows']=$rows;
        // $data['lastRow']=count($rows);
        // return response()->json($data);
    }
    public function getSetFilterValues(Request $request, $field)
    {
        $values = DB::table('athletes')->select($field)->distinct()->orderBy($field, 'asc')->pluck($field);
        return $values;
    }
    public function getData(Request $request)
    {
        $SQL = $this->buildSql($request);
        // for debugging purposes - logs are saved to storage/logs/laravel.log
        Log::debug($SQL); 
        $results = DB::select($SQL);
        $rowCount = $this->getRowCount($request, $results);
        $resultsForPage = $this->cutResultsToPageSize($request, $results);
        
        return ['rows' => $resultsForPage, 'lastRow' => $rowCount];
    }


    public function buildSql(Request $request)
    {
        $selectSql = $this->createSelectSql($request);
        $fromSql = " FROM athletes ";
        $whereSql = $this->whereSql($request);
        $groupBySql = $this->groupBySql($request);
        $orderBySql = $this->orderBySql($request);
        $limitSql = $this->createLimitSql($request);

        $SQL = $selectSql . $fromSql . $whereSql . $groupBySql . $orderBySql . $limitSql;
        // dd("query is :::::",  $SQL," END HERE");
        return $SQL;
    }

    public function createSelectSql(Request $request)
    {
        $rowGroupCols = $request->input('rowGroupCols');
        $valueCols = $request->input('valueCols');
        $groupKeys = $request->input('groupKeys');

        if ($this->isDoingGrouping($rowGroupCols, $groupKeys)) {
            $colsToSelect = [];

            $rowGroupCol = $rowGroupCols[sizeof($groupKeys)];
            array_push($colsToSelect, $rowGroupCol['field']);

            foreach ($valueCols as $key => $value) {
                array_push($colsToSelect, $value['aggFunc'] . '(' . $value['field'] . ') as ' . $value['field']);
            }

            return "SELECT " . join(", ", $colsToSelect);
        }

        return "SELECT * ";
    }

    public function whereSql(Request $request)
    {
        $rowGroupCols = $request->input('rowGroupCols');
        $groupKeys = $request->input('groupKeys');
        $filterModel = $request->input('filterModel');

        $whereParts = [];

        if (sizeof($groupKeys) > 0) {
            foreach ($groupKeys as $key => $value) {
                $colName = $rowGroupCols[$key]['field'];
                array_push($whereParts, "{$colName} = '{$value}'");
            }
        }

        if ($filterModel) {
            foreach ($filterModel as $key => $value) {
                if ($value['filterType'] == 'set') {
                    array_push($whereParts, $key . ' IN ("'  . join('", "', $value['values']) . '")');
                }
            }
        }

        if (sizeof($whereParts) > 0) {
            return " WHERE " . join(' and ', $whereParts);
        } else {
            return "";
        }
    }

    public function groupBySql(Request $request)
    {

        $rowGroupCols = $request->input('rowGroupCols');
        $groupKeys = $request->input('groupKeys');

        if ($this->isDoingGrouping($rowGroupCols, $groupKeys)) {
            $colsToGroupBy = [];

            $rowGroupCol = $rowGroupCols[sizeof($groupKeys)];
            array_push($colsToGroupBy, $rowGroupCol['field']);

            return " GROUP BY " . join(", ", $colsToGroupBy);
        } else {
            // select all columns
            return "";
        }
    }

    public function orderBySql(Request $request)
    {
        $sortModel = $request->input('sortModel');

        if ($sortModel) {
            $sortParts = [];

            foreach ($sortModel as $key => $value) {
                array_push($sortParts, $value['colId'] . " " . $value['sort']);
            }

            if (sizeof($sortParts) > 0) {
                return " ORDER BY " . join(", ", $sortParts);
            } else {
                return '';
            }
        }
    }

    public function isDoingGrouping($rowGroupCols, $groupKeys)
    {
        // we are not doing grouping if at the lowest level. we are at the lowest level
        // if we are grouping by more columns than we have keys for (that means the user
        // has not expanded a lowest level group, OR we are not grouping at all).

        return sizeof($rowGroupCols) > sizeof($groupKeys);
    }

    public function createLimitSql(Request $request)
    {
        $startRow = $request->input('startRow');
        $endRow = $request->input('endRow');
        $pageSize = ($endRow - $startRow) + 1;

        return " LIMIT {$pageSize} OFFSET {$startRow};";
    }

    public function getRowCount($request, $results)
    {
        if (is_null($results) || !isset($results) || sizeof($results) == 0) {
            // or return null
            return 0;
        }

        $currentLastRow = $request['startRow'] + sizeof($results);

        if ($currentLastRow <= $request['endRow']) {
            return $currentLastRow;
        } else {
            return -1;
        }
    }

    public function cutResultsToPageSize($request, $results)
    {
        $pageSize = $request['endRow'] - $request['startRow'];

        if ($results && (sizeof($results) > $pageSize)) {
            return array_splice($results, 0, $pageSize);
        } else {
            return $results;
        }
    }
}