var AgGrid = require('ag-grid-community');
import { createGrid } from 'ag-grid-community';

import 'ag-grid-enterprise';


function getAthletes()
{
    var grid=document.querySelector('#grid');
    Grid.createGrid(grid, gridOptions);

    const gridOptions = {
        rowModelType: 'serverSide',
        serverSideDatasource: myDatasource,
        columnDefs: [
            { headerName: 'ID', field: 'id' },
            { headerName: 'Athlete', field: 'athlete' },
            { headerName: 'Age', field: 'age' },
            { headerName: 'Country', field: 'country' },
                // Add more column definitions as needed

        ],
        // other grid options ...
    }
    const myDatasource = server => {
        return {
            // called by the grid when more rows are required
            getRows: params => {
    
                // get data for request from server
                // const response = server.getData(params.request);
                fetch('/data')
                .then(response =>
                    {
                        console.log(response)
                        // rowData= response
                    }
                )
                .catch(error => console.error('Error fetching data:', error));
    
                // if (response.success) {
                //     // supply rows for requested block to grid
                //     params.success({
                //         rowData: response.rows
                //     });
                // } else {
                //     // inform grid request failed
                //     params.fail();
                // }
            }
        };
    }
}

    document.addEventListener('DOMContentLoaded', function() {
    //     var gridDiv = document.querySelector('#grid');
    //     var gridOptions = {
    //         columnDefs: [
    //             { headerName: 'ID', field: 'id' },
    //             { headerName: 'Athlete', field: 'athlete' },
    //             { headerName: 'Age', field: 'age' },
    //             { headerName: 'Country', field: 'country' },
    //                 // Add more column definitions as needed
                    
    //         ],
    //         // rowData: data ,
    //         defaultColDef: {
    //             sortable: true,
    //             filter: true,
    //         },
    //     };

    //     // Initialize ag-Grid
    //     new agGrid.Grid(gridDiv, gridOptions);

    //     // Fetch data from Laravel backend using AJAX
    //     fetch('/data')
    //         .then(response => response.json())
    //         .then(data => gridOptions.api.setRowData(data))
    //         .catch(error => console.error('Error fetching data:', error));
    getAthletes();
    });