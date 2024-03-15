<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AthleteControllerDoc extends BaseController
{
    public function getData(Request $request)
    {
        $allData = []; 
        $allData=Athlete::all();// Your data source, e.g., fetched from a database

        $groupByResult = $this->executeQuery($request, $allData);

        return response()->json([
            'success' => true,
            'rows' => $groupByResult,
            'lastRow' => $this->getLastRowIndex($request, $groupByResult),
        ]);
    }

    private function executeQuery(Request $request, $allData)
    {
        $groupByResult = $this->executeRowGroupQuery($request);
        $rowGroupCols = $request->input('rowGroupCols');
        $groupKeys = $request->input('groupKeys');
        
        if (!$this->isDoingGrouping($rowGroupCols, $groupKeys)) {
            return $groupByResult;
        }
        
        $groupsToUse = array_slice($request->input('rowGroupCols'), count($groupKeys), 1);
        $groupColId = $groupsToUse[0]['id'];
        $childCountResult = $this->executeGroupChildCountsQuery($request, $groupColId);
        // $groupId="Argentina";
        // dd ($childCountResult);

        foreach ($groupByResult as &$group) {
            $groupId = $group->$groupColId;
            // dd($group);
            $group->childCount = isset($childCountResult[$groupId]) ? $childCountResult[$groupId] : 0;
        }
        // var_dump($groupByResult);
        return ($groupByResult);


    }

    private function executeRowGroupQuery(Request $request)
    {
        $groupByQuery = $this->buildGroupBySql($request);

        // Perform SQL query using Laravel DB facade
        $groupByResult = DB::select($groupByQuery);

        return $groupByResult;
    }

    private function executeGroupChildCountsQuery(Request $request, $groupId)
    {
            // Construct the SQL query for group child counts
            $whereSql = $this->whereSql($request);
            
            // dd("request : ",$request,"group id : ",$groupId,"all data : ",$allData);
            // Construct the SELECT part of the SQL query with conditional aggregation
            $selectSql = "SELECT {$groupId}, COUNT(*) AS child_count";
            $fromSql = "FROM athletes";
            $groupBySql="GROUP BY {$groupId}";
            // Construct the complete SQL query
            $SQL = "$selectSql $fromSql $whereSql $groupBySql";
            // dd($SQL);
            // Log the SQL query for debugging
            Log::debug('[FakeServer] - about to execute group child count query: ' . $SQL);
            
            // Execute the raw SQL query using Laravel's DB facade
            $childCountResult = DB::select($SQL);
            // dd($childCountResult);
            $formattedResults = [];
            foreach ($childCountResult as $result) {
                $formattedResults[$result->$groupId] = $result->child_count;
            }
        
            return $formattedResults;
    
        // return $childCountResult;
    }
    


    private function buildGroupBySql(Request $request)
    {
         // Construct the SELECT part of the SQL query
    $selectSql = $this->selectSql($request);

    // Construct the FROM part of the SQL query
    $fromSql = 'FROM athletes'; // Assuming 'athletes' is the table name

    // Construct the WHERE part of the SQL query
    $whereSql = $this->whereSql($request);

    // Construct the GROUP BY part of the SQL query
    $groupBySql = $this->groupBySql($request);

    // Construct the ORDER BY part of the SQL query
    $orderBySql = $this->orderBySql($request);

    // Construct the LIMIT part of the SQL query
    $limitSql = $this->limitSql($request);

    // Concatenate all parts of the SQL query
    $SQL = "$selectSql $fromSql $whereSql $groupBySql $orderBySql $limitSql";
        // echo("SQL IS ::::::".$SQL."END HERE");
    return $SQL;
    }

    // private function selectSql(Request $request)
    // {
    //     $rowGroupCols = $request->input('rowGroupCols');
    //     $valueCols = $request->input('valueCols');
    //     $groupKeys = $request->input('groupKeys');

    //     if ($this->isDoingGrouping($rowGroupCols, $groupKeys)) {
    //         $rowGroupCol = $rowGroupCols[sizeof($groupKeys)];
    //         $colsToSelect = [$rowGroupCol['id']];

    //         foreach ($valueCols as $valueCol) {
    //             $colsToSelect[] = $valueCol['aggFunc'] . '(' . $valueCol['id'] . ') AS ' . $valueCol['id'];
    //         }

    //         return 'SELECT ' . implode(', ', $colsToSelect);
    //     }

    //     return 'SELECT *';
    // }

    public function selectSql(Request $request)
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

    // private function whereSql(Request $request)
    // {
    //     $rowGroupCols = $request->input('rowGroupCols');
    //     $groupKeys = $request->input('groupKeys');
    //     $whereParts = [];

    //     if (sizeof($groupKeys) > 0) {
    //         foreach ($groupKeys as $key => $value) {
    //             $colName = $rowGroupCols[$key]['id'];
    //             $whereParts[] = "{$colName} = '{$value}'";
    //         }
    //     }
        
    //     if (sizeof($whereParts) > 0) {
    //         return ' WHERE ' . implode(' AND ', $whereParts);
    //     } else {
    //         return '';
    //     }
    // }

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

    // private function groupBySql(Request $request)
    // {
    //     $rowGroupCols = $request->input('rowGroupCols');
    //     $groupKeys = $request->input('groupKeys');

    //     if ($this->isDoingGrouping($rowGroupCols, $groupKeys)) {
    //         $rowGroupCol = $rowGroupCols[sizeof($groupKeys)];
    //         return ' GROUP BY ' . $rowGroupCol['id'] . ' HAVING COUNT(*) > 0';
    //     }

    //     return '';
    // }

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

    // private function orderBySql(Request $request)
    // {
    //     $sortModel = $request->input('sortModel');

    //     if (empty($sortModel)) {
    //         return '';
    //     }

    //     $sorts = array_map(function ($s) {
    //         return $s['colId'] . ' ' . strtoupper($s['sort']);
    //     }, $sortModel);

    //     return ' ORDER BY ' . implode(', ', $sorts);
    // }

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

    private function limitSql(Request $request)
    {
        $startRow = $request->input('startRow');
        $endRow = $request->input('endRow');

        if ($startRow === null || $endRow === null) {
            return '';
        }

        $blockSize = $endRow - $startRow;

        return ' LIMIT ' . $blockSize . ' OFFSET ' . $startRow;
    }


    private function isDoingGrouping($rowGroupCols, $groupKeys)
    {
        // Implement your logic to determine if grouping is being done
        return count($rowGroupCols) > count($groupKeys);
    }

    private function getLastRowIndex(Request $request, $groupByResult)
    {
        // Implement your logic to get the last row index
        return count($groupByResult); // Placeholder
    }
}