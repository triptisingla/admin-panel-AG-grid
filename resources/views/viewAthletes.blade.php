<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-enterprise/styles/ag-grid.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-enterprise/styles/ag-theme-quartz.css">
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community@31.1.1/dist/ag-grid-community.min.js"></script>
</head>

<body>
<div id="myGrid" style="height: 200px;" class="ag-theme-quartz"></div>
    <script type="module">
        import { Grid } from 'ag-grid-community';
        // ECMA 5 - using nodes require() method
        // var AgGrid = require('ag-grid-community');
        // import { createGrid } from 'ag-grid-community';
        const columnDefs = [
            { headerName: 'ID', field: 'id' },
            { headerName: 'Athlete', field: 'athlete' },
            { headerName: 'Age', field: 'age' },
            { headerName: 'Country', field: 'country' },
        ];

        // specify the data
        const rowData = [
            { make: "Toyota", model: `Corolla`, price: 35000 },
            { make: "Ford", model: "Mondeo", price: 32000 },
            { make: "Porsche", model: "Boxter", price: 72000 }
        ];
        const datasource = {
            getRows(params) {
            console.log(JSON.stringify(params.request, null, 1));

            fetch('/data/', {
                method: 'get',
                headers: { 'Content-Type': 'application/json; charset=utf-8' }
            })
            .then(response => {
                console.log(response);
                // params.successCallback(response.rows, response.lastRow);
            })
            .catch(error => {
                console.error(error);
                params.failCallback();
            })
    }
};

// register datasource with the grid
// api.setGridOption('serverSideDatasource', datasource);
        // let the grid know which columns and what data to use
        const gridOptions = {
            // rowModelType: 'serverSide',
            // serverSideDatasource: datasource,
            defaultColDef: {
                wrapText: true,
                autoHeight: true,
                filter: true
            },
            columnDefs: columnDefs,
            rowData: rowData
        };

        // setup the grid after the page has finished loading
        document.addEventListener('DOMContentLoaded', () => {
            const gridDiv = document.querySelector('#myGrid');
            new AgGrid.Grid(gridDiv, gridOptions);
            // const api = new AgGrid.Grid(gridDiv, gridOptions);
        });
    </script>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-enterprise/styles/ag-grid.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-enterprise/styles/ag-theme-quartz.css">
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-enterprise@31.1.1/dist/ag-grid-enterprise.min.js"></script>
    <style media="only screen">  
            html, body {
                height: 100%;
                width: 100%;
                margin: 0;
                box-sizing: border-box;
                -webkit-overflow-scrolling: touch;
            }

            html {
                position: absolute;
                top: 0;
                left: 0;
                padding: 0;
                overflow: auto;
            }

            body {
                padding: 16px;
                overflow: auto;
                background-color: transparent
            }
            </style>
</head>

<body>
    <div id="myGrid" style="height: 100%;" class="ag-theme-quartz"></div>
    <script src="ag-grid-community.js"></script>
    <script>
        // import { Grid } from 'ag-grid-community';
        // var {Grid} = require('ag-grid-community');
        const { Grid } = agGrid;

        const columnDefs = [
            { headerName: 'ID', field: 'id' ,sortable: true,},
            { headerName: 'Athlete', field: 'athlete', sortable: true },
            { headerName: 'Age', field: 'age',sortable: true, },
            { headerName: 'Country', field: 'country',sortable: true,rowGroup:true },
            { headerName: 'Year', field: 'year',sortable: true },
            { headerName: 'Date', field: 'date',sortable: true },
            { headerName: 'Sport',field: 'sport',sortable: true },
            { headerName: 'Gold',aggFunc: 'sum', field: 'gold',sortable: true },
            { headerName: 'Silver',aggFunc: 'sum', field: 'silver',sortable: true },
            { headerName: 'Bronze',aggFunc: 'sum', field: 'bronze',sortable: true },
            { headerName: 'Total',aggFunc: 'sum', field: 'total',sortable: true },
            // { field: 'country', rowGroup: true, hide: true },
            // { field: 'sport', rowGroup: true, hide: true },
            // { field: 'year', minWidth: 100 },
            // { field: 'gold', aggFunc: 'sum', enableValue: true },
            // { field: 'silver', aggFunc: 'sum', enableValue: true },
            // { field: 'bronze', aggFunc: 'sum', enableValue: true },
        ];
    
        const datasource = {
            async getRows(params) {
                const url = '/olympicWinners';
                const requestBody = JSON.stringify(params.request); // Convert params.request to JSON string
                console.log(requestBody);
                try {
                    const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: requestBody
                });
                console.log("response is :", response);
                if (!response.ok) {
                    console.log(response);
                    throw new Error('Network response was not ok');
                }   
                const data = await response.json();
                console.log("data is :", data.rows);
                params.success({
                    rowData: data.rows,
                    rowCount: data.lastRow,
                });
                } catch (error) {
                    console.error('Error fetching data:', error);
                    params.fail();
                }
            }
        };

        const gridOptions = {
            getChildCount: (data) => {
                return data ? data.childCount : undefined;
            },

            getRowId: params => {
                const parentKeysJoined = (params.parentKeys || []).join('-');
                let rowId = parentKeysJoined; // Initialize rowId with parent keys
                if (params.data.id != null) {
                    rowId += '-' + params.data.id; // Append row ID if available
                } else {
                    const rowGroupCols = params.api.getRowGroupColumns();
                    const thisGroupCol = rowGroupCols[params.level];
                    rowId += '-' + params.data[thisGroupCol.getColDef().field]; // Append group value
                }
                return rowId; // Return the generated row ID
            },  
            debug: true,
            defaultColDef: {
                flex: 1,
                minWidth: 120,
            },
            columnDefs: columnDefs,
            rowModelType: 'serverSide',
            serverSideDatasource: datasource
        };

        document.addEventListener('DOMContentLoaded', () => {
            const gridDiv = document.querySelector('#myGrid');
            new agGrid.createGrid(gridDiv, gridOptions);
        });
    </script>
</body>
</html>
